<?php
namespace WoohooLabs\Yang\JsonApi\Schema;

class ResourceObjects
{
    /*
     * @var bool
     */
    private $isSinglePrimaryResource;

    /**
     * @var \WoohooLabs\Yang\JsonApi\Schema\ResourceObject[]
     */
    private $resources = [];

    /**
     * @var array
     */
    private $primaryKeys = [];

    /**
     * @var array
     */
    private $includedKeys = [];

    /**
     * @param array $data
     * @param array $included
     * @return ResourceObjects
     */
    public static function createFromSinglePrimaryData(array $data, array $included)
    {
        return new self($data, $included, true);
    }

    /**
     * @param array $data
     * @param array $included
     * @return ResourceObjects
     */
    public static function createFromCollectionPrimaryData(array $data, array $included)
    {
        return new self($data, $included, false);
    }

    /**
     * @param array $data
     * @param array $included
     * @param bool $isSinglePrimaryResource
     */
    public function __construct(array $data, array $included, $isSinglePrimaryResource)
    {
        $this->isSinglePrimaryResource = $isSinglePrimaryResource;

        if ($this->isSinglePrimaryResource === true) {
            if (empty($data) === false) {
                $this->addPrimaryResource(ResourceObject::createFromArray($data, $this));
            }
        } else {
            foreach ($data as $resource) {
                $this->addPrimaryResource(ResourceObject::createFromArray($resource, $this));
            }
        }

        foreach ($included as $resource) {
            $this->addIncludedResource(ResourceObject::createFromArray($resource, $this));
        }
    }

    /**
     * @return array|null
     */
    public function primaryDataToArray()
    {
        return $this->isSinglePrimaryResource ? $this->primaryResourceToArray() : $this->primaryCollectionToArray();
    }

    /**
     * @return array
     */
    public function includedToArray()
    {
        $result = [];
        foreach ($this->includedKeys as $resource) {
            /** @var \WoohooLabs\Yang\JsonApi\Schema\ResourceObject $resource */
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

    /**
     * @return array
     */
    private function primaryCollectionToArray()
    {
        $result = [];
        foreach ($this->primaryKeys as $resource) {
            /** @var \WoohooLabs\Yang\JsonApi\Schema\ResourceObject $resource */
            $result[] = $resource->toArray();
        }

        return $result;
    }

    /**
     * @return bool
     */
    public function isSinglePrimaryResource()
    {
        return $this->isSinglePrimaryResource === true;
    }

    /**
     * @return bool
     */
    public function isPrimaryResourceCollection()
    {
        return $this->isSinglePrimaryResource === false;
    }

    /**
     * @param string $type
     * @param string $id
     * @return \WoohooLabs\Yang\JsonApi\Schema\ResourceObject|null
     */
    public function resource($type, $id)
    {
        return isset($this->resources[$type . "." . $id]) ? $this->resources[$type . "." . $id] : null;
    }

    /**
     * @return bool
     */
    public function hasAnyPrimaryResources()
    {
        return empty($this->primaryKeys) === false;
    }

    /**
     * @return \WoohooLabs\Yang\JsonApi\Schema\ResourceObject[]
     */
    public function primaryResources()
    {
        return array_values($this->primaryKeys);
    }

    /**
     * @return \WoohooLabs\Yang\JsonApi\Schema\ResourceObject|null
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

    /**
     * @param string $type
     * @param string $id
     * @return bool
     */
    public function hasPrimaryResource($type, $id)
    {
        return isset($this->primaryKeys[$type . "." . $id]);
    }

    /**
     * @return bool
     */
    public function hasAnyIncludedResources()
    {
        return empty($this->includedKeys) === false;
    }

    /**
     * @param string $type
     * @param string $id
     * @return bool
     */
    public function hasIncludedResource($type, $id)
    {
        return isset($this->includedKeys[$type . "." . $id]);
    }

    /**
     * @return \WoohooLabs\Yang\JsonApi\Schema\ResourceObject[]
     */
    public function includedResources()
    {
        return array_values($this->includedKeys);
    }

    /**
     * @param \WoohooLabs\Yang\JsonApi\Schema\ResourceObject $resource
     * @return $this
     */
    private function addPrimaryResource(ResourceObject $resource)
    {
        $type = $resource->type();
        $id = $resource->id();
        if ($this->hasIncludedResource($type, $id) === true) {
            unset($this->includedKeys[$type . "." . $id]);
        }

        $this->addResource($this->primaryKeys, $resource);

        return $this;
    }

    /**
     * @param \WoohooLabs\Yang\JsonApi\Schema\ResourceObject $resource
     * @return $this
     */
    private function addIncludedResource(ResourceObject $resource)
    {
        if ($this->hasPrimaryResource($resource->type(), $resource->id()) === false) {
            $this->addResource($this->includedKeys, $resource);
        }

        return $this;
    }

    private function addResource(&$keys, ResourceObject $resource)
    {
        $type = $resource->type();
        $id = $resource->id();

        $this->resources[$type . "." . $id] = $resource;
        $keys[$type . "." . $id] = &$this->resources[$type . "." . $id];
    }
}
