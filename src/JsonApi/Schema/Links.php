<?php
namespace WoohooLabs\Yang\JsonApi\Schema;

class Links
{
    /**
     * @var \WoohooLabs\Yang\JsonApi\Schema\Link[]
     */
    protected $links;

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
        return $this->getLink("self") !== null;
    }

    /**
     * @return \WoohooLabs\Yang\JsonApi\Schema\Link|null
     */
    public function getSelf()
    {
        return $this->getLink("self");
    }

    /**
     * @return bool
     */
    public function hasRelated()
    {
        return $this->getLink("related") !== null;
    }

    /**
     * @return \WoohooLabs\Yang\JsonApi\Schema\Link|null
     */
    public function getRelated()
    {
        return $this->getLink("related");
    }

    /**
     * @return bool
     */
    public function hasFirst()
    {
        return $this->getLink("first") !== null;
    }

    /**
     * @return \WoohooLabs\Yang\JsonApi\Schema\Link|null
     */
    public function getFirst()
    {
        return $this->getLink("first");
    }

    /**
     * @return bool
     */
    public function hasLast()
    {
        return $this->getLink("last") !== null;
    }

    /**
     * @return \WoohooLabs\Yang\JsonApi\Schema\Link|null
     */
    public function getLast()
    {
        return $this->getLink("last");
    }

    /**
     * @return bool
     */
    public function hasPrev()
    {
        return $this->getLink("prev") !== null;
    }

    /**
     * @return \WoohooLabs\Yang\JsonApi\Schema\Link|null
     */
    public function getPrev()
    {
        return $this->getLink("prev");
    }

    /**
     * @return bool
     */
    public function hasNext()
    {
        return $this->getLink("next") !== null;
    }

    /**
     * @return \WoohooLabs\Yang\JsonApi\Schema\Link|null
     */
    public function getNext()
    {
        return $this->getLink("next");
    }

    /**
     * @param $name
     * @return bool
     */
    public function hasLink($name)
    {
        return $this->hasLink($name) !== null;
    }

    /**
     * @param $name
     * @return \WoohooLabs\Yang\JsonApi\Schema\Link[] $links
     */
    public function getLink($name)
    {
        return isset($this->links[$name]) ? $this->links[$name] : null;
    }

    /**
     * @return bool
     */
    public function hasLinks()
    {
        return empty($this->links) === false;
    }

    /**
     * @return \WoohooLabs\Yang\JsonApi\Schema\Link[]
     */
    public function getLinks()
    {
        return $this->links;
    }
}
