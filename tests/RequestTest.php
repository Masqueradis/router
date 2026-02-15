<?php

declare(strict_types=1);

namespace Masqueradis\RouterTests;

use Masqueradis\Router\Routers\Request;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class RequestTest extends TestCase
{
    public function testInputReturnsFromBody(): void
    {
        $fakeBody = [
            'status' => 'active',
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
