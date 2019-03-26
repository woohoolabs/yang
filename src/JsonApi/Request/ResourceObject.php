<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\JsonApi\Request;

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
    private $attributes;

    /**
     * @var RelationshipInterface[]
     */
    private $relationships;

    public static function create(string $type, string $id = ""): ResourceObject
    {
        return new self($type, $id);
    }

    public function __construct(string $type, string $id = "")
    {
        $this->type = $type;
        $this->id = $id;
        $this->attributes = [];
        $this->relationships = [];
    }

    public function type(): string
    {
        return $this->type;
    }

    public function setType(string $type): ResourceObject
    {
        $this->type = $type;

        return $this;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function setId(string $id): ResourceObject
    {
        $this->id = $id;

        return $this;
    }

    public function attributes(): array
    {
        return $this->attributes;
    }

    public function setAttributes(array $attributes): ResourceObject
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * @param mixed $value
     */
    public function setAttribute(string $name, $value): ResourceObject
    {
        $this->attributes[$name] = $value;

        return $this;
    }

    /**
     * @return RelationshipInterface[]
     */
    public function relationships(): array
    {
        return $this->relationships;
    }

    public function setToOneRelationship(string $name, ToOneRelationship $relationship): ResourceObject
    {
        return $this->setRelationship($name, $relationship);
    }

    public function setToManyRelationship(string $name, ToManyRelationship $relationship): ResourceObject
    {
        return $this->setRelationship($name, $relationship);
    }

    public function setRelationship(string $name, RelationshipInterface $relationship): ResourceObject
    {
        $this->relationships[$name] = $relationship;

        return $this;
    }

    /**
     * @internal
     */
    public function toArray(): array
    {
        $resource = [
            "data" => [
                "type" => $this->type,
            ],
        ];

        if ($this->id !== "") {
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
