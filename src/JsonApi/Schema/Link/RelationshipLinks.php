<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\JsonApi\Schema\Link;

use WoohooLabs\Yang\JsonApi\Exception\DocumentException;

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
}
