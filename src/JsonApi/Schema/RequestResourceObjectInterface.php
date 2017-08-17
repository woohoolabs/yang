<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\JsonApi\Schema;

use WoohooLabs\Yang\JsonApi\Request\RelationshipInterface;
use WoohooLabs\Yang\JsonApi\Request\ToManyRelationship;
use WoohooLabs\Yang\JsonApi\Request\ToOneRelationship;

interface RequestResourceObjectInterface
{
    public function setAttributes(array $attributes): RequestResourceObjectInterface;

    /**
     * @param mixed $value
     */
    public function setAttribute(string $name, $value): RequestResourceObjectInterface;

    public function setToOneRelationship(string $name, ToOneRelationship $relationship): RequestResourceObjectInterface;

    public function setToManyRelationship(string $name, ToManyRelationship $relationship): RequestResourceObjectInterface;

    public function setRelationship(string $name, RelationshipInterface $relationship): RequestResourceObjectInterface;

    public function toArray(): array;
}
