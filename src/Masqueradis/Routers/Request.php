<?php

declare(strict_types=1);

namespace Masqueradis\Routers;

use ReflectionClass;

class Request
{
    public function __construct(
        public readonly array $queryParams = [],
        public readonly array $body = [],
        public readonly array $server = [],
    ) {}

    public static function fromGlobals(): self
    {
        $body = $_POST;
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

        if (str_contains($contentType, 'application/json')) {
            $input = file_get_contents('php://input');
            $decoded = json_decode($input, true);
            if(is_array($decoded)) {
                $body = array_merge($body, $decoded);
            }
        }
        return new self($body);
    }

    public function mapTo(string $className): object
    {
        $reflection = new ReflectionClass($className);
        $dto = $reflection->newInstance();
        $allData = array_merge($this->queryParams, $this->body);

        foreach ($reflection->getProperties() as $property) {
            $name = $property->getName();

            if(isset($allData[$name])) {
                $property->setValue($dto, $allData[$name]);
            }
        }
        if (method_exists($dto, 'validate')) {
            $dto->validate($allData);
        }
        return $dto;
    }

    public function input(string $key, mixed $default = null): mixed
    {
        if(isset($this->body[$key])) {
            return $this->body[$key];
        }

        if(isset($this->queryParams[$key])) {
            return $this->queryParams[$key];
        }

        return $default;
    }
}