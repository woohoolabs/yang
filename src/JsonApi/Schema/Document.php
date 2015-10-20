<?php
namespace WoohooLabs\Yang\JsonApi\Schema;

class Document
{
    /**
     * @var \WoohooLabs\Yang\JsonApi\Schema\JsonApi
     */
    protected $jsonApi;

    /**
     * @var array
     */
    protected $meta;

    /**
     * @var \WoohooLabs\Yang\JsonApi\Schema\Links
     */
    protected $links;

    /**
     * @var \WoohooLabs\Yang\JsonApi\Schema\Resources
     */
    protected $resources;

    /**
     * @var \WoohooLabs\Yang\JsonApi\Schema\Error[]
     */
    protected $errors;

    /**
     * @param array $document
     * @return $this
     */
    public static function createFromArray(array $document)
    {
        if (isset($document["jsonApi"]) && is_array($document["jsonApi"])) {
            $jsonApi = $document["jsonApi"];
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

        if (isset($document["data"]) && is_array($document["data"])) {
            $data = $document["data"];
        } else {
            $data = [];
        }

        if (isset($document["included"]) && is_array($document["included"])) {
            $included = $document["included"];
        } else {
            $included = [];
        }

        $resources = new Resources($data, $included);

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
     * @param \WoohooLabs\Yang\JsonApi\Schema\Resources $resources
     * @param \WoohooLabs\Yang\JsonApi\Schema\Error[] $errors
     */
    public function __construct(JsonApi $jsonApi, array $meta, Links $links, Resources $resources, array $errors)
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
        $content = [];

        if ($this->hasJsonApi()) {
            $content["jsonApi"] = $this->jsonApi->toArray();
        }

        if ($this->hasMeta()) {
            $content["meta"] = $this->meta;
        }

        if ($this->hasLinks()) {
            $content["links"] = $this->links->toArray();
        }

        if ($this->hasPrimaryResources()) {
            $content["data"] = $this->resources->primaryDataToArray();
        }

        if ($this->hasErrors()) {
            $errors = [];
            foreach ($this->errors as $error) {
                $errors = $error->toArray();
            }
            $content["errors"] = $errors;
        }

        if ($this->resources->hasIncludedResources()) {
            $content["included"] = $this->resources->includedToArray();
        }

        return $content;
    }

    /**
     * @return bool
     */
    public function hasJsonApi()
    {
        return $this->jsonApi->hasJsonApi();
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
        return $this->links->hasLinks();
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
    public function hasPrimaryResources()
    {
        return $this->resources->hasPrimaryResources();
    }

    /**
     * @return \WoohooLabs\Yang\JsonApi\Schema\Resource|null
     */
    public function primaryResource()
    {
        return $this->resources->getPrimaryResource();
    }

    /**
     * @return \WoohooLabs\Yang\JsonApi\Schema\Resource[]
     */
    public function primaryResources()
    {
        return $this->resources->getPrimaryResources();
    }

    /**
     * @param string $type
     * @param string $id
     * @return \WoohooLabs\Yang\JsonApi\Schema\Resource|null
     */
    public function resource($type, $id)
    {
        return $this->resources->getResource($type, $id);
    }

    /**
     * @return bool
     */
    public function hasIncludedResources()
    {
        return $this->resources->hasIncludedResources();
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
     * @return \WoohooLabs\Yang\JsonApi\Schema\Resource[]
     */
    public function includedResources()
    {
        return $this->resources->getIncludedResources();
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
    public function getErrors()
    {
        return $this->errors;
    }
}
