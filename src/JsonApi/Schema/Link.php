<?php
namespace WoohooLabs\Yang\JsonApi\Schema;

class Link
{
    /**
     * @var string
     */
    private $href;

    /**
     * @var array
     */
    private $meta;

    /**
     * @param string $link
     * @return $this
     */
    public static function createFromString($link)
    {
        return new self($link);
    }

    /**
     * @param array $link
     * @return $this
     */
    public static function createFromArray(array $link)
    {
        $href = empty($link["href"]) ? "" : $link["href"];
        $meta = empty($link["meta"]) ? [] : $link["meta"];

        return new self($href, $meta);
    }

    /**
     * @param string $href
     * @param array $meta
     */
    public function __construct($href, array $meta = [])
    {
        $this->href = $href;
        $this->meta = $meta;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $link = ["href" => $this->href];

        if (empty($this->meta) === false) {
            $link["meta"] = $this->meta;
        }

        return $link;
    }

    /**
     * @return string
     */
    public function href()
    {
        return $this->href;
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
