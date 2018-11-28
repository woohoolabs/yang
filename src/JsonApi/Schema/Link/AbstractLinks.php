<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\JsonApi\Schema\Link;

use WoohooLabs\Yang\JsonApi\Exception\LinkException;

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
     * @throws LinkException
     */
    public function link(string $name)
    {
        if (isset($this->links[$name]) === false) {
            throw new LinkException("Link with '$name rel type cannot be found!");
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
     * @internal
     * @return $this
     */
    public static function fromArray(array $links)
    {
        $linkObjects = [];
        foreach ($links as $name => $value) {
            if (is_string($value)) {
                $linkObjects[$name] = Link::createFromString($value);
            } elseif (is_array($value)) {
                $linkObjects[$name] = Link::fromArray($value);
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
