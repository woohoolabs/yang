<?php

declare(strict_types=1);

namespace WoohooLabs\Yang\JsonApi\Hydrator;

use WoohooLabs\Yang\JsonApi\Schema\Resource\ResourceObject;

interface AttributeHydratorInterface
{
    public function hydrateResourceAttributes(object $result, ResourceObject $resource): object;
}
