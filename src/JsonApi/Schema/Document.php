<?php
namespace WoohooLabs\Yang\JsonApi\Schema;

class Document
{
    /**
     * @var \WoohooLabs\Yang\JsonApi\Schema\JsonApi
     */
    private $jsonApi;

    /**
     * @var array
     */
    private $meta;

    /**
     * @var \WoohooLabs\Yang\JsonApi\Schema\Links
     */
    private $links;

    /**
     * @var \WoohooLabs\Yang\JsonApi\Schema\ResourceObjects
     */
    private $resources;

    /**
     * @var \WoohooLabs\Yang\JsonApi\Schema\Error[]
     */
    private $errors;

    /**
     * @param array $document
     * @return $this
     */
    public static function createFromArray(array $document)
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
     * @param \WoohooLabs\Yang\JsonApi\Schema\JsonApi $jsonApi
     * @param array $meta
     * @param \WoohooLabs\Yang\JsonApi\Schema\Links $links
     * @param \WoohooLabs\Yang\JsonApi\Schema\ResourceObjects $resources
     * @param \WoohooLabs\Yang\JsonApi\Schema\Error[] $errors
     */
    public function __construct(JsonApi $jsonApi, array $meta, Links $links, ResourceObjects $resources, array $errors)
    {
        $this->jsonApi = $jsonApi;
        $this->meta = $meta;
        $this->links = $links;
        $this->resources = $resources;
        $this->errors = $errors;
    }

    /**
     * @return array
     */
    public function toArray()
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

    /**
     * @return \WoohooLabs\Yang\JsonApi\Schema\JsonApi
     */
    public function jsonApi()
    {
        return $this->jsonApi;
    }

    /**
     * @return bool
     */
    public function hasMeta()
    {
        return empty($this->meta) === false;
    }

    /**
     * @return array
     */
    public function meta()
    {
        return $this->meta;
    }

    /**
     * @return bool
     */
    public function hasLinks()
    {
        return $this->links->hasAnyLinks();
    }

    /**
     * @return \WoohooLabs\Yang\JsonApi\Schema\Links
     */
    public function links()
    {
        return $this->links;
    }

    /**
     * @return bool
     */
    public function isSingleResourceDocument()
    {
        return $this->resources->isSinglePrimaryResource() === true;
    }

    /**
     * @return bool
     */
    public function isResourceCollectionDocument()
    {
        return $this->resources->isPrimaryResourceCollection() === true;
    }

    /**
     * @return bool
     */
    public function hasAnyPrimaryResources()
    {
        return $this->resources->hasAnyPrimaryResources();
    }

    /**
     * @return \WoohooLabs\Yang\JsonApi\Schema\ResourceObject|null
     */
    public function primaryResource()
    {
        return $this->resources->primaryResource();
    }

    /**
     * @return \WoohooLabs\Yang\JsonApi\Schema\ResourceObject[]
     */
    public function primaryResources()
    {
        return $this->resources->primaryResources();
    }

    /**
     * @param string $type
     * @param string $id
     * @return \WoohooLabs\Yang\JsonApi\Schema\ResourceObject|null
     */
    public function resource($type, $id)
    {
        return $this->resources->resource($type, $id);
    }

    /**
     * @return bool
     */
    public function hasAnyIncludedResources()
    {
        return $this->resources->hasAnyIncludedResources();
    }

    /**
     * @param string $type
     * @param string $id
     * @return bool
     */
    public function hasIncludedResource($type, $id)
    {
        return $this->resources->hasIncludedResource($type, $id);
    }

    /**
     * @return \WoohooLabs\Yang\JsonApi\Schema\ResourceObject[]
     */
    public function includedResources()
    {
        return $this->resources->includedResources();
    }

    /**
     * @return bool
     */
    public function hasErrors()
    {
        return empty($this->errors) === false;
    }

    /**
     * @return \WoohooLabs\Yang\JsonApi\Schema\Error[]
     */
    public function errors()
    {
        return $this->errors;
    }

    /**
     * @param int $number
     * @return \WoohooLabs\Yang\JsonApi\Schema\Error|null
     */
    public function error($number)
    {
        return isset($this->errors[$number]) ? $this->errors[$number] : null;
    }

    /**
     * @param array $array
     * @return bool
     */
    private static function isAssociativeArray(array $array)
    {
        return (bool)count(array_filter(array_keys($array), 'is_string'));
    }
}
