<?php

declare(strict_types=1);

namespace Masqueradis\RouterTests;

use Composer\Autoload\ClassLoader;
use Masqueradis\Router\Routers\Router;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class RouterTest extends TestCase
{
    private $loaderMuck;
    private $router;

    protected function setUp(): void
    {
        $this->loaderMuck = $this->createMock(ClassLoader::class);
        $this->router = new Router($this->loaderMuck);
    }

    public function testRouterPostMethod(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = [
            'id' => 1,
            'title' => 'title',
        ];

        $fakeNamespace = 'Masqueradis\RouterTests\Fake\Controllers\\';

        $this->loaderMuck->method('getPrefixesPsr4')->willReturn([
            'Masqueradis\RouterTests\\' => [__DIR__],
        ]);

        $this->expectOutputString('Admin with id: 1 and title: title.');
        $this->router->dispatch($fakeNamespace, '/dashboard');
    }

    public function testRouterNoController(): void
    {
        $fakeNamespace = 'Masqueradis\RouterTests\Fake\Controllers\\';

        $this->expectOutputString('No directory for Controller: Masqueradis\RouterTests\Fake\Controllers\\'.PHP_EOL);
        $this->router->dispatch($fakeNamespace, '/admin');
    }

    public function testScanClassNegativeCase(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $fakeNamespace = 'Masqueradis\RouterTests\Fake\Controllers\\';

        $this->loaderMuck->method('getPrefixesPsr4')->willReturn([
            'Masqueradis\RouterTests\Fake\Controllers\\' => [__DIR__],
        ]);

        $this->expectOutputString('');
        $this->router->dispatch($fakeNamespace, '/fake/path');
    }

    public function testResolveBuiltInParameters(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $fakeNamespace = 'Masqueradis\RouterTests\Fake\Controllers\\';

        $this->loaderMuck->method('getPrefixesPsr4')->willReturn([
            'Masqueradis\RouterTests\\' => [__DIR__],
        ]);

        $this->expectOutputString('Success');
        $this->router->dispatch($fakeNamespace, '/paramtest');
    }
}
