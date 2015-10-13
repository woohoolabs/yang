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

    protected $relationships;

    /**
     * @param array $array
     * @return $this
     */
    public static function createFromArray($array)
    {
        $type = empty($array["type"]) ? "" : $array["type"];
        $id = empty($array["id"]) ? "" : $array["id"];
        $links = Links::createFromArray(isset($array["links"]) && is_array($array["links"]) ? $array["links"] : []);
        $meta = isset($array["meta"]) && is_array($array["meta"]) ? $array["meta"] : [];
        $attributes = isset($array["attributes"]) && is_array($array["attributes"]) ? $array["attributes"] : [];

        return new self($type, $id, $meta, $links, $attributes);
    }

    /**
     * @param string $type
     * @param string $id
     * @param array $meta
     * @param \WoohooLabs\Yang\JsonApi\Schema\Links $links
     * @param array $attributes
     */
    public function __construct($type, $id, array $meta, Links $links, array $attributes)
    {
        $this->type = $type;
        $this->id = $id;
        $this->meta = $meta;
        $this->links = $links;
        $this->attributes = $attributes;
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

        if ($this->attributes) {
            $result["attributes"] = $this->attributes;
        }

        if ($this->relationships) {
            $result["relationships"] = $this->relationships;
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
}
