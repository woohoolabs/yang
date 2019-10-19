<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\JsonApi\Hydrator;

use stdClass;
use WoohooLabs\Yang\JsonApi\Schema\Document;
use WoohooLabs\Yang\JsonApi\Schema\Relationship;
use WoohooLabs\Yang\JsonApi\Schema\Resource\ResourceObject;

class ClassDocumentHydrator extends AbstractClassDocumentHydrator
{
    /**
     * @return stdClass[]
     */
    public function hydrate(Document $document): iterable
    {
        return parent::hydrate($document);
    }

    /**
     * @return stdClass[]
     */
    public function hydrateCollection(Document $document): iterable
    {
        return parent::hydrateCollection($document);
    }

    public function hydrateSingleResource(Document $document): object
    {
        return parent::hydrateSingleResource($document);
    }

    protected function createObject(ResourceObject $resource): object
    {
        return new stdClass();
    }

    protected function hydrateResourceAttributes(object $result, ResourceObject $resource): object
    {
        $result->type = $resource->type();
        $result->id = $resource->id();
        foreach ($resource->attributes() as $attribute => $value) {
            $result->{$attribute} = $value;
        }

        return $result;
    }

    protected function hydrateResourceRelationship(object $result, Relationship $relationship, string $name, object $object): object
    {
        if ($relationship->isToOneRelationship()) {
            $result->{$name} = $object;
        } else {
            $result->{$name}[] = $object;
        }

        return $result;
    }
}
