<?php

declare(strict_types=1);

namespace BahaaAlhagar\Yang\JsonApi\Request;

interface RelationshipInterface
{
    public function toArray(): array;
}
