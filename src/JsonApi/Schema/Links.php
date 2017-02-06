<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\JsonApi\Schema;

class Links
{
    /**
     * @var Link[]
     */
    private $links;

    public static function createFromArray(array $links): Links
    {
        $linkObjects = [];
        foreach ($links as $name => $value) {
            if (is_string($value)) {
                $linkObjects[$name] = Link::createFromString($value);
            } elseif (is_array($value)) {
                $linkObjects[$name] = Link::createFromArray($value);
            }
        }

        return new self($linkObjects);
    }

    /**
     * @param Link[] $links
     */
    public function __construct(array $links)
    {
        $this->links = $links;
    }

    public function toArray(): array
    {
        $links = [];

        foreach ($this->links as $rel => $link) {
            $links[$rel] = $link->toArray();
        }

        return $links;
    }

    public function hasSelf(): bool
    {
        return $this->hasLink("self");
    }

    /**
     * @return Link|null
     */
    public function self()
    {
        return $this->link("self");
    }

    public function hasRelated(): bool
    {
        return $this->hasLink("related");
    }

    /**
     * @return Link|null
     */
    public function related()
    {
        return $this->link("related");
    }

    public function hasFirst(): bool
    {
        return $this->hasLink("first");
    }

    /**
     * @return Link|null
     */
    public function first()
    {
        return $this->link("first");
    }

    public function hasLast(): bool
    {
        return $this->hasLink("last");
    }

    /**
     * @return Link|null
     */
    public function last()
    {
        return $this->link("last");
    }

    public function hasPrev(): bool
    {
        return $this->hasLink("prev");
    }

    /**
     * @return Link|null
     */
    public function prev()
    {
        return $this->link("prev");
    }

    public function hasNext(): bool
    {
        return $this->hasLink("next");
    }

    /**
     * @return Link|null
     */
    public function next()
    {
        return $this->link("next");
    }

    public function hasAbout(): bool
    {
        return $this->hasLink("about");
    }

    /**
     * @return Link|null
     */
    public function about()
    {
        return $this->link("about");
    }

    public function hasLink(string $name): bool
    {
        return isset($this->links[$name]);
    }

    /**
     * @return Link|null
     */
    public function link(string $name)
    {
        return $this->links[$name] ?? null;
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
}
