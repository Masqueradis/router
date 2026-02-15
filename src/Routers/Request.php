<?php

declare(strict_types=1);

namespace Masqueradis\Router\Routers;

class Request
{
    public function __construct(
        public readonly array $queryParams = [],
        public readonly array $body = [],
        public readonly array $server = [],
    ) {}

    public static function fromGlobals(): self
    {
        $body = self::getPostData();
        $contentType = self::getServerData('CONTENT_TYPE') ?? '';

        return new self($body);
    }

    public static function getPostData(): array
    {
        return $_POST;
    }

    public static function getServerData(string $key): ?string
    {
        return $_SERVER[$key] ?? null;
    }

    public function input(string $key, mixed $default = null): mixed
    {
        if (isset($this->body[$key])) {
            return $this->body[$key];
        }

        if (isset($this->queryParams[$key])) {
            return $this->queryParams[$key];
        }

        return $default;
    }
}
