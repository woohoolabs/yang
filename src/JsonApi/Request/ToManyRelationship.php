<?php
namespace WoohooLabs\Yang\JsonApi\Request;

class ToManyRelationship implements RelationshipInterface
{
    /**
     * @var array
     */
    private $resourceIdentifiers = [];

    public static function create()
    {
        return new self();
    }

    /**
     * @param string $type
     * @param string $id
     */
    public function addResourceIdentifier($type, $id)
    {
        $this->resourceIdentifiers[] = ["type" => $type, "id" => $id];
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            "data" => $this->resourceIdentifiers
        ];
    }
}
