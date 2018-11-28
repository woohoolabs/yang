<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\JsonApi\Schema\Link;

final class ErrorLinks extends AbstractLinks
{

    /**
     * @param Link[] $links
     * @param Link[] $types
     */
    public function __construct(array $links, array $types)
    {
        parent::__construct($links);
    }

    public function hasAbout(): bool
    {
        return $this->hasLink("about");
    }

    public function about(): Link
    {
        return $this->link("about");
    }


    /**
     * @internal
     */
    public static function fromArray(array $links): ErrorLinks
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

        foreach ($this->types as $link) {
            $links["type"][] = $link->toArray();
        }

        return $links;
    }
}
