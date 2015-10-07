<?php
namespace WoohooLabs\Yang\JsonApi\Schema;

class Document
{
    /**
     * @var array
     */
    protected $document;

    /**
     * @param array $document
     */
    public function __construct(array $document)
    {
        $this->document = $document;
    }

    public function hasJsonApi()
    {
        return empty($this->document["jsonApi"]) === false;
    }

    public function hasMeta()
    {
        return empty($this->document["meta"]) === false;
    }

    public function hasLinks()
    {
        return empty($this->document["meta"]) === false;
    }

    public function hasData()
    {
        return empty($this->document["data"]) === false;
    }

    /**
     * @return bool
     */
    public function hasError()
    {
        return empty($this->document["errors"]) === false;
    }
}
