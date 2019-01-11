<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\JsonApi\Schema\Resource;

use WoohooLabs\Yang\JsonApi\Exception\DocumentException;
use WoohooLabs\Yang\JsonApi\Schema\Link\ResourceLinks;
use WoohooLabs\Yang\JsonApi\Schema\Relationship;
use function array_key_exists;
use function array_merge;
use function is_array;
use function is_string;

final class ResourceObject
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

    /**
     * @var ResourceLinks
     */
    private $links;

    /**
     * @var array
     */
    private $attributes;

    /**
     * @var Relationship[]
     */
    private $relationships;

    /**
     * @param Relationship[] $relationships
     */
    public function __construct(
        string $type,
        string $id,
        array $meta,
        ResourceLinks $links,
        array $attributes,
        array $relationships
    ) {
        $this->type = $type;
        $this->id = $id;
        $this->meta = $meta;
        $this->links = $links;
        $this->attributes = $attributes;
        $this->relationships = $relationships;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function hasMeta(): bool
    {
        return empty($this->meta) === false;
    }

    public function meta(): array
    {
        return $this->meta;
    }

    public function hasLinks(): bool
    {
        return $this->links->hasAnyLinks();
    }

    public function links(): ResourceLinks
    {
        return $this->links;
    }

    public function attributes(): array
    {
        return $this->attributes;
    }

    public function idAndAttributes(): array
    {
        return array_merge(["id" => $this->id()], $this->attributes());
    }

    public function hasAttribute(string $name): bool
    {
        return array_key_exists($name, $this->attributes);
    }

    /**
     * @param mixed $default
     * @return mixed
     */
    public function attribute(string $name, $default = null)
    {
        return $this->hasAttribute($name) ? $this->attributes[$name] : $default;
    }

    /**
     * @return Relationship[]
     */
    public function relationships(): array
    {
        return $this->relationships;
    }

    public function hasRelationship(string $name): bool
    {
        return array_key_exists($name, $this->relationships);
    }

    /**
     * @throws DocumentException
     */
    public function relationship(string $name): Relationship
    {
        if ($this->hasRelationship($name) === false) {
            throw new DocumentException("Relationship with the '$name' name doesn't exist in the Document!");
        }

        return $this->relationships[$name];
    }

    /**
     * @internal
     */
    public static function fromArray(array $array, ResourceObjects $resources): ResourceObject
    {
        $type = isset($array["type"]) && is_string($array["type"]) ? $array["type"] : "";
        $id = isset($array["id"]) && is_string($array["id"]) ? $array["id"] : "";
        $meta = self::isArrayKey($array, "meta") ? $array["meta"] : [];
        $links = ResourceLinks::fromArray(self::isArrayKey($array, "links") ? $array["links"] : []);
        $attributes = self::isArrayKey($array, "attributes") ? $array["attributes"] : [];

        $relationships = [];
        if (self::isArrayKey($array, "relationships")) {
            foreach ($array["relationships"] as $name => $relationship) {
                if (is_string($name) === false || is_array($relationship) === false) {
                    continue;
                }

                $relationships[$name] = Relationship::fromArray($name, $relationship, $resources);
            }
        }

        return new self($type, $id, $meta, $links, $attributes, $relationships);
    }

    public function toArray(): array
    {
        $result = [
            "type" => $this->type,
            "id" => $this->id,
        ];

        if (empty($this->meta) === false) {
            $result["meta"] = $this->meta;
        }

        if ($this->links->hasAnyLinks()) {
            $result["links"] = $this->links->toArray();
        }

        if (empty($this->attributes) === false) {
            $result["attributes"] = $this->attributes;
        }

        if (empty($this->relationships) === false) {
            $result["relationships"] = [];
            foreach ($this->relationships as $name => $relationship) {
                $result["relationships"][$name] = $relationship->toArray();
            }
        }

        return $result;
    }

    private static function isArrayKey(array $array, string $key): bool
    {
        return isset($array[$key]) && is_array($array[$key]);
    }
}
