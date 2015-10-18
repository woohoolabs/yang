<?php
namespace WoohooLabs\Yang\JsonApi\Schema;

class Relationship
{
    /**
     * @var bool
     */
    protected $isToOneRelationship;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $meta;

    /**
     * @var \WoohooLabs\Yang\JsonApi\Schema\Links
     */
    protected $links;

    /**
     * @var array
     */
    protected $resourceMap;

    /**
     * @var \WoohooLabs\Yang\JsonApi\Schema\Resources
     */
    protected $resources;

    /**
     * @param string $name
     * @param array $array
     * @param \WoohooLabs\Yang\JsonApi\Schema\Resources $resources
     */
    public function __construct($name, array $array, Resources $resources)
    {
        $this->name = $name;
        $this->meta = $this->isArrayKey($array, "meta") ? $array["meta"] : [];
        $this->links = Links::createFromArray($this->isArrayKey($array, "links") ? $array["links"] : []);

        if ($this->isArrayKey($array, "data")) {
            if ($this->isAssociativeArray($array["data"])) {
                $this->isToOneRelationship = true;
                if (empty($array["data"]["type"]) === false && empty($array["data"]["id"]) === false) {
                    $this->resourceMap = [["type" => $array["data"]["type"], "id" => $array["data"]["id"]]];
                }
            } else {
                $this->isToOneRelationship = false;
                $this->resourceMap = [];
                foreach ($array["data"] as $item) {
                    if (empty($item["type"]) === false && empty($item["id"]) === false) {
                        $this->resourceMap[] = ["type" => $item["type"], "id" => $item["id"]];
                    }
                }
            }
        } else {
            $this->isToOneRelationship = null;
        }

        $this->resources = $resources;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $result = [];

        if (empty($this->meta) === false) {
            $result["meta"] = $this->meta;
        }

        if ($this->links) {
            $result["links"] = $this->links->toArray();
        }

        if (empty($this->resourceMap) === false) {
            $result["data"] = $this->resourceMap;
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function hasMeta()
    {
        return empty($this->meta) === false;
    }

    /**
     * @return array
     */
    public function getMeta()
    {
        return $this->meta;
    }

    /**
     * @return bool
     */
    public function hasLinks()
    {
        return $this->links->hasLinks();
    }

    /**
     * @return \WoohooLabs\Yang\JsonApi\Schema\Links
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * @return array
     */
    public function getResourceLinks()
    {
        return $this->resourceMap;
    }

    /**
     * @return array|null
     */
    public function getResourceLink()
    {
        $link = reset($this->resourceMap);

        return $link === false ? null : $link;
    }

    /**
     * @param string $type
     * @param string $id
     * @return bool
     */
    public function hasResource($type, $id)
    {
        return $this->resources->hasIncludedResource($type, $id);
    }

    /**
     * @return \WoohooLabs\Yang\JsonApi\Schema\Resource[]
     */
    public function getResourceCollection()
    {
        $resources = [];
        foreach ($this->resourceMap as $resourceLink) {
            if ($this->hasResource($resourceLink["type"], $resourceLink["id"])) {
                $resources[] = $this->getResourceBy($resourceLink["type"], $resourceLink["id"]);
            }
        }

        return $resources;
    }

    /**
     * @return \WoohooLabs\Yang\JsonApi\Schema\Resource[]
     */
    public function getResourceMap()
    {
        $resources = [];
        foreach ($this->resourceMap as $resourceLink) {
            $type = $resourceLink["type"];
            $id = $resourceLink["id"];
            if ($this->hasResource($type, $id)) {
                $resources[$type][$id] = $this->getResourceBy($type, $id);
            }
        }

        return $resources;
    }

    /**
     * @return \WoohooLabs\Yang\JsonApi\Schema\Resource|null
     */
    public function getResource()
    {
        $resourceMap = reset($this->resourceMap);
        if (is_array($resourceMap) === false) {
            return null;
        }

        return $this->getResourceBy($resourceMap["type"], $resourceMap["id"]);
    }

    /**
     * @param string $type
     * @param string $id
     * @return \WoohooLabs\Yang\JsonApi\Schema\Resource|null
     */
    public function getResourceBy($type, $id)
    {
        return $this->resources->getResource($type, $id);
    }

    /**
     * @param array $array
     * @return bool
     */
    protected function isAssociativeArray(array $array)
    {
        return (bool)count(array_filter(array_keys($array), 'is_string'));
    }

    /**
     * @param array $array
     * @param string $key
     * @return bool
     */
    protected function isArrayKey($array, $key)
    {
        return isset($array[$key]) && is_array($array[$key]);
    }
}
