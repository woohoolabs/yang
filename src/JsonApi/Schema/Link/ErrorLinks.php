<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\JsonApi\Schema\Link;

use WoohooLabs\Yang\JsonApi\Exception\DocumentException;
use function array_values;
use function is_array;
use function is_string;

final class ErrorLinks extends AbstractLinks
{
    /**
     * @var Link[]
     */
    private $types;

    /**
     * @param Link[] $links
     * @param Link[] $types
     */
    public function __construct(array $links, array $types)
    {
        parent::__construct($links);
        $this->types = $types;
    }

    public function hasAbout(): bool
    {
        return $this->hasLink("about");
    }

    /**
     * @throws DocumentException
     */
    public function about(): Link
    {
        return $this->link("about");
    }

    public function hasType(string $href): bool
    {
        return isset($this->types[$href]);
    }

    public function hasAnyTypes(): bool
    {
        return empty($this->types) === false;
    }

    /**
     * @return Link[]
     */
    public function types(): array
    {
        return array_values($this->types);
    }

    /**
     * @throws DocumentException
     */
    public function type(string $href): Link
    {
        if (isset($this->types[$href]) === false) {
            throw new DocumentException("There is no type link with the '$href' URI!");
        }

        return $this->types[$href];
    }

    /**
     * @internal
     */
    public static function fromArray(array $links): ErrorLinks
    {
        $linkObjects = [];
        $types = [];

        foreach ($links as $name => $link) {
            if ($name === "type") {
                $types = is_array($link) ? self::createTypes($link) : [];
                continue;
            }

            if (is_string($link)) {
                $linkObjects[$name] = Link::fromString($link);
                continue;
            }

            if (is_array($link)) {
                $linkObjects[$name] = Link::fromArray($link);
                continue;
            }
        }

        return new self($linkObjects, $types);
    }

    public function toArray(): array
    {
        $links = parent::toArray();

        foreach ($this->types as $link) {
            $links["type"][] = $link->toArray();
        }

        return $links;
    }

    private static function createTypes(array $types): array
    {
        $typeLinks = [];

        foreach ($types as $link) {
            if (is_string($link)) {
                $typeLinks[$link] = Link::fromString($link);
                continue;
            }

            if (is_array($link)) {
                $profileLink = Link::fromArray($link);
                $typeLinks[$profileLink->href()] = $profileLink;
                continue;
            }
        }

        return $typeLinks;
    }
}
