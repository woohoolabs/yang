<?php
namespace WoohooLabs\Yang\JsonApi\Request;

class JsonApiToOneRelationship implements JsonApiRelationshipInterface
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
     * @param string $type
     * @param string $id
     * @return $this
     */
    public static function create($type, $id)
    {
        return new self($type, $id);
    }

    /**
     * @param string $type
     * @param string $id
     */
    public function __construct($type, $id)
    {
        $this->type = $type;
        $this->id = $id;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            "data" => [
                "type" => $this->type,
                "id" => $this->id,
            ]
        ];
    }
}
