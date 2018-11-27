<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\JsonApi\Request;

final class ToManyRelationship implements RelationshipInterface
{
    /**
     * @var array
     */
    private $resourceIdentifiers = [];

    public static function create(): ToManyRelationship
    {
        return new self();
    }

    public function addResourceIdentifier(string $type, string $id, array $meta = []): ToManyRelationship
    {
        $resourceIdentifier = [
            "type" => $type,
            "id" => $id,
        ];

        if (empty($meta) === false) {
            $resourceIdentifier["meta"] = $meta;
        }

        $this->resourceIdentifiers[] = $resourceIdentifier;

        return $this;
    }

    /**
     * @internal
     */
    public function toArray(): array
    {
        return [
            "data" => $this->resourceIdentifiers,
        ];
    }
}
