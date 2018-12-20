<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\JsonApi\Schema\Link;

use WoohooLabs\Yang\JsonApi\Exception\DocumentException;
use function array_values;
use function is_array;
use function is_string;

final class DocumentLinks extends AbstractLinks
{
    /**
     * @var ProfileLink[]
     */
    private $profiles;

    /**
     * @param Link[] $links
     * @param ProfileLink[] $profiles
     */
    public function __construct(array $links, array $profiles)
    {
        parent::__construct($links);
        $this->profiles = $profiles;
    }

    public function hasSelf(): bool
    {
        return $this->hasLink("self");
    }

    /**
     * @throws DocumentException
     */
    public function self(): Link
    {
        return $this->link("self");
    }

    public function hasRelated(): bool
    {
        return $this->hasLink("related");
    }

    /**
     * @throws DocumentException
     */
    public function related(): Link
    {
        return $this->link("related");
    }

    public function hasFirst(): bool
    {
        return $this->hasLink("first");
    }

    /**
     * @throws DocumentException
     */
    public function first(): Link
    {
        return $this->link("first");
    }

    public function hasLast(): bool
    {
        return $this->hasLink("last");
    }

    /**
     * @throws DocumentException
     */
    public function last(): Link
    {
        return $this->link("last");
    }

    public function hasPrev(): bool
    {
        return $this->hasLink("prev");
    }

    /**
     * @throws DocumentException
     */
    public function prev(): Link
    {
        return $this->link("prev");
    }

    public function hasNext(): bool
    {
        return $this->hasLink("next");
    }

    /**
     * @throws DocumentException
     */
    public function next(): Link
    {
        return $this->link("next");
    }

    public function hasAnyProfiles(): bool
    {
        return empty($this->profiles) === false;
    }

    /**
     * @return ProfileLink[]
     */
    public function profiles(): array
    {
        return array_values($this->profiles);
    }

    public function hasProfile(string $href): bool
    {
        return isset($this->profiles[$href]);
    }

    /**
     * @throws DocumentException
     */
    public function profile(string $href): ProfileLink
    {
        if (isset($this->profiles[$href]) === false) {
            throw new DocumentException("There is no profile link with the '$href' URI!");
        }

        return $this->profiles[$href];
    }

    /**
     * @internal
     */
    public static function fromArray(array $links): DocumentLinks
    {
        $linkObjects = [];
        $profiles = [];

        foreach ($links as $name => $link) {
            if ($name === "profile") {
                $profiles = is_array($link) ? self::createProfiles($link) : [];
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

        return new self($linkObjects, $profiles);
    }

    public function toArray(): array
    {
        $links = parent::toArray();

        foreach ($this->profiles as $rel => $link) {
            $links["profile"][$rel] = $link->toArray();
        }

        return $links;
    }

    /**
     * @return ProfileLink[]
     */
    private static function createProfiles(array $profiles): array
    {
        $profileLinks = [];

        foreach ($profiles as $link) {
            if (is_string($link)) {
                $profileLinks[$link] = ProfileLink::fromString($link);
                continue;
            }

            if (is_array($link)) {
                $profileLink = ProfileLink::fromArray($link);
                $profileLinks[$profileLink->href()] = $profileLink;
                continue;
            }
        }

        return $profileLinks;
    }
}
