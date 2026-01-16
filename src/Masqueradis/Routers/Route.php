<?php

declare(strict_types=1);

namespace Masqueradis\Routers;

use Attribute;

#[Attribute]
class Route
{
    public function __construct(
        public string $path,
        public string $method = 'GET'
    ) {}
}
