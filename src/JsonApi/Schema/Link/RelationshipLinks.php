<?php

declare(strict_types=1);

namespace WoohooLabs\Yang\JsonApi\Schema\Link;

use WoohooLabs\Yang\JsonApi\Exception\DocumentException;

use function is_array;
use function is_string;

final class RelationshipLinks extends AbstractLinks
{
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

    public static function fromArray(array $links): RelationshipLinks
    {
        $linkObjects = [];
        foreach ($links as $name => $link) {
            if (is_string($link)) {
                $linkObjects[$name] = Link::fromString($link);
            } elseif (is_array($link)) {
                $linkObjects[$name] = Link::fromArray($link);
            }
        }

        return new self($linkObjects);
    }
}
