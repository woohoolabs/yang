<?php
namespace WoohooLabs\Yang\JsonApi\Schema;

class ResourceObjects
{
    /*
     * @var bool
     */
    protected $isSinglePrimaryResource;

    /**
     * @var \WoohooLabs\Yang\JsonApi\Schema\ResourceObject[]
     */
    protected $resources = [];

    /**
     * @var array
     */
    protected $primaryKeys = [];

    /**
     * @var array
     */
    protected $includedKeys = [];

    /**
     * @param array $data
     * @param array $included
     */
    public function __construct(array $data, array $included)
    {
        if (empty($data)) {
            $this->isSinglePrimaryResource = null;
        } else {
            $this->isSinglePrimaryResource = $this->isAssociativeArray($data) === true;
        }

        if ($this->isSinglePrimaryResource === true) {
            $this->addPrimaryResource(new ResourceObject($data, $this));
        } else {
            foreach ($data as $resource) {
                $this->addPrimaryResource(new ResourceObject($resource, $this));
            }
        }

        foreach ($included as $resource) {
            $this->addIncludedResource(new ResourceObject($resource, $this));
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
            $result[] = $resource;
        }

        return $result;
    }

    /**
     * @return array|null
     */
    protected function primaryResourceToArray()
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
    protected function primaryCollectionToArray()
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
    public function getResource($type, $id)
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
    public function getPrimaryResources()
    {
        return array_values($this->primaryKeys);
    }

    /**
     * @return \WoohooLabs\Yang\JsonApi\Schema\ResourceObject|null
     */
    public function getPrimaryResource()
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
    public function getIncludedResources()
    {
        return array_values($this->includedKeys);
    }

    /**
     * @param \WoohooLabs\Yang\JsonApi\Schema\ResourceObject $resource
     * @return $this
     */
    protected function addPrimaryResource(ResourceObject $resource)
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
    protected function addIncludedResource(ResourceObject $resource)
    {
        if ($this->hasPrimaryResource($resource->type(), $resource->id()) === false) {
            $this->addResource($this->includedKeys, $resource);
        }

        return $this;
    }

    protected function addResource(&$keys, ResourceObject $resource)
    {
        $type = $resource->type();
        $id = $resource->id();

        $this->resources[$type . "." . $id] = $resource;
        $keys[$type . "." . $id] = &$this->resources[$type . "." . $id];
    }

    /**
     * @param array $array
     * @return bool
     */
    private function isAssociativeArray(array $array)
    {
        return (bool)count(array_filter(array_keys($array), 'is_string'));
    }
}
