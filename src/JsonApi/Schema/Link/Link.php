<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\JsonApi\Schema\Link;

use function is_array;
use function is_string;

class Link
{
    /**
     * @var string
     */
    private $href;

    /**
     * @var array
     */
    private $meta;

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

    /**
     * @internal
     * @return static
     */
    public static function fromString(string $link)
    {
        return new static($link);
    }

    /**
     * @internal
     * @return static
     */
    public static function fromArray(array $link)
    {
        $href = isset($link["href"]) && is_string($link["href"]) ? $link["href"] : "";
        $meta = isset($link["meta"]) && is_array($link["meta"]) ? $link["meta"] : [];

        return new static($href, $meta);
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
