<?php

declare(strict_types=1);

namespace WoohooLabs\Yang\JsonApi\Hydrator;

use WoohooLabs\Yang\JsonApi\Schema\Document;
use WoohooLabs\Yang\JsonApi\Schema\Relationship;
use WoohooLabs\Yang\JsonApi\Exception\DocumentException;
use WoohooLabs\Yang\JsonApi\Schema\Resource\ResourceObject;
use WoohooLabs\Yang\JsonApi\Schema\Resource\ResourceObjects;

abstract class AbstractClassDocumentHydrator implements DocumentHydratorInterface
{
    abstract protected function createObject(ResourceObject $resource): object;

    abstract protected function hydrateResourceAttributes(object $result, ResourceObject $resource): object;

    abstract protected function hydrateResourceRelationship(object $result, Relationship $relationship, string $name, object $object): object;

    /**
     * @return iterable<object>
     */
    public function hydrate(Document $document): iterable
    {
        if ($document->hasAnyPrimaryResources() === false) {
            return [];
        }

        if ($document->isSingleResourceDocument()) {
            return [$this->hydratePrimaryResource($document)];
        }

        return $this->hydratePrimaryResources($document);
    }

    /**
     * @return iterable<object>
     * @throws DocumentException when the document has a single resource as primary data.
     */
    public function hydrateCollection(Document $document): iterable
    {
        if ($document->isSingleResourceDocument()) {
            throw new DocumentException(
                "The document is a single-resource document, therefore it doesn't have multiple resources. " .
                "Use the 'ClassDocumentHydrator::hydrateSingleResource()' method instead."
            );
        }

        if ($document->hasAnyPrimaryResources() === false) {
            return [];
        }

        return $this->hydratePrimaryResources($document);
    }

    public function hydrateSingleResource(Document $document): object
    {
        if ($document->isSingleResourceDocument() === false) {
            throw new DocumentException(
                "The document is a collection document, therefore it doesn't have a single resource. " .
                "Use the 'ClassDocumentHydrator::hydrateCollection()' method instead."
            );
        }

        if ($document->hasAnyPrimaryResources() === false) {
            throw new DocumentException(
                "The document doesn't have any primary data."
            );
        }

        return $this->hydratePrimaryResource($document);
    }

    /**
     * @return object[]
     */
    protected function hydratePrimaryResources(Document $document): array
    {
        $result = [];
        $resourceMap = [];

        foreach ($document->primaryResources() as $primaryResource) {
            $result[] = $this->hydrateResource($primaryResource, $document, $resourceMap);
        }

        return $result;
    }

    protected function hydratePrimaryResource(Document $document): object
    {
        $resourceMap = [];

        return $this->hydrateResource($document->primaryResource(), $document, $resourceMap);
    }

    /**
     * @param object[] $resourceMap
     */
    protected function hydrateResource(ResourceObject $resource, Document $document, array &$resourceMap): object
    {
        $result = $this->createObject($resource);
        $result = $this->hydrateResourceAttributes($result, $resource);

        //Save resource to the identity map
        $this->saveObjectToMap($resource->type(), $resource->id(), $result, $resourceMap);

        //Fill relationships
        foreach ($resource->relationships() as $name => $relationship) {
            foreach ($relationship->resourceLinks() as $link) {
                $object = $this->getObjectFromMap($link["type"], $link["id"], $resourceMap);

                if ($object === null && $document->hasIncludedResource($link["type"], $link["id"])) {
                    $relatedResource = $document->resource($link["type"], $link["id"]);
                    $object = $this->hydrateResource($relatedResource, $document, $resourceMap);
                }

                if ($object === null) {
                    if (isset($link["type"], $link["id"])) {
                        $relatedResource = ResourceObject::fromArray($link, new ResourceObjects([], [], $relationship->isToOneRelationship()));

                        $object = $this->hydrateResource($relatedResource, $document, $resourceMap);
                    }
                }

                $result = $this->hydrateResourceRelationship($result, $relationship, $name, $object);
            }
        }

        return $result;
    }

    /**
     * @param object[] $resourceMap
     */
    private function getObjectFromMap(string $type, string $id, array $resourceMap): ?object
    {
        return $resourceMap[$type . "-" . $id] ?? null;
    }

    /**
     * @param object[] $resourceMap
     */
    private function saveObjectToMap(string $type, string $id, object $object, array &$resourceMap): void
    {
        $resourceMap[$type . "-" . $id] = $object;
    }
}
