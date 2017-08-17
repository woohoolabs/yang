<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\JsonApi\Schema;

class ResourceObjects
{
    /*
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

    public static function createFromSinglePrimaryData(array $data, array $included): ResourceObjects
    {
        return new self($data, $included, true);
    }

    public static function createFromCollectionPrimaryData(array $data, array $included): ResourceObjects
    {
        return new self($data, $included, false);
    }

    public function __construct(array $data, array $included, bool $isSinglePrimaryResource)
    {
        $this->isSinglePrimaryResource = $isSinglePrimaryResource;

        if ($this->isSinglePrimaryResource === true) {
            if (empty($data) === false) {
                $this->addPrimaryResource(ResourceObject::createForResponse($data, $this));
            }
        } else {
            foreach ($data as $resource) {
                $this->addPrimaryResource(ResourceObject::createForResponse($resource, $this));
            }
        }

        foreach ($included as $resource) {
            $this->addIncludedResource(ResourceObject::createForResponse($resource, $this));
        }
    }

    /**
     * @return array|null
     */
    public function primaryDataToArray()
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

    /**
     * @return array|null
     */
    private function primaryResourceToArray()
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

    public function isSinglePrimaryResource(): bool
    {
        return $this->isSinglePrimaryResource === true;
    }

    public function isPrimaryResourceCollection(): bool
    {
        return $this->isSinglePrimaryResource === false;
    }

    /**
     * @return ResourceObject|null
     */
    public function resource(string $type, string $id)
    {
        return $this->resources[$type . "." . $id] ?? null;
    }

    public function hasAnyPrimaryResources(): bool
    {
        return empty($this->primaryKeys) === false;
    }

    /**
     * @return ResourceObject[]
     */
    public function primaryResources(): array
    {
        return array_values($this->primaryKeys);
    }

    /**
     * @return ResourceObject|null
     */
    public function primaryResource()
    {
        if ($this->hasAnyPrimaryResources() === false) {
            return null;
        }

        reset($this->primaryKeys);
        $key = key($this->primaryKeys);

        return $this->resources[$key];
    }

    public function hasPrimaryResource(string $type, string $id): bool
    {
        return isset($this->primaryKeys[$type . "." . $id]);
    }

    public function hasAnyIncludedResources(): bool
    {
        return empty($this->includedKeys) === false;
    }

    public function hasIncludedResource(string $type, string $id): bool
    {
        return isset($this->includedKeys[$type . "." . $id]);
    }

    /**
     * @return ResourceObject[]
     */
    public function includedResources(): array
    {
        return array_values($this->includedKeys);
    }

    private function addPrimaryResource(ResponseResourceObjectInterface $resource): ResourceObjects
    {
        $type = $resource->type();
        $id = $resource->id();
        if ($this->hasIncludedResource($type, $id) === true) {
            unset($this->includedKeys[$type . "." . $id]);
        }

        $this->addResource($this->primaryKeys, $resource);

        return $this;
    }

    private function addIncludedResource(ResponseResourceObjectInterface $resource): ResourceObjects
    {
        if ($this->hasPrimaryResource($resource->type(), $resource->id()) === false) {
            $this->addResource($this->includedKeys, $resource);
        }

        return $this;
    }

    /**
     * @return void
     */
    private function addResource(array &$keys, ResponseResourceObjectInterface $resource)
    {
        $type = $resource->type();
        $id = $resource->id();

        $this->resources[$type . "." . $id] = $resource;
        $keys[$type . "." . $id] = &$this->resources[$type . "." . $id];
    }
}
