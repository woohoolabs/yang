<?php
namespace WoohooLabs\Yang\JsonApi\Schema;

class JsonApi
{
    /**
     * @var string
     */
    protected $version;

    /**
     * @var array
     */
    protected $meta;

    /**
     * @param array $array
     * @return $this
     */
    public static function createFromArray($array)
    {
        $version = empty($array["version"]) ? "" : $array["version"];
        $meta = isset($array["meta"]) && is_array($array["meta"]) ? $array["meta"] : [];

        return new self($version, $meta);
    }

    /**
     * @param string $version
     * @param array $meta
     */
    public function __construct($version, array $meta)
    {
        $this->version = $version;
        $this->meta = $meta;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $result = [];

        if ($this->version) {
            $result["version"] = $this->version;
        }

        if (empty($this->meta) === false) {
            $result["meta"] = $this->meta;
        }

        return $result;
    }

    /**
     * @return bool
     */
    public function hasJsonApi()
    {
        return $this->hasVersion() || $this->hasMeta();
    }

    /**
     * @return bool
     */
    public function hasVersion()
    {
        return empty($this->version) === false;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
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
}
