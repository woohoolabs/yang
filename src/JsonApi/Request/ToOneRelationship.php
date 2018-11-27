<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\JsonApi\Request;

final class ToOneRelationship implements RelationshipInterface
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

    public static function create(string $type, string $id, array $meta = []): ToOneRelationship
    {
        return new self($type, $id, $meta);
    }

    public function __construct(string $type, string $id, array $meta = [])
    {
        $this->type = $type;
        $this->id = $id;
        $this->meta = $meta;
    }

    /**
     * @internal
     */
    public function toArray(): array
    {
        $resourceIdentifier = [
            "type" => $this->type,
            "id" => $this->id,
        ];

        if (empty($this->meta) === false) {
            $resourceIdentifier["meta"] = $this->meta;
        }

        return [
            "data" => $resourceIdentifier,
        ];
    }
}
