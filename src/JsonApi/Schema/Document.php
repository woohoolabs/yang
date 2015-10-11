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
     * @var \WoohooLabs\Yang\JsonApi\Schema\Data
     */
    protected $data;

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

        $dataObject = Data::createFromArray($data);

        $errors = [];
        if (isset($document["errors"]) && is_array($document["errors"])) {
            foreach ($document["errors"] as $error) {
                if (is_array($error)) {
                    $errors[] = Error::createFromArray($error);
                }
            }
        }

        return new self($jsonApiObject, $meta, $linksObject, $dataObject, $errors);
    }

    /**
     * @param \WoohooLabs\Yang\JsonApi\Schema\JsonApi $jsonApi
     * @param array $meta
     * @param \WoohooLabs\Yang\JsonApi\Schema\Links $links
     * @param \WoohooLabs\Yang\JsonApi\Schema\Data $data
     * @param \WoohooLabs\Yang\JsonApi\Schema\Error[] $errors
     */
    public function __construct(JsonApi $jsonApi, array $meta, Links $links, Data $data, array $errors)
    {
        $this->jsonApi = $jsonApi;
        $this->meta = $meta;
        $this->links = $links;
        $this->data = $data;
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

        if ($this->hasData()) {
        }

        if ($this->hasErrors()) {
            $errors = [];
            foreach ($this->errors as $error) {
                $errors = $error->toArray();
            }
            $content["errors"] = $errors;
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
    public function getJsonApi()
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
    public function getMeta()
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
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * @return bool
     */
    public function hasData()
    {
        return $this->data->hasPrimaryResources();
    }

    /**
     * @return \WoohooLabs\Yang\JsonApi\Schema\Data
     */
    public function getData()
    {
        return $this->data;
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
