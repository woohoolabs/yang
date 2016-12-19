<?php
namespace WoohooLabs\Yang\JsonApi\Request;

class JsonApiResource
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
    private $attributes;

    /**
     * @var JsonApiRelationshipInterface[]
     */
    private $relationships;

    /**
     * @param string $type
     * @param string $id
     * @return $this
     */
    public static function create($type, $id = "")
    {
        return new self($type, $id);
    }

    /**
     * @param string $type
     * @param string $id
     */
    public function __construct($type, $id = "")
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
     * @param string $name
     * @param JsonApiToOneRelationship $relationship
     * @return $this
     */
    public function setToOneRelationship($name, JsonApiToOneRelationship $relationship)
    {
        return $this->setRelationship($name, $relationship);
    }

    /**
     * @param string $name
     * @param JsonApiToManyRelationship $relationship
     * @return $this
     */
    public function setToManyRelationship($name, JsonApiToManyRelationship $relationship)
    {
        return $this->setRelationship($name, $relationship);
    }

    /**
     * @param string $name
     * @param JsonApiRelationshipInterface $relationship
     * @return $this
     */
    public function setRelationship($name, JsonApiRelationshipInterface $relationship)
    {
        $this->relationships[$name] = $relationship;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $resource = [
            "data" => [
                "type" => $this->type
            ]
        ];

        if (empty($this->id) === false) {
            $resource["data"]["id"] = $this->id;
        }

        if (empty($this->attributes) === false) {
            $resource["data"]["attributes"] = $this->attributes;
        }

        foreach ($this->relationships as $name => $relationship) {
            $resource["data"]["relationships"][$name] = $relationship->toArray();
        }

        return $resource;
    }
}
