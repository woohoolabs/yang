<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\JsonApi\Schema;

class Document
{
    /**
     * @var JsonApi
     */
    private $jsonApi;

    /**
     * @var array
     */
    private $meta;

    /**
     * @var Links
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

    public static function createFromArray(array $document): Document
    {
        if (isset($document["jsonapi"]) && is_array($document["jsonapi"])) {
            $jsonApi = $document["jsonapi"];
        } else {
            $jsonApi = [];
        }
        $jsonApiObject = JsonApi::createFromArray($jsonApi);

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
        $linksObject = Links::createFromArray($links);

        if (isset($document["included"]) && is_array($document["included"])) {
            $included = $document["included"];
        } else {
            $included = [];
        }

        if (isset($document["data"]) && is_array($document["data"])) {
            $resources = new ResourceObjects($document["data"], $included, self::isAssociativeArray($document["data"]));
        } else {
            $resources = ResourceObjects::createFromSinglePrimaryData([], $included);
        }

        $errors = [];
        if (isset($document["errors"]) && is_array($document["errors"])) {
            foreach ($document["errors"] as $error) {
                if (is_array($error)) {
                    $errors[] = Error::createFromArray($error);
                }
            }
        }

        return new self($jsonApiObject, $meta, $linksObject, $resources, $errors);
    }

    /**
     * @param Error[] $errors
     */
    public function __construct(JsonApi $jsonApi, array $meta, Links $links, ResourceObjects $resources, array $errors)
    {
        $this->jsonApi = $jsonApi;
        $this->meta = $meta;
        $this->links = $links;
        $this->resources = $resources;
        $this->errors = $errors;
    }

    public function toArray(): array
    {
        $content = [
            "jsonapi" => $this->jsonApi->toArray()
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

    public function jsonApi(): JsonApi
    {
        return $this->jsonApi;
    }

    /**
     * @return bool
     */
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

    public function isSingleResourceDocument(): bool
    {
        return $this->resources->isSinglePrimaryResource() === true;
    }

    public function isResourceCollectionDocument(): bool
    {
        return $this->resources->isPrimaryResourceCollection() === true;
    }

    public function hasAnyPrimaryResources(): bool
    {
        return $this->resources->hasAnyPrimaryResources();
    }

    /**
     * @return ResponseResourceObjectInterface|null
     */
    public function primaryResource()
    {
        return $this->resources->primaryResource();
    }

    /**
     * @return ResponseResourceObjectInterface[]
     */
    public function primaryResources(): array
    {
        return $this->resources->primaryResources();
    }

    /**
     * @return ResponseResourceObjectInterface|null
     */
    public function resource(string $type, string $id)
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
     * @return ResponseResourceObjectInterface[]
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
        return $this->errors;
    }

    /**
     * @return Error|null
     */
    public function error(int $number)
    {
        return $this->errors[$number] ?? null;
    }

    private static function isAssociativeArray(array $array): bool
    {
        return (bool)count(array_filter(array_keys($array), 'is_string'));
    }
}
