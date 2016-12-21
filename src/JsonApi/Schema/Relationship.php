<?php
namespace WoohooLabs\Yang\JsonApi\Schema;

class Relationship
{
    /**
     * @var bool|null
     */
    private $isToOneRelationship;

    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $meta;

    /**
     * @var \WoohooLabs\Yang\JsonApi\Schema\Links
     */
    private $links;

    /**
     * @var array
     */
    private $resourceMap = [];

    /**
     * @var \WoohooLabs\Yang\JsonApi\Schema\ResourceObjects
     */
    private $resources;

    /**
     * @param string $name
     * @param array $array
     * @param \WoohooLabs\Yang\JsonApi\Schema\ResourceObjects $resources
     * @return Relationship
     */
    public static function createFromArray($name, array $array, ResourceObjects $resources)
    {
        $meta = self::isArrayKey($array, "meta") ? $array["meta"] : [];
        $links = Links::createFromArray(self::isArrayKey($array, "links") ? $array["links"] : []);

        if (self::isArrayKey($array, "data") === false) {
            return self::createEmptyFromArray($name, $meta, $links, $resources);
        }

        if (self::isAssociativeArray($array["data"])) {
            return self::createToOneFromArray($name, $meta, $links, $array["data"], $resources);
        }

        return self::createToManyFromArray($name, $meta, $links, $array["data"], $resources);
    }

    /**
     * @param string $name
     * @param array $meta
     * @param Links $links
     * @param ResourceObjects $resources
     * @return Relationship
     */
    private static function createEmptyFromArray($name, array $meta, Links $links, ResourceObjects $resources)
    {
        return new Relationship($name, $meta, $links, [], $resources, null);
    }

    /**
     * @param string $name
     * @param array $meta
     * @param Links $links
     * @param array $data
     * @param ResourceObjects $resources
     * @return Relationship
     */
    private static function createToOneFromArray(
        $name,
        array $meta,
        Links $links,
        array $data,
        ResourceObjects $resources
    ) {
        $resourceMap = [];
        $isToOneRelationship = true;

        if (empty($data["type"]) === false && empty($data["id"]) === false) {
            $resourceMap = [
                [
                    "type" => $data["type"],
                    "id" => $data["id"]
                ]
            ];
        }

        return new Relationship($name, $meta, $links, $resourceMap, $resources, $isToOneRelationship);
    }

    /**
     * @param string $name
     * @param array $meta
     * @param Links $links
     * @param array $data
     * @param ResourceObjects $resources
     * @return Relationship
     */
    private static function createToManyFromArray(
        $name,
        array $meta,
        Links $links,
        array $data,
        ResourceObjects $resources
    ) {
        $isToOneRelationship = false;
        $resourceMap = [];

        foreach ($data as $item) {
            if (empty($item["type"]) === false && empty($item["id"]) === false) {
                $resourceMap[] = [
                    "type" => $item["type"],
                    "id" => $item["id"]
                ];
            }
        }

        return new Relationship($name, $meta, $links, $resourceMap, $resources, $isToOneRelationship);
    }

    /**
     * @param string $name
     * @param array $meta
     * @param Links $links
     * @param array $resourceMap
     * @param ResourceObjects $resources
     * @param bool|null $isToOneRelationship
     */
    public function __construct(
        $name,
        array $meta,
        Links $links,
        array $resourceMap,
        ResourceObjects $resources,
        $isToOneRelationship = null
    ) {
        $this->name = $name;
        $this->meta = $meta;
        $this->links = $links;
        $this->resourceMap = $resourceMap;
        $this->isToOneRelationship = $isToOneRelationship;
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

        if ($this->links->hasAnyLinks()) {
            $result["links"] = $this->links->toArray();
        }

        if (empty($this->resourceMap) === false) {
            $result["data"] = $this->isToOneRelationship ? reset($this->resourceMap) : $this->resourceMap;
        }

        return $result;
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function isToOneRelationship()
    {
        return $this->isToOneRelationship === true;
    }

    /**
     * @return bool
     */
    public function isToManyRelationship()
    {
        return $this->isToOneRelationship === false;
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
    public function meta()
    {
        return $this->meta;
    }

    /**
     * @return bool
     */
    public function hasLinks()
    {
        return $this->links->hasAnyLinks();
    }

    /**
     * @return \WoohooLabs\Yang\JsonApi\Schema\Links
     */
    public function links()
    {
        return $this->links;
    }

    /**
     * @return array
     */
    public function resourceLinks()
    {
        return $this->resourceMap;
    }

    /**
     * @return array|null
     */
    public function firstResourceLink()
    {
        $link = reset($this->resourceMap);

        return $link === false ? null : $link;
    }

    /**
     * @param string $type
     * @param string $id
     * @return bool
     */
    public function hasIncludedResource($type, $id)
    {
        return $this->resources->hasIncludedResource($type, $id);
    }

    /**
     * @return \WoohooLabs\Yang\JsonApi\Schema\ResourceObject[]
     */
    public function resources()
    {
        if ($this->isToOneRelationship) {
            return [];
        }

        $resources = [];
        foreach ($this->resourceMap as $resourceLink) {
            if ($this->hasIncludedResource($resourceLink["type"], $resourceLink["id"])) {
                $resources[] = $this->resourceBy($resourceLink["type"], $resourceLink["id"]);
            }
        }

        return $resources;
    }

    /**
     * @return \WoohooLabs\Yang\JsonApi\Schema\ResourceObject[]
     */
    public function resourceMap()
    {
        $resources = [];
        foreach ($this->resourceMap as $resourceLink) {
            $type = $resourceLink["type"];
            $id = $resourceLink["id"];
            if ($this->hasIncludedResource($type, $id)) {
                $resources[$type][$id] = $this->resourceBy($type, $id);
            }
        }

        return $resources;
    }

    /**
     * @return \WoohooLabs\Yang\JsonApi\Schema\ResourceObject|null
     */
    public function resource()
    {
        if ($this->isToOneRelationship === false) {
            return null;
        }

        $resourceMap = reset($this->resourceMap);
        if (is_array($resourceMap) === false) {
            return null;
        }

        return $this->resourceBy($resourceMap["type"], $resourceMap["id"]);
    }

    /**
     * @param string $type
     * @param string $id
     * @return \WoohooLabs\Yang\JsonApi\Schema\ResourceObject|null
     */
    public function resourceBy($type, $id)
    {
        return $this->resources->resource($type, $id);
    }

    /**
     * @param array $array
     * @return bool
     */
    private static function isAssociativeArray(array $array)
    {
        return (bool)count(array_filter(array_keys($array), 'is_string'));
    }

    /**
     * @param array $array
     * @param string $key
     * @return bool
     */
    private static function isArrayKey($array, $key)
    {
        return isset($array[$key]) && is_array($array[$key]);
    }
}
