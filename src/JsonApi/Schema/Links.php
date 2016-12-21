<?php
namespace WoohooLabs\Yang\JsonApi\Schema;

class Links
{
    /**
     * @var \WoohooLabs\Yang\JsonApi\Schema\Link[]
     */
    private $links;

    /**
     * @param array $links
     * @return $this
     */
    public static function createFromArray(array $links)
    {
        $linkObjects = [];
        foreach ($links as $name => $value) {
            if (is_string($value)) {
                $linkObjects[$name] = Link::createFromString($value);
            } elseif (is_array($value)) {
                $linkObjects[$name] = Link::createFromArray($value);
            }
        }

        return new self($linkObjects);
    }

    /**
     * @param \WoohooLabs\Yang\JsonApi\Schema\Link[] $links
     */
    public function __construct(array $links)
    {
        $this->links = $links;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $links = [];

        foreach ($this->links as $rel => $link) {
            /** @var \WoohooLabs\Yang\JsonApi\Schema\Link $link */
            $links[$rel] = $link->toArray();
        }

        return $links;
    }

    /**
     * @return bool
     */
    public function hasSelf()
    {
        return $this->link("self") !== null;
    }

    /**
     * @return \WoohooLabs\Yang\JsonApi\Schema\Link|null
     */
    public function self()
    {
        return $this->link("self");
    }

    /**
     * @return bool
     */
    public function hasRelated()
    {
        return $this->link("related") !== null;
    }

    /**
     * @return \WoohooLabs\Yang\JsonApi\Schema\Link|null
     */
    public function related()
    {
        return $this->link("related");
    }

    /**
     * @return bool
     */
    public function hasFirst()
    {
        return $this->link("first") !== null;
    }

    /**
     * @return \WoohooLabs\Yang\JsonApi\Schema\Link|null
     */
    public function first()
    {
        return $this->link("first");
    }

    /**
     * @return bool
     */
    public function hasLast()
    {
        return $this->link("last") !== null;
    }

    /**
     * @return \WoohooLabs\Yang\JsonApi\Schema\Link|null
     */
    public function last()
    {
        return $this->link("last");
    }

    /**
     * @return bool
     */
    public function hasPrev()
    {
        return $this->link("prev") !== null;
    }

    /**
     * @return \WoohooLabs\Yang\JsonApi\Schema\Link|null
     */
    public function prev()
    {
        return $this->link("prev");
    }

    /**
     * @return bool
     */
    public function hasNext()
    {
        return $this->link("next") !== null;
    }

    /**
     * @return \WoohooLabs\Yang\JsonApi\Schema\Link|null
     */
    public function next()
    {
        return $this->link("next");
    }

    /**
     * @return bool
     */
    public function hasAbout()
    {
        return $this->link("about") !== null;
    }

    /**
     * @return \WoohooLabs\Yang\JsonApi\Schema\Link|null
     */
    public function about()
    {
        return $this->link("about");
    }

    /**
     * @param $name
     * @return bool
     */
    public function hasLink($name)
    {
        return isset($this->links[$name]);
    }

    /**
     * @param $name
     * @return \WoohooLabs\Yang\JsonApi\Schema\Link|null
     */
    public function link($name)
    {
        return isset($this->links[$name]) ? $this->links[$name] : null;
    }

    /**
     * @return bool
     */
    public function hasAnyLinks()
    {
        return empty($this->links) === false;
    }

    /**
     * @return \WoohooLabs\Yang\JsonApi\Schema\Link[]
     */
    public function links()
    {
        return $this->links;
    }
}
