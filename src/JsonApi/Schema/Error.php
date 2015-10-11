<?php
namespace WoohooLabs\Yang\JsonApi\Schema;

class Error
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var array
     */
    protected $meta;

    /**
     * @var \WoohooLabs\Yang\JsonApi\Schema\Links
     */
    protected $links;

    /**
     * @var int
     */
    protected $status;

    /**
     * @var string
     */
    protected $code;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $detail;

    /**
     * @var \WoohooLabs\Yang\JsonApi\Schema\ErrorSource
     */
    protected $source;

    /**
     * @param array $error
     * @return $this
     */
    public static function createFromArray(array $error)
    {
        $id = empty($error["id"]) === false ? $error["id"] : "";
        $meta = isset($error["meta"]) && is_array($error["meta"]) ? $error["meta"] : [];
        $links = Links::createFromArray(isset($error["links"]) && is_array($error["links"]) ? $error["links"] : []);
        $status = empty($error["status"]) === false ? $error["status"] : "";
        $code = empty($error["code"]) === false ? $error["code"] : "";
        $title = empty($error["title"]) === false ? $error["title"] : "";
        $detail = empty($error["detail"]) === false ? $error["detail"] : "";
        $source = ErrorSource::fromArray(isset($error["source"]) && is_array($error["source"]) ? $error["source"] : "");

        return new self($id, $meta, $links, $status, $code, $title, $detail, $source);
    }

    /**
     * @param string $id
     * @param array $meta
     * @param \WoohooLabs\Yang\JsonApi\Schema\Links $links
     * @param string $status
     * @param string $code
     * @param string $title
     * @param string $detail
     * @param \WoohooLabs\Yang\JsonApi\Schema\ErrorSource $source
     */
    public function __construct($id, array $meta, Links $links, $status, $code, $title, $detail, ErrorSource $source)
    {
        $this->id = $id;
        $this->meta = $meta;
        $this->links = $links;
        $this->status = $status;
        $this->code = $code;
        $this->title = $title;
        $this->detail = $detail;
        $this->source = $source;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $content = [];

        if ($this->id) {
            $content["id"] = $this->id;
        }

        if (empty($this->meta) === false) {
            $content["meta"] = $this->meta;
        }

        if ($this->hasLinks()) {
            $content["links"] = $this->links->toArray();
        }

        if ($this->status) {
            $content["status"] = $this->status;
        }

        if ($this->code) {
            $content["code"] = $this->code;
        }

        if ($this->title) {
            $content["title"] = $this->title;
        }

        if ($this->detail) {
            $content["detail"] = $this->detail;
        }

        if ($this->hasSource()) {
            $content["source"] = $this->source->toArray();
        }

        return $content;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
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
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getDetail()
    {
        return $this->detail;
    }

    /**
     * @return bool
     */
    public function hasSource()
    {
        return $this->source->hasSource();
    }

    /**
     * @return \WoohooLabs\Yang\JsonApi\Schema\ErrorSource
     */
    public function getSource()
    {
        return $this->source;
    }
}
