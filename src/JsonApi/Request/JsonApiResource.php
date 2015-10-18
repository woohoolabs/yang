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
     */
    public function __construct($type = "", $id = "")
    {
        $this->type = $type;
        $this->id = $id;
        $this->attributes = [];
        $this->relationships = [];
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function setAttribute($name, $value)
    {
        $this->attributes[$name] = $value;
    }

    /**
     * @param string $relationship
     * @param string $type
     * @param string $id
     */
    public function setToOneResourceIdentifier($relationship, $type, $id)
    {
        $this->relationships[$relationship] = ["type" => $type, "id" => $id];
    }

    /**
     * @param string $relationship
     * @param string $type
     * @param string $id
     */
    public function addToManyResourceIdentifier($relationship, $type, $id)
    {
        $this->relationships[$relationship][] = ["type" => $type, "id" => $id];
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $resource = ["data" => []];
        foreach ($this->attributes as $name => $attribute) {
            $resource["data"]["attributes"][$name] = $attribute;
        }

        foreach ($this->relationships as $name => $relationship) {
            $resource["data"]["attributes"][$name]["data"] = $relationship;
        }

        return $resource;
    }
}
