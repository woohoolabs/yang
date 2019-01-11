<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\JsonApi\Schema\Link;

use WoohooLabs\Yang\JsonApi\Exception\DocumentException;
use function is_array;
use function is_string;

abstract class AbstractLinks
{
    /**
     * @var Link[]
     */
    protected $links;

    /**
     * @param Link[] $links
     */
    public function __construct(array $links)
    {
        $this->links = $links;
    }

    public function hasLink(string $name): bool
    {
        return isset($this->links[$name]);
    }

    /**
     * @return Link
     * @throws DocumentException
     */
    public function link(string $name)
    {
        if (isset($this->links[$name]) === false) {
            throw new DocumentException("Link with '$name rel type cannot be found!");
        }

        return $this->links[$name];
    }

    public function hasAnyLinks(): bool
    {
        return empty($this->links) === false;
    }

    /**
     * @return Link[]
     */
    public function links(): array
    {
        return $this->links;
    }

    /**
     * @return static
     */
    public static function fromArray(array $links)
    {
        $linkObjects = [];
        foreach ($links as $name => $link) {
            if (is_string($link)) {
                $linkObjects[$name] = Link::fromString($link);
            } elseif (is_array($link)) {
                $linkObjects[$name] = Link::fromArray($link);
            }
        }

        return new static($linkObjects);
    }

    public function toArray(): array
    {
        $links = [];

        foreach ($this->links as $rel => $link) {
            $links[$rel] = $link->toArray();
        }

        return $links;
    }
}
