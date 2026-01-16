<?php

declare(strict_types=1);

namespace Masqueradis\Routers;

use Composer\Autoload\ClassLoader;

class Router
{
    private ClassLoader $composerLoader;
    public function __construct(ClassLoader $loader) {
        $this->composerLoader = $loader;
    }

    public function dispatch(string $targetNamespace, string $uri): void
    {
        $dirPath = $this->getDirFromNamespace($targetNamespace);

        if (!is_dir($dirPath) || !$dirPath) {
            echo 'No directory for Controller: ' . $targetNamespace . PHP_EOL;
        }

        $files = glob($dirPath . '/*.php');

        foreach ($files as $file) {
            $className = basename($file, '.php');

            $fullClassName = rtrim($targetNamespace, '\\') . '\\' . $className;

            if (class_exists($fullClassName)) {
                if($this->scanClass($fullClassName, $uri)){
                    return;
                }
            }
        }
    }

    private function getDirFromNamespace(string $namespace): ?string
    {
        $prefixes = $this->composerLoader->getPrefixesPsr4();
        $namespace = trim($namespace, '\\');

        foreach ($prefixes as $prefix => $paths) {
            $prefixClean = trim($prefix, '\\');

            if (strpos($namespace, $prefixClean) === 0) {
               $basePathRaw = $paths[0];
               $basePath = realpath($basePathRaw);

               $subPath = substr($namespace, strlen($prefixClean));
               $subPath = trim($subPath, '\\');

               return $basePath . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $subPath);
            }
        }
        return null;
    }

    private function scanClass(string $className, string $uri): bool
    {
        $reflection = new \ReflectionClass($className);
        $prefix = '';
        $classAttributes = $reflection->getAttributes(Route::class);

        if (!empty($classAttributes)) {
            $prefix = $classAttributes[0]->newInstance()->path;
        }

        foreach ($reflection->getMethods() as $method) {
            $attributes = $method->getAttributes(Route::class);

            foreach ($attributes as $attribute) {
                $route = $attribute->newInstance();

                $fullPath = rtrim($prefix, '/') . '/' . ltrim($route->path, '/');
                if ($fullPath !== '/') {
                    $fullPath = '/' . ltrim($fullPath, '/');
                }

                if ($fullPath === $uri) {
                    $controller = new $className();
                    $action = $method->getName();
                    $controller->$action();
                    return true;
                }
            }
        }
        return false;
    }
}