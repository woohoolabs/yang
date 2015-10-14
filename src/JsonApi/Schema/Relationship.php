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
