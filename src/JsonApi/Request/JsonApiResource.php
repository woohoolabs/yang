<?php
namespace WoohooLabs\Yang\JsonApi\Request;

class JsonApiResource
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
    protected $attributes;

    /**
     * @var array
     */
    protected $relationships;

    /**
     * @param string $type
     * @param string $id
     * @return $this
     */
    public static function create($type = "", $id = "")
    {
        return new self($type, $id);
    }

    /**
     * @param string $type
     * @param string $id
     */
    public function __construct($type = "", $id = "")
    {
        $this->type = $type;
        $this->id = $id;
        $this->attributes = [];
        $this->relationships = [];
    }

    /**
     * @param array $attributes
     * @return $this
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;
        return $this;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function setAttribute($name, $value)
    {
        $this->attributes[$name] = $value;
        return $this;
    }

    /**
     * @param string $relationship
     * @param string $type
     * @param string $id
     * @return $this
     */
    public function setToOneResourceIdentifier($relationship, $type, $id)
    {
        $this->relationships[$relationship] = ["type" => $type, "id" => $id];
        return $this;
    }

    /**
     * @param string $relationship
     * @param string $type
     * @param string $id
     * @return $this
     */
    public function addToManyResourceIdentifier($relationship, $type, $id)
    {
        $this->relationships[$relationship][] = ["type" => $type, "id" => $id];
        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $resource = ["data" => ["type" => $this->type]];
        if (empty($this->id) === false) {
            $resource["data"]["id"] = $this->id;
        }

        foreach ($this->attributes as $name => $attribute) {
            $resource["data"]["attributes"][$name] = $attribute;
        }

        foreach ($this->relationships as $name => $relationship) {
            $resource["data"]["attributes"][$name]["data"] = $relationship;
        }

        return $resource;
    }
}
