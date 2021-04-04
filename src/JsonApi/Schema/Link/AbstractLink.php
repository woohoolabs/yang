<?php

declare(strict_types=1);

namespace WoohooLabs\Yang\JsonApi\Schema\Link;

abstract class AbstractLink
{
    private string $href;
    private array $meta;

    public function __construct(string $href, array $meta = [])
    {
        $this->href = $href;
        $this->meta = $meta;
    }

    public function href(): string
    {
        return $this->href;
    }

    public function hasMeta(): bool
    {
        return empty($this->meta) === false;
    }

    public function meta(): array
    {
        return $this->meta;
    }

    public function toArray(): array
    {
        $link = ["href" => $this->href];

        if (empty($this->meta) === false) {
            $link["meta"] = $this->meta;
        }

        return $link;
    }
}
