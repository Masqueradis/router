<?php

declare(strict_types=1);

namespace Masqueradis\Router\Routers;

use Composer\Autoload\ClassLoader;
use Masqueradis\Router\Attributes\Route;

class Router
{
    private ClassLoader $composerLoader;

    public function __construct(ClassLoader $loader)
    {
        $this->composerLoader = $loader;
    }

    public static function getServerData(string $key): ?string
    {
        return $_SERVER[$key] ?? null;
    }

    public function dispatch(string $targetNamespace, string $uri): void
    {
        $requestMethod = self::getServerData('REQUEST_METHOD');
        $dirPath = $this->getDirFromNamespace($targetNamespace);

        if (!$dirPath || !is_dir($dirPath)) {
            echo 'No directory for Controller: '.$targetNamespace.PHP_EOL;

            return;
        }

        $files = glob($dirPath.'/*.php') ?: [];

        foreach ($files as $file) {
            $className = basename($file, '.php');

            $fullClassName = rtrim($targetNamespace, '\\').'\\'.$className;

            if (class_exists($fullClassName)) {
                if ($this->scanClass($fullClassName, $uri)) {
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

            if (0 === strpos($namespace, $prefixClean)) {
                $basePathRaw = $paths[0];
                $basePath = realpath($basePathRaw);

                $subPath = substr($namespace, strlen($prefixClean));
                $subPath = trim($subPath, '\\');

                return $basePath.DIRECTORY_SEPARATOR.str_replace('\\', DIRECTORY_SEPARATOR, $subPath);
            }
        }

        return null;
    }

    private function scanClass(string $className, string $uri): bool
    {
        $reflection = new \ReflectionClass($className);
        $prefix = '';
        $classAttributes = $reflection->getAttributes(Route::class);

        foreach ($reflection->getMethods() as $method) {
            $attributes = $method->getAttributes(Route::class);

            foreach ($attributes as $attribute) {
                $route = $attribute->newInstance();

                $fullPath = rtrim($prefix, '/').'/'.ltrim($route->path, '/');
                if ('/' !== $fullPath) {
                    $fullPath = '/'.ltrim($fullPath, '/');
                }

                if ($fullPath === $uri) {
                    $controller = new $className();
                    $args = $this->resolveParameters($method);
                    $method->invokeArgs($controller, $args);

                    return true;
                }
            }
        }

        return false;
    }

    private function resolveParameters(\ReflectionMethod $method): array
    {
        $args = [];
        $request = Request::fromGlobals();

        foreach ($method->getParameters() as $parameter) {
            $type = $parameter->getType();

            if (!$type || $type->isBuiltin()) { // @phpstan-ignore-line
                $args[] = null;

                continue;
            }

            $typeName = $type->getName(); // @phpstan-ignore-line

            if (Request::class === $typeName) {
                $args[] = $request;

                continue;
            }
        }

        return $args;
    }
}
