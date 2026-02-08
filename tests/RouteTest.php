<?php

declare(strict_types=1);

namespace Masqueradis\Tests;

use PHPUnit\Framework\TestCase;
use Masqueradis\Attributes\Route;

class RouteTest extends TestCase
{
    public function testAttributeStores(): void
    {
        $router = new Route('/home', 'POST');

        $this->assertEquals('/home', $router->path);
        $this->assertEquals('POST', $router->method);
    }
}
