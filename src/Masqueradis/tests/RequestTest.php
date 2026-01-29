<?php

declare(strict_types=1);

namespace Masqueradis\Tests;

use PHPUnit\Framework\TestCase;
use Masqueradis\Routers\Request;
use Masqueradis\Routers\Router;
use Masqueradis\Attributes\Route;

class RequestTest extends TestCase
{
    public function  testInputReturnsFromBody(): void
    {
        $fakeBody = [
            'status' => 'active'
        ];

        $request = new Request(body: $fakeBody);

        $this->assertEquals('active', $request->input('status'));
    }

    public function testInputReturnsDefaultValue(): void
    {
        $request = new Request(queryParams: [], body: []);

        $defaultValue = 'defaultValue';
        $result = $request->input('fakeKey', $defaultValue);
        $this->assertEquals($defaultValue, $result);
    }

}
