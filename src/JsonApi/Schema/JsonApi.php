<?php
namespace WoohooLabs\Yang\JsonApi\Schema;

class JsonApi
{
    /**
     * @var string
     */
    private $version;

    /**
     * @var array
     */
    private $meta;

    /**
     * @param array $array
     * @return $this
     */
    public static function createFromArray($array)
    {
        $version = isset($array["version"]) && is_string("version") ? $array["version"] : "1.0";
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
     * @return string
     */
    public function version()
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
    public function meta()
    {
        return $this->meta;
    }
}
