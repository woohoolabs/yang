<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\JsonApi\Hydrator;

use stdClass;
use WoohooLabs\Yang\JsonApi\Exception\DocumentException;
use WoohooLabs\Yang\JsonApi\Schema\Document;
use WoohooLabs\Yang\JsonApi\Schema\Resource\ResourceObject;

final class ClassDocumentHydrator implements DocumentHydratorInterface
{
    /**
     * @return stdClass[]
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
     * @throws DocumentException when the document is empty or it has a collection as primary data.
     */
    public function hydrateSingleResource(Document $document): stdClass
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
     * @return stdClass[]
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
