<?php

declare(strict_types=1);

namespace WoohooLabs\Yang\JsonApi\Schema\Link;

use function is_array;
use function is_string;

final class ProfileLink extends AbstractLink
{
    /** @var array<string, string> */
    private array $aliases;

    /**
     * @return array<int|string, mixed> $meta
     * @return array<string, string> $aliases
     */
    public function __construct(string $href, array $meta = [], array $aliases = [])
    {
        parent::__construct($href, $meta);
        $this->aliases = $aliases;
    }

    public function aliases(): array
    {
        return $this->aliases;
    }

    /**
     * @internal
     */
    public static function fromString(string $link): ProfileLink
    {
        return new ProfileLink($link);
    }

    /**
     * @internal
     */
    public static function fromArray(array $link): ProfileLink
    {
        $href = isset($link["href"]) && is_string($link["href"]) ? $link["href"] : "";
        $meta = isset($link["meta"]) && is_array($link["meta"]) ? $link["meta"] : [];
        $aliases = isset($link["aliases"]) && is_array($link["aliases"]) ? $link["aliases"] : [];

        return new ProfileLink($href, $meta, $aliases);
    }

    public function toArray(): array
    {
        $link = parent::toArray();

        if (empty($this->aliases) === false) {
            $link["aliases"] = $this->aliases;
        }

        return $link;
    }
}
