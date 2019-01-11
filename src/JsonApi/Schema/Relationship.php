<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\JsonApi\Schema;

use WoohooLabs\Yang\JsonApi\Exception\DocumentException;
use WoohooLabs\Yang\JsonApi\Schema\Link\RelationshipLinks;
use WoohooLabs\Yang\JsonApi\Schema\Resource\ResourceObject;
use WoohooLabs\Yang\JsonApi\Schema\Resource\ResourceObjects;
use function array_filter;
use function array_key_exists;
use function array_keys;
use function count;
use function is_array;
use function is_numeric;
use function reset;

final class Relationship
{
    /**
     * @var bool|null
     */
    private $isToOneRelationship;

    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $meta;

    /**
     * @var RelationshipLinks
     */
    private $links;

    /**
     * @var array
     */
    private $resourceMap = [];

    /**
     * @var ResourceObjects
     */
    private $resources;

    public function __construct(
        string $name,
        array $meta,
        RelationshipLinks $links,
        array $resourceMap,
        ResourceObjects $resources,
        ?bool $isToOneRelationship = null
    ) {
        $this->name = $name;
        $this->meta = $meta;
        $this->links = $links;
        $this->resourceMap = $resourceMap;
        $this->isToOneRelationship = $isToOneRelationship;
        $this->resources = $resources;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function isToOneRelationship(): bool
    {
        return $this->isToOneRelationship === true;
    }

    public function isToManyRelationship(): bool
    {
        return $this->isToOneRelationship === false;
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

    public function links(): RelationshipLinks
    {
        return $this->links;
    }

    public function resourceLinks(): array
    {
        return $this->resourceMap;
    }

    public function firstResourceLink(): ?array
    {
        $link = reset($this->resourceMap);

        return $link === false ? null : $link;
    }

    public function hasIncludedResource(string $type, string $id): bool
    {
        return $this->resources->hasIncludedResource($type, $id);
    }

    /**
     * @return ResourceObject[]
     * @throws DocumentException
     */
    public function resources(): array
    {
        if ($this->isToOneRelationship === true) {
            throw new DocumentException(
                "The relationship with '$this->name' name is a to-one relationship, therefore it doesn't have multiple resources. " .
                "Use the 'Relationship::resource()' method instead."
            );
        }

        $resources = [];
        foreach ($this->resourceMap as $resourceLink) {
            if ($this->hasIncludedResource($resourceLink["type"], $resourceLink["id"])) {
                $resources[] = $this->resourceBy($resourceLink["type"], $resourceLink["id"]);
            }
        }

        return $resources;
    }

    /**
     * @return ResourceObject[][]
     */
    public function resourceMap(): array
    {
        $resources = [];
        foreach ($this->resourceMap as $resourceLink) {
            $type = $resourceLink["type"];
            $id = $resourceLink["id"];
            if ($this->hasIncludedResource($type, $id)) {
                $resources[$type][$id] = $this->resourceBy($type, $id);
            }
        }

        return $resources;
    }

    /**
     * @throws DocumentException
     */
    public function resource(): ResourceObject
    {
        if ($this->isToOneRelationship === false) {
            throw new DocumentException(
                "The relationship with '$this->name' name is a to-many relationship, therefore it doesn't have a single resource. " .
                "Use the 'Relationship::resources()' method instead."
            );
        }

        $resource = reset($this->resourceMap);
        if ($resource === false) {
            throw new DocumentException("The relationship with '$this->name' name is empty, therefore it doesn't have a single resource.");
        }

        return $this->resourceBy($resource["type"], $resource["id"]);
    }

    /**
     * @throws DocumentException
     */
    public function resourceBy(string $type, string $id): ResourceObject
    {
        return $this->resources->resource($type, $id);
    }

    /**
     * Get meta information that may be defined next to the resource identifier link.
     * This occurs when a relationship contains additional data besides the relation's identifiers.
     */
    public function resourceLinkMeta(string $type, string $id): array
    {
        foreach ($this->resourceMap as $resourceLink) {
            if (isset($resourceLink["meta"]) && $resourceLink["type"] === $type && $resourceLink["id"] === $id) {
                return $resourceLink["meta"];
            }
        }

        return [];
    }

    /**
     * @internal
     */
    public static function fromArray(string $name, array $array, ResourceObjects $resources): Relationship
    {
        $meta = self::isArrayKey($array, "meta") ? $array["meta"] : [];
        $links = RelationshipLinks::fromArray(self::isArrayKey($array, "links") ? $array["links"] : []);

        // Data member is missing
        if (array_key_exists("data", $array) === false) {
            return self::createEmptyFromArray($name, $meta, $links, $resources, null);
        }

        // Relationship is empty To-One
        if ($array["data"] === null) {
            return self::createEmptyFromArray($name, $meta, $links, $resources, true);
        }

        // Relationship is To-One
        if (self::isAssociativeArray($array["data"])) {
            return self::createToOneFromArray($name, $meta, $links, $array["data"], $resources);
        }

        // Relationship is To-Many
        return self::createToManyFromArray($name, $meta, $links, $array["data"], $resources);
    }

    public function toArray(): array
    {
        $result = [];

        if (empty($this->meta) === false) {
            $result["meta"] = $this->meta;
        }

        if ($this->links->hasAnyLinks()) {
            $result["links"] = $this->links->toArray();
        }

        if ($this->isToOneRelationship === null) {
            return $result;
        }

        if (empty($this->resourceMap)) {
            $result["data"] = $this->isToOneRelationship ? null : [];
        } else {
            $result["data"] = $this->isToOneRelationship ? reset($this->resourceMap) : $this->resourceMap;
        }

        return $result;
    }

    private static function createEmptyFromArray(
        string $name,
        array $meta,
        RelationshipLinks $links,
        ResourceObjects $resources,
        ?bool $isToOneRelationship
    ): Relationship {
        return new Relationship($name, $meta, $links, [], $resources, $isToOneRelationship);
    }

    private static function createToOneFromArray(
        string $name,
        array $meta,
        RelationshipLinks $links,
        array $data,
        ResourceObjects $resources
    ): Relationship {
        $resourceMap = [];
        $isToOneRelationship = true;

        if (self::isBlankKey($data, "type") === false && self::isBlankKey($data, "id") === false) {
            $resourceMap = [
                [
                    "type" => $data["type"],
                    "id" => $data["id"],
                ],
            ];
            if (empty($data["meta"]) === false) {
                $resourceMap[0]["meta"] = $data["meta"];
            }
        }

        return new Relationship($name, $meta, $links, $resourceMap, $resources, $isToOneRelationship);
    }

    private static function createToManyFromArray(
        string $name,
        array $meta,
        RelationshipLinks $links,
        array $data,
        ResourceObjects $resources
    ): Relationship {
        $isToOneRelationship = false;
        $resourceMap = [];

        foreach ($data as $item) {
            if (self::isBlankKey($item, "type") === false && self::isBlankKey($item, "id") === false) {
                $resource = [
                    "type" => $item["type"],
                    "id" => $item["id"],
                ];
                if (empty($item["meta"]) === false) {
                    $resource["meta"] = $item["meta"];
                }
                $resourceMap[] = $resource;
            }
        }

        return new Relationship($name, $meta, $links, $resourceMap, $resources, $isToOneRelationship);
    }

    private static function isAssociativeArray(array $array): bool
    {
        return (bool) count(array_filter(array_keys($array), "is_string"));
    }

    private static function isArrayKey(array $array, string $key): bool
    {
        return isset($array[$key]) && is_array($array[$key]);
    }

    private static function isBlankKey(array $array, string $key): bool
    {
        return array_key_exists($key, $array) === false || (empty($array[$key]) && is_numeric($array[$key]) === false);
    }
}
