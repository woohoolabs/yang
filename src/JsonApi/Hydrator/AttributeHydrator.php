<?php

declare(strict_types=1);

namespace WoohooLabs\Yang\JsonApi\Hydrator;

use WoohooLabs\Yang\JsonApi\Schema\Resource\ResourceObject;

class AttributeHydrator implements AttributeHydratorInterface
{
    public function hydrateResourceAttributes(object $result, ResourceObject $resource): object
    {
        $result->type = $resource->type();
        $result->id   = $resource->id();
        foreach ($resource->attributes() as $attribute => $value) {
            $result->{$attribute} = $value;
        }

        return $result;
    }
}
