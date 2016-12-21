<?php
namespace WoohooLabs\Yang\JsonApi\Schema;

class Error
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var array
     */
    private $meta;

    /**
     * @var \WoohooLabs\Yang\JsonApi\Schema\Links
     */
    private $links;

    /**
     * @var string
     */
    private $status;

    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $detail;

    /**
     * @var \WoohooLabs\Yang\JsonApi\Schema\ErrorSource
     */
    private $source;

    /**
     * @param array $error
     * @return $this
     */
    public static function createFromArray(array $error)
    {
        $id = isset($error["id"]) && is_string($error["id"]) ? $error["id"] : "";
        $meta = isset($error["meta"]) && is_array($error["meta"]) ? $error["meta"] : [];
        $links = Links::createFromArray(isset($error["links"]) && is_array($error["links"]) ? $error["links"] : []);
        $status = isset($error["status"]) && is_scalar($error["status"]) ? (string) $error["status"] : "";
        $code = isset($error["code"]) && is_string($error["code"]) ? $error["code"] : "";
        $title = isset($error["title"]) && is_string($error["title"]) ? $error["title"] : "";
        $detail = isset($error["detail"]) && is_string($error["detail"]) ? $error["detail"] : "";
        $source = ErrorSource::createFromArray(isset($error["source"]) && is_array($error["source"]) ? $error["source"] : []);

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
    public function id()
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
     * @return string
     */
    public function status()
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function code()
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function title()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function detail()
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
    public function source()
    {
        return $this->source;
    }
}
