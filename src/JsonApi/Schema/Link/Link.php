<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\JsonApi\Schema\Link;

use function is_array;
use function is_string;

final class Link extends AbstractLink
{
    /**
     * @internal
     */
    public static function fromString(string $link): Link
    {
        return new Link($link);
    }

    /**
     * @internal
     */
    public static function fromArray(array $link): Link
    {
        $href = isset($link["href"]) && is_string($link["href"]) ? $link["href"] : "";
        $meta = isset($link["meta"]) && is_array($link["meta"]) ? $link["meta"] : [];

        return new Link($href, $meta);
    }
}
