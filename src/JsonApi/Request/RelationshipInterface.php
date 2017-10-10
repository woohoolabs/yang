<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\JsonApi\Request;

interface RelationshipInterface
{
    public function toArray(): array;
}
