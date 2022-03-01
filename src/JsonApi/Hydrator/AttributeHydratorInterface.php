<?php

declare(strict_types=1);

namespace BahaaAlhagar\Yang\JsonApi\Hydrator;

use BahaaAlhagar\Yang\JsonApi\Schema\Resource\ResourceObject;

interface AttributeHydratorInterface
{
    public function hydrateResourceAttributes(object $result, ResourceObject $resource): object;
}
