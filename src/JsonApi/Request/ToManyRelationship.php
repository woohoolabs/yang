<?php
declare(strict_types=1);

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

    public function addResourceIdentifier(string $type, string $id, array $meta = [])
    {
        $resourceIdentifier = [
            "type" => $type,
            "id" => $id,
        ];

        if (empty($meta) === false) {
            $resourceIdentifier["meta"] = $meta;
        }

        $this->resourceIdentifiers[] = $resourceIdentifier;
    }

    public function toArray(): array
    {
        return [
            "data" => $this->resourceIdentifiers,
        ];
    }
}
