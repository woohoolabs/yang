<?php
namespace WoohooLabs\Yang\JsonApi\Schema;

class Resource
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $id;

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
    protected $attributes;

    /**
     * @var \WoohooLabs\Yang\JsonApi\Schema\Relationship[]
     */
    protected $relationships;

    /**
     * @param array $array
     * @param \WoohooLabs\Yang\JsonApi\Schema\Resources $resources
     */
    public function __construct($array, Resources $resources)
    {
        $this->type = empty($array["type"]) ? "" : $array["type"];
        $this->id = empty($array["id"]) ? "" : $array["id"];
        $this->meta = $this->isArrayKey($array, "meta") ? $array["meta"] : [];
        $this->links = Links::createFromArray($this->isArrayKey($array, "links") ? $array["links"] : []);
        $this->attributes = $this->isArrayKey($array, "attributes") ? $array["attributes"] : [];

        $this->relationships = [];
        if ($this->isArrayKey($array, "relationships")) {
            foreach ($array["relationships"] as $name => $relationship) {
                $this->relationships[$name] = new Relationship($name, $relationship, $resources);
            }
        }
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $result = [
            "type" => $this->type,
            "id" => $this->id
        ];

        if (empty($this->meta) === false) {
            $result["meta"] = $this->meta;
        }

        if ($this->links) {
            $result["links"] = $this->links->toArray();
        }

        if (empty($this->attributes) === false) {
            $result["attributes"] = $this->attributes;
        }

        if (empty($this->relationships) === false) {
            $result["relationships"] = [];
            foreach ($this->relationships as $name => $relationship) {
                $result["relationships"][$name] = $relationship->toArray();
            }
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
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
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasAttribute($name)
    {
        return array_key_exists($name, $this->attributes);
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    public function getAttribute($name)
    {
        return $this->hasAttribute($name) ? $this->attributes[$name] : null;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasRelationship($name)
    {
        return array_key_exists($name, $this->relationships);
    }

    /**
     * @param string $name
     * @return \WoohooLabs\Yang\JsonApi\Schema\Relationship|null|
     */
    public function getRelationship($name)
    {
        return $this->hasRelationship($name) ? $this->relationships[$name] : null;
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
