<?php

declare(strict_types=1);

namespace Masqueradis\Tests;

use Composer\Autoload\ClassLoader;
use PHPUnit\Framework\TestCase;
use Masqueradis\Routers\Router;
use Masqueradis\Attributes\Route;

class RouterTest extends TestCase
{
    private $loaderMuck;
    private $router;

    protected function setUp(): void
    {
        $this->loaderMuck = $this->createMock(ClassLoader::class);
        $this->router = new Router($this->loaderMuck);

    }

    public function testRouter(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = [
            'id' => 1,
            'title' => 'title'
        ];

        $fakeNamespace = 'Masqueradis\\Tests\\FakeControllers\\';

        $this->loaderMuck->method('getPrefixesPsr4')->willReturn([
            'Masqueradis\\Tests\\' => [__DIR__]
        ]);

        $this->expectOutputString('Admin with id: 1 and title: title.');
        $this->router->dispatch($fakeNamespace, '/admin/dashboard');
    }
}
