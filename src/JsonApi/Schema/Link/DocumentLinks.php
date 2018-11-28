<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\JsonApi\Schema\Link;

final class DocumentLinks extends AbstractLinks
{
    /**
     * @var Link[]
     */
    private $links;

    /**
     * @param Link[] $links
     */
    {
        parent::__construct($links);
    }

    public function hasSelf(): bool
    {
        return $this->hasLink("self");
    }

    public function self(): Link
    {
        return $this->link("self");
    }

    public function hasRelated(): bool
    {
        return $this->hasLink("related");
    }

    public function related(): Link
    {
        return $this->link("related");
    }

    public function hasFirst(): bool
    {
        return $this->hasLink("first");
    }

    public function first(): Link
    {
        return $this->link("first");
    }

    public function hasLast(): bool
    {
        return $this->hasLink("last");
    }

    public function last(): Link
    {
        return $this->link("last");
    }

    public function hasPrev(): bool
    {
        return $this->hasLink("prev");
    }

    public function prev(): Link
    {
        return $this->link("prev");
    }

    public function hasNext(): bool
    {
        return $this->hasLink("next");
    }

    public function next(): Link
    {
        return $this->link("next");
    }

    /**
     * @internal
     */
    public static function fromArray(array $links): DocumentLinks
    {
        $linkObjects = [];
        foreach ($links as $name => $value) {
            if (is_string($value)) {
                $linkObjects[$name] = Link::createFromString($value);
            } elseif (is_array($value)) {
                $linkObjects[$name] = Link::fromArray($value);
            }
        }

        return new self($linkObjects, []);
    }

    public function toArray(): array
    {
        $links = parent::toArray();

        foreach ($this->profiles as $rel => $link) {
            $links["profile"][$rel] = $link->toArray();
        }

        return $links;
    }
}
