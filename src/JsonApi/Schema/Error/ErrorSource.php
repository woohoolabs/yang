<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\JsonApi\Schema\Error;

use function is_string;

final class ErrorSource
{
    /**
     * @var string
     */
    private $pointer;

    /**
     * @var string
     */
    private $parameter;

    public function __construct(string $pointer, string $parameter)
    {
        $this->pointer = $pointer;
        $this->parameter = $parameter;
    }

    public function hasSource(): bool
    {
        return $this->hasPointer() || $this->hasParameter();
    }

    public function hasPointer(): bool
    {
        return empty($this->pointer) === false;
    }

    public function pointer(): string
    {
        return $this->pointer;
    }

    public function hasParameter(): bool
    {
        return empty($this->parameter) === false;
    }

    public function parameter(): string
    {
        return $this->parameter;
    }

    /**
     * @internal
     */
    public static function fromArray(array $source): ErrorSource
    {
        $pointer = isset($source["pointer"]) && is_string($source["pointer"]) ? $source["pointer"] : "";
        $parameter = isset($source["parameter"]) && is_string($source["parameter"]) ? $source["parameter"] : "";

        return new self($pointer, $parameter);
    }

    public function toArray(): array
    {
        $content = [];

        if ($this->pointer !== "") {
            $content["pointer"] = $this->pointer;
        }

        if ($this->parameter !== "") {
            $content["parameter"] = $this->parameter;
        }

        return $content;
    }
}
