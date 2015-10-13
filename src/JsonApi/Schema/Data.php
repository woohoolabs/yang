<?php
namespace WoohooLabs\Yang\JsonApi\Schema;

class Data
{
    /*
     * @var bool
     */
    protected $isSingleResourceData;

    /**
     * @var \WoohooLabs\Yang\JsonApi\Schema\Resource[]
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
     * @return $this
     */
    public static function createFromArray(array $data)
    {
        $isSingleResourceData = self::isAssociativeArray($data);

        $resourceList = [];
        if ($isSingleResourceData) {
            $resourceList = [Resource::createFromArray($data)];
        } else {
            foreach ($data as $resource) {
                $resourceList[] = Resource::createFromArray($resource);
            }
        }

        return new self($isSingleResourceData, $resourceList);
    }

    /**
     * @param bool $isSingleResourceData
     * @param \WoohooLabs\Yang\JsonApi\Schema\Resource[] $resources
     */
    public function __construct($isSingleResourceData, array $resources)
    {
        $this->isSingleResourceData = $isSingleResourceData;
        $this->setPrimaryResources($resources);
    }

    /**
     * @return array|null
     */
    public function primaryDataToArray()
    {
        return $this->isSingleResourceData ? $this->primaryResourceToArray() : $this->primaryCollectionToArray();
    }

    /**
     * @return array
     */
    public function includedToArray()
    {
        ksort($this->includedKeys);

        $result = [];
        foreach ($this->includedKeys as $type => $ids) {
            ksort($ids);
            foreach ($ids as $id => $value) {
                $result[] = $this->resources[$type][$id]->toArray();
            }
        }

        return $result;
    }

    /**
     * @return array|null
     */
    protected function primaryResourceToArray()
    {
        if ($this->hasPrimaryResources() === false) {
            return null;
        }

        $ids = reset($this->primaryKeys);
        $key = key($this->primaryKeys);
        $id = key($ids);

        return $this->resources[$key][$id]->toArray();
    }

    /**
     * @return array
     */
    protected function primaryCollectionToArray()
    {
        ksort($this->primaryKeys);

        $result = [];
        foreach ($this->primaryKeys as $type => $ids) {
            ksort($ids);
            foreach ($ids as $id => $value) {
                $result[] = $this->resources[$type][$id]->toArray();
            }
        }

        return $result;
    }

    /**
     * @param string $type
     * @param string $id
     * @return \WoohooLabs\Yang\JsonApi\Schema\Resource|null
     */
    public function getResource($type, $id)
    {
        return isset($this->resources[$type][$id]) ? $this->resources[$type][$id] : null;
    }

    /**
     * @return bool
     */
    public function hasPrimaryResources()
    {
        return empty($this->primaryKeys) === false;
    }

    /**
     * @param string $type
     * @param string $id
     * @return bool
     */
    public function hasPrimaryResource($type, $id)
    {
        return isset($this->primaryKeys[$type][$id]);
    }

    /**
     * @return bool
     */
    public function hasIncludedResources()
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
        return isset($this->includedKeys[$type][$id]);
    }

    /**
     * @param \WoohooLabs\Yang\JsonApi\Schema\Resource[] $resources
     * @return $this
     */
    public function setPrimaryResources(array $resources)
    {
        $this->primaryKeys = [];
        foreach ($resources as $resource) {
            $this->addPrimaryResource($resource);
        }

        return $this;
    }

    /**
     * @param \WoohooLabs\Yang\JsonApi\Schema\Resource $resource
     * @return $this
     */
    public function addPrimaryResource(Resource $resource)
    {
        $type = $resource->getType();
        $id = $resource->getId();
        if ($this->hasIncludedResource($type, $id) === true) {
            unset($this->includedKeys[$type][$id]);
            $this->primaryKeys[$type][$id] = true;
        } else {
            $this->addResource($this->primaryKeys, $resource);
        }

        return $this;
    }

    /**
     * @param \WoohooLabs\Yang\JsonApi\Schema\Resource[] $resources
     * @return $this
     */
    public function setIncludedResources(array $resources)
    {
        $this->includedKeys = [];
        foreach ($resources as $resource) {
            $this->addIncludedResource($resource);
        }

        return $this;
    }

    /**
     * @param \WoohooLabs\Yang\JsonApi\Schema\Resource $resource
     * @return $this
     */
    public function addIncludedResource(Resource $resource)
    {
        if ($this->hasPrimaryResource($resource->getType(), $resource->getId()) === false) {
            $this->addResource($this->includedKeys, $resource);
        }

        return $this;
    }

    protected function addResource(&$keys, Resource $resource)
    {
        $type = $resource->getType();
        $id = $resource->getId();

        $keys[$type][$id] = true;
        $this->resources[$type][$id] = $resource;
    }


    /**
     * @param array $array
     * @return bool
     */
    private static function isAssociativeArray(array $array)
    {
        return (bool)count(array_filter(array_keys($array), 'is_string'));
    }
}
