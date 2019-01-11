<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\JsonApi\Schema\Resource;

use WoohooLabs\Yang\JsonApi\Exception\DocumentException;
use function array_values;
use function key;
use function reset;

final class ResourceObjects
{
    /**
     * @var bool
     */
    private $isSinglePrimaryResource;

    /**
     * @var ResourceObject[]
     */
    private $resources = [];

    /**
     * @var ResourceObject[]
     */
    private $primaryKeys = [];

    /**
     * @var ResourceObject[]
     */
    private $includedKeys = [];

    public function __construct(array $data, array $included, bool $isSinglePrimaryResource)
    {
        $this->isSinglePrimaryResource = $isSinglePrimaryResource;

        if ($this->isSinglePrimaryResource) {
            if (empty($data) === false) {
                $this->addPrimaryResource(ResourceObject::fromArray($data, $this));
            }
        } else {
            foreach ($data as $resource) {
                $this->addPrimaryResource(ResourceObject::fromArray($resource, $this));
            }
        }

        foreach ($included as $resource) {
            $this->addIncludedResource(ResourceObject::fromArray($resource, $this));
        }
    }

    public function isSinglePrimaryResource(): bool
    {
        return $this->isSinglePrimaryResource === true;
    }

    public function isPrimaryResourceCollection(): bool
    {
        return $this->isSinglePrimaryResource === false;
    }

    public function hasResource(string $type, string $id): bool
    {
        return isset($this->resources["$type.$id"]);
    }

    /**
     * @throws DocumentException
     */
    public function resource(string $type, string $id): ResourceObject
    {
        if (isset($this->resources["$type.$id"]) === false) {
            throw new DocumentException("The document doesn't contain any resource with the '$type' type and '$id' ID!");
        }

        return $this->resources["$type.$id"];
    }

    public function hasAnyPrimaryResources(): bool
    {
        return empty($this->primaryKeys) === false;
    }

    public function hasPrimaryResource(string $type, string $id): bool
    {
        return isset($this->primaryKeys["$type.$id"]);
    }

    /**
     * @return ResourceObject[]
     */
    public function primaryResources(): array
    {
        if ($this->isSinglePrimaryResource) {
            throw new DocumentException(
                "The document is a single-resource or error document, therefore it doesn't have multiple resources. " .
                "Use the 'Document::primaryResource()' method instead."
            );
        }

        return array_values($this->primaryKeys);
    }

    /**
     * @throws DocumentException
     */
    public function primaryResource(): ResourceObject
    {
        if ($this->isSinglePrimaryResource === false) {
            throw new DocumentException(
                "The document is a collection or error document, therefore it doesn't have a single resource. " .
                "Use the 'Document::primaryResources()' method instead."
            );
        }

        if ($this->hasAnyPrimaryResources() === false) {
            throw new DocumentException("The document doesn't contain any primary resources!");
        }

        reset($this->primaryKeys);
        $key = key($this->primaryKeys);

        return $this->resources[$key];
    }

    public function hasAnyIncludedResources(): bool
    {
        return empty($this->includedKeys) === false;
    }

    public function hasIncludedResource(string $type, string $id): bool
    {
        return isset($this->includedKeys["$type.$id"]);
    }

    /**
     * @return ResourceObject[]
     */
    public function includedResources(): array
    {
        return array_values($this->includedKeys);
    }

    /**
     * @internal
     */
    public static function fromSinglePrimaryData(array $data, array $included): ResourceObjects
    {
        return new self($data, $included, true);
    }

    /**
     * @internal
     */
    public static function fromCollectionPrimaryData(array $data, array $included): ResourceObjects
    {
        return new self($data, $included, false);
    }

    public function primaryDataToArray(): ?array
    {
        return $this->isSinglePrimaryResource ? $this->primaryResourceToArray() : $this->primaryCollectionToArray();
    }

    public function includedToArray(): array
    {
        $result = [];
        foreach ($this->includedKeys as $resource) {
            $result[] = $resource->toArray();
        }

        return $result;
    }

    private function primaryResourceToArray(): ?array
    {
        if ($this->hasAnyPrimaryResources() === false) {
            return null;
        }

        reset($this->primaryKeys);
        $key = key($this->primaryKeys);

        return $this->resources[$key]->toArray();
    }

    private function primaryCollectionToArray(): array
    {
        $result = [];
        foreach ($this->primaryKeys as $resource) {
            $result[] = $resource->toArray();
        }

        return $result;
    }

    private function addPrimaryResource(ResourceObject $resource): ResourceObjects
    {
        $this->addResource($this->primaryKeys, $resource);

        return $this;
    }

    private function addIncludedResource(ResourceObject $resource): ResourceObjects
    {
        if ($this->hasPrimaryResource($resource->type(), $resource->id()) === false) {
            $this->addResource($this->includedKeys, $resource);
        }

        return $this;
    }

    private function addResource(array &$keys, ResourceObject $resource): void
    {
        $type = $resource->type();
        $id = $resource->id();
        $index = "$type.$id";

        $this->resources[$index] = $resource;
        $keys[$index] = &$this->resources[$index];
    }
}
