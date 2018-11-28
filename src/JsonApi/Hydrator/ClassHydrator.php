<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\JsonApi\Hydrator;

use stdClass;
use WoohooLabs\Yang\JsonApi\Schema\Document;
use WoohooLabs\Yang\JsonApi\Schema\Resource\ResourceObject;

final class ClassHydrator implements HydratorInterface
{
    /**
     * @return array|stdClass
     */
    public function hydrate(Document $document)
    {
        if ($document->hasAnyPrimaryResources() === false) {
            return new stdClass();
        }

        if ($document->isSingleResourceDocument()) {
            return $this->hydratePrimaryResource($document);
        }

        return $this->hydratePrimaryResources($document);
    }

    public function hydrateObject(Document $document): stdClass
    {
        if ($document->isSingleResourceDocument() === false) {
            return new stdClass();
        }

        if ($document->hasAnyPrimaryResources() === false) {
            return new stdClass();
        }

        return $this->hydratePrimaryResource($document);
    }

    public function hydrateCollection(Document $document): iterable
    {
        if ($document->hasAnyPrimaryResources() === false) {
            return [];
        }

        if ($document->isSingleResourceDocument()) {
            return [$this->hydratePrimaryResource($document)];
        }

        return $this->hydratePrimaryResources($document);
    }

    private function hydratePrimaryResources(Document $document): array
    {
        $result = [];
        $resourceMap = [];

        foreach ($document->primaryResources() as $primaryResource) {
            $result[] = $this->hydrateResource($primaryResource, $document, $resourceMap);
        }

        return $result;
    }

    private function hydratePrimaryResource(Document $document): stdClass
    {
        $resourceMap = [];

        return $this->hydrateResource($document->primaryResource(), $document, $resourceMap);
    }

    /**
     * @param stdClass[] $resourceMap
     */
    private function hydrateResource(ResourceObject $resource, Document $document, array &$resourceMap): stdClass
    {
        // Fill basic attributes of the resource
        $result = new stdClass();
        $result->type = $resource->type();
        $result->id = $resource->id();
        foreach ($resource->attributes() as $attribute => $value) {
            $result->{$attribute} = $value;
        }

        //Save resource to the identity map
        $this->saveObjectToMap($result, $resourceMap);

        //Fill relationships
        foreach ($resource->relationships() as $name => $relationship) {
            foreach ($relationship->resourceLinks() as $link) {
                $object = $this->getObjectFromMap($link["type"], $link["id"], $resourceMap);

                if ($object === null && $document->hasIncludedResource($link["type"], $link["id"])) {
                    $relatedResource = $document->resource($link["type"], $link["id"]);
                    $object = $this->hydrateResource($relatedResource, $document, $resourceMap);
                }

                if ($object === null) {
                    continue;
                }

                if ($relationship->isToOneRelationship()) {
                    $result->{$name} = $object;
                } else {
                    $result->{$name}[] = $object;
                }
            }
        }

        return $result;
    }

    /**
     * @param stdClass[] $resourceMap
     */
    private function getObjectFromMap(string $type, string $id, array $resourceMap): ?stdClass
    {
        return $resourceMap[$type . "-" . $id] ?? null;
    }

    /**
     * @param stdClass[] $resourceMap
     */
    private function saveObjectToMap(stdClass $object, array &$resourceMap): void
    {
        $resourceMap[$object->type . "-" . $object->id] = $object;
    }
}
