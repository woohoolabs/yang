<?php
namespace WoohooLabs\Yang\JsonApi\Schema;

class ResourceObject
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $id;

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
    private $attributes;

    /**
     * @var \WoohooLabs\Yang\JsonApi\Schema\Relationship[]
     */
    private $relationships;

    /**
     * @param array $array
     * @param \WoohooLabs\Yang\JsonApi\Schema\ResourceObjects $resources
     * @return ResourceObject
     */
    public static function createFromArray($array, ResourceObjects $resources)
    {
        $type = isset($array["type"]) && is_string($array["type"]) ? $array["type"] : "";
        $id = isset($array["id"]) && is_string($array["id"]) ? $array["id"] : "";
        $meta = self::isArrayKey($array, "meta") ? $array["meta"] : [];
        $links = Links::createFromArray(self::isArrayKey($array, "links") ? $array["links"] : []);
        $attributes = self::isArrayKey($array, "attributes") ? $array["attributes"] : [];

        $relationships = [];
        if (self::isArrayKey($array, "relationships")) {
            foreach ($array["relationships"] as $name => $relationship) {
                if (is_string($name) === false || is_array($relationship) === false) {
                    continue;
                }

                $relationships[$name] = Relationship::createFromArray($name, $relationship, $resources);
            }
        }

        return new self($type, $id, $meta, $links, $attributes, $relationships);
    }

    /**
     * @param string $type
     * @param string $id
     * @param array $meta
     * @param Links $links
     * @param array $attributes
     * @param Relationship[] $relationships
     */
    public function __construct($type, $id, array $meta, Links $links, array $attributes, array $relationships)
    {
        $this->type = $type;
        $this->id = $id;
        $this->meta = $meta;
        $this->links = $links;
        $this->attributes = $attributes;
        $this->relationships = $relationships;
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

        if ($this->links->hasAnyLinks()) {
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
    public function type()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function id()
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
    public function attributes()
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
    public function attribute($name)
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
     * @return \WoohooLabs\Yang\JsonApi\Schema\Relationship|null
     */
    public function relationship($name)
    {
        return $this->hasRelationship($name) ? $this->relationships[$name] : null;
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
