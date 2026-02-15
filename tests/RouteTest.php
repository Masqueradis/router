<?php

declare(strict_types=1);

namespace Masqueradis\RouterTests;

use Masqueradis\Router\Attributes\Route;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class RouteTest extends TestCase
{
    public function testAttributeStores(): void
    {
        $router = new Route('/home', 'POST');

        $this->assertEquals('/home', $router->path);
        $this->assertEquals('POST', $router->method);
    }
}
