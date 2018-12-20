<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\JsonApi\Schema;

use WoohooLabs\Yang\JsonApi\Exception\DocumentException;
use WoohooLabs\Yang\JsonApi\Schema\Error\Error;
use WoohooLabs\Yang\JsonApi\Schema\Link\DocumentLinks;
use WoohooLabs\Yang\JsonApi\Schema\Resource\ResourceObject;
use WoohooLabs\Yang\JsonApi\Schema\Resource\ResourceObjects;
use function array_filter;
use function array_keys;
use function count;
use function is_array;

final class Document
{
    /**
     * @var JsonApiObject
     */
    private $jsonApi;

    /**
     * @var array
     */
    private $meta;

    /**
     * @var DocumentLinks
     */
    private $links;

    /**
     * @var ResourceObjects
     */
    private $resources;

    /**
     * @var Error[]
     */
    private $errors;

    /**
     * @param Error[] $errors
     */
    public function __construct(JsonApiObject $jsonApi, array $meta, DocumentLinks $links, ResourceObjects $resources, array $errors)
    {
        $this->jsonApi = $jsonApi;
        $this->meta = $meta;
        $this->links = $links;
        $this->resources = $resources;
        $this->errors = $errors;
    }

    public function jsonApi(): JsonApiObject
    {
        return $this->jsonApi;
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

    public function links(): DocumentLinks
    {
        return $this->links;
    }

    public function isSingleResourceDocument(): bool
    {
        return $this->resources->isSinglePrimaryResource();
    }

    public function isResourceCollectionDocument(): bool
    {
        return $this->resources->isPrimaryResourceCollection();
    }

    public function hasAnyPrimaryResources(): bool
    {
        return $this->resources->hasAnyPrimaryResources();
    }

    public function primaryResource(): ResourceObject
    {
        return $this->resources->primaryResource();
    }

    /**
     * @return ResourceObject[]
     */
    public function primaryResources(): array
    {
        return $this->resources->primaryResources();
    }

    /**
     * @throws DocumentException
     */
    public function resource(string $type, string $id): ResourceObject
    {
        return $this->resources->resource($type, $id);
    }

    public function hasAnyIncludedResources(): bool
    {
        return $this->resources->hasAnyIncludedResources();
    }

    public function hasIncludedResource(string $type, string $id): bool
    {
        return $this->resources->hasIncludedResource($type, $id);
    }

    /**
     * @return ResourceObject[]
     */
    public function includedResources(): array
    {
        return $this->resources->includedResources();
    }

    public function hasErrors(): bool
    {
        return empty($this->errors) === false;
    }

    /**
     * @return Error[]
     */
    public function errors(): array
    {
        if (empty($this->errors)) {
            return [];
        }

        return $this->errors;
    }

    public function errorCount(): int
    {
        return count($this->errors);
    }

    /**
     * @throws DocumentException
     */
    public function error(int $index): Error
    {
        if (isset($this->errors[$index]) === false) {
            throw new DocumentException("The document doesn't contain error with the '$index' index!");
        }

        return $this->errors[$index];
    }

    /**
     * @internal
     */
    public static function fromArray(array $document): Document
    {
        if (isset($document["jsonapi"]) && is_array($document["jsonapi"])) {
            $jsonApi = $document["jsonapi"];
        } else {
            $jsonApi = [];
        }
        $jsonApiObject = JsonApiObject::fromArray($jsonApi);

        if (isset($document["meta"]) && is_array($document["meta"])) {
            $meta = $document["meta"];
        } else {
            $meta = [];
        }

        if (isset($document["links"]) && is_array($document["links"])) {
            $links = $document["links"];
        } else {
            $links = [];
        }
        $linksObject = DocumentLinks::fromArray($links);

        if (isset($document["included"]) && is_array($document["included"])) {
            $included = $document["included"];
        } else {
            $included = [];
        }

        if (isset($document["data"]) && is_array($document["data"])) {
            $resources = new ResourceObjects($document["data"], $included, self::isAssociativeArray($document["data"]));
        } else {
            $resources = ResourceObjects::fromSinglePrimaryData([], $included);
        }

        $errors = [];
        if (isset($document["errors"]) && is_array($document["errors"])) {
            foreach ($document["errors"] as $error) {
                if (is_array($error)) {
                    $errors[] = Error::fromArray($error);
                }
            }
        }

        return new self($jsonApiObject, $meta, $linksObject, $resources, $errors);
    }

    public function toArray(): array
    {
        $content = [
            "jsonapi" => $this->jsonApi->toArray(),
        ];

        if ($this->hasMeta()) {
            $content["meta"] = $this->meta;
        }

        if ($this->hasLinks()) {
            $content["links"] = $this->links->toArray();
        }

        if ($this->hasAnyPrimaryResources()) {
            $content["data"] = $this->resources->primaryDataToArray();
        }

        if ($this->hasErrors()) {
            $errors = [];
            foreach ($this->errors as $error) {
                $errors[] = $error->toArray();
            }
            $content["errors"] = $errors;
        }

        if ($this->resources->hasAnyIncludedResources()) {
            $content["included"] = $this->resources->includedToArray();
        }

        return $content;
    }

    private static function isAssociativeArray(array $array): bool
    {
        return (bool) count(array_filter(array_keys($array), "is_string"));
    }
}
