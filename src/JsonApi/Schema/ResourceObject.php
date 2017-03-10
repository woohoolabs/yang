<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\JsonApi\Schema;

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
    private $meta;

    /**
     * @var Links
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

    public static function createFromArray(array $array, ResourceObjects $resources): ResourceObject
    {
        $type = isset($array["type"]) && is_string($array["type"]) ? $array["type"] : "";
        $id = isset($array["id"]) && is_string($array["id"]) ? $array["id"] : "";
        $meta = self::isArrayKey($array, "meta") ? $array["meta"] : [];
        $links = Links::createFromArray(self::isArrayKey($array, "links") ? $array["links"] : []);
        $attributes = self::isArrayKey($array, "attributes") ? $array["attributes"] : [];

        $relationships = [];
        if (self::isArrayKey($array, "relationships")) {
            foreach ($array["relationships"] as $name => $relationship) {
                if (is_string($name) === false || is_array($relationship) === false) {
                    continue;
                }

                $relationships[$name] = Relationship::createFromArray($name, $relationship, $resources);
            }
        }

        return new self($type, $id, $meta, $links, $attributes, $relationships);
    }

    /**
     * @param Relationship[] $relationships
     */
    public function __construct(
        string $type,
        string $id,
        array $meta,
        Links $links,
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

    public function toArray(): array
    {
        $result = [
            "type" => $this->type,
            "id" => $this->id
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

    public function links(): Links
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
     * @return mixed|null
     */
    public function attribute(string $name)
    {
        return $this->hasAttribute($name) ? $this->attributes[$name] : null;
    }

    public function hasRelationship(string $name): bool
    {
        return array_key_exists($name, $this->relationships);
    }

    /**
     * @return Relationship|null
     */
    public function relationship(string $name)
    {
        return $this->hasRelationship($name) ? $this->relationships[$name] : null;
    }

    private static function isArrayKey(array $array, string $key): bool
    {
        return isset($array[$key]) && is_array($array[$key]);
    }
}
