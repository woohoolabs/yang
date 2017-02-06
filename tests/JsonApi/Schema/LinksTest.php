<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\Tests\JsonApi\Schema;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yang\JsonApi\Schema\Link;
use WoohooLabs\Yang\JsonApi\Schema\Links;

class LinksTest extends TestCase
{
    /**
     * @test
     */
    public function createFromArray()
    {
        $links = Links::createFromArray(
            [
                "self" => "",
                "related" => [],
            ]
        );

        $this->assertArrayHasKey("self", $links->links());
        $this->assertArrayHasKey("related", $links->links());
    }

    /**
     * @test
     */
    public function toArray()
    {
        $links = Links::createFromArray(
            [
                "self" => "",
                "related" => [],
            ]
        );

        $this->assertArrayHasKey("self", $links->toArray());
        $this->assertArrayHasKey("related", $links->toArray());
    }

    /**
     * @test
     */
    public function hasSelfIsTrue()
    {
        $links = Links::createFromArray(
            [
                "self" => ""
            ]
        );

        $this->assertTrue($links->hasSelf());
    }

    /**
     * @test
     */
    public function hasSelfIsFalse()
    {
        $links = Links::createFromArray([]);

        $this->assertFalse($links->hasSelf());
    }

    /**
     * @test
     */
    public function selfReturnsObject()
    {
        $links = Links::createFromArray(
            [
                "self" => [],
            ]
        );

        $this->assertInstanceOf(Link::class, $links->self());
    }

    /**
     * @test
     */
    public function selfReturnsNull()
    {
        $links = Links::createFromArray([]);

        $this->assertNull($links->self());
    }

    /**
     * @test
     */
    public function hasRelatedIsTrue()
    {
        $links = Links::createFromArray(
            [
                "related" => ""
            ]
        );

        $this->assertTrue($links->hasRelated());
    }

    /**
     * @test
     */
    public function relatedReturnsObject()
    {
        $links = Links::createFromArray(
            [
                "related" => [],
            ]
        );

        $this->assertInstanceOf(Link::class, $links->related());
    }

    /**
     * @test
     */
    public function relatedReturnsNull()
    {
        $links = Links::createFromArray([]);

        $this->assertNull($links->related());
    }

    /**
     * @test
     */
    public function hasRelatedIsFalse()
    {
        $links = Links::createFromArray([]);

        $this->assertFalse($links->hasRelated());
    }

    /**
     * @test
     */
    public function hasFirstIsTrue()
    {
        $links = Links::createFromArray(
            [
                "first" => ""
            ]
        );

        $this->assertTrue($links->hasFirst());
    }

    /**
     * @test
     */
    public function hasFirstIsFalse()
    {
        $links = Links::createFromArray([]);

        $this->assertFalse($links->hasFirst());
    }

    /**
     * @test
     */
    public function firstReturnsObject()
    {
        $links = Links::createFromArray(
            [
                "first" => [],
            ]
        );

        $this->assertInstanceOf(Link::class, $links->first());
    }

    /**
     * @test
     */
    public function firstReturnsNull()
    {
        $links = Links::createFromArray([]);

        $this->assertNull($links->first());
    }

    /**
     * @test
     */
    public function hasLastIsTrue()
    {
        $links = Links::createFromArray(
            [
                "last" => ""
            ]
        );

        $this->assertTrue($links->hasLast());
    }

    /**
     * @test
     */
    public function hasLastIsFalse()
    {
        $links = Links::createFromArray([]);

        $this->assertFalse($links->hasLast());
    }

    /**
     * @test
     */
    public function lastReturnsObject()
    {
        $links = Links::createFromArray(
            [
                "last" => [],
            ]
        );

        $this->assertInstanceOf(Link::class, $links->last());
    }

    /**
     * @test
     */
    public function lastReturnsNull()
    {
        $links = Links::createFromArray([]);

        $this->assertNull($links->last());
    }

    /**
     * @test
     */
    public function hasPrevIsTrue()
    {
        $links = Links::createFromArray(
            [
                "prev" => ""
            ]
        );

        $this->assertTrue($links->hasPrev());
    }

    /**
     * @test
     */
    public function hasPrevIsFalse()
    {
        $links = Links::createFromArray([]);

        $this->assertFalse($links->hasPrev());
    }

    /**
     * @test
     */
    public function prevReturnsObject()
    {
        $links = Links::createFromArray(
            [
                "prev" => [],
            ]
        );

        $this->assertInstanceOf(Link::class, $links->prev());
    }

    /**
     * @test
     */
    public function prevReturnsNull()
    {
        $links = Links::createFromArray([]);

        $this->assertNull($links->prev());
    }

    /**
     * @test
     */
    public function hasNextIsTrue()
    {
        $links = Links::createFromArray(
            [
                "next" => ""
            ]
        );

        $this->assertTrue($links->hasNext());
    }

    /**
     * @test
     */
    public function hasNextIsFalse()
    {
        $links = Links::createFromArray([]);

        $this->assertFalse($links->hasNext());
    }

    /**
     * @test
     */
    public function nextReturnsObject()
    {
        $links = Links::createFromArray(
            [
                "next" => [],
            ]
        );

        $this->assertInstanceOf(Link::class, $links->next());
    }

    /**
     * @test
     */
    public function nextReturnsNull()
    {
        $links = Links::createFromArray([]);

        $this->assertNull($links->next());
    }

    /**
     * @test
     */
    public function hasAboutIsTrue()
    {
        $links = Links::createFromArray(
            [
                "about" => ""
            ]
        );

        $this->assertTrue($links->hasAbout());
    }

    /**
     * @test
     */
    public function hasAboutIsFalse()
    {
        $links = Links::createFromArray([]);

        $this->assertFalse($links->hasAbout());
    }

    /**
     * @test
     */
    public function aboutReturnsObject()
    {
        $links = Links::createFromArray(
            [
                "about" => [],
            ]
        );

        $this->assertInstanceOf(Link::class, $links->about());
    }

    /**
     * @test
     */
    public function aboutReturnsNull()
    {
        $links = Links::createFromArray([]);

        $this->assertNull($links->about());
    }

    /**
     * @test
     */
    public function hasLinkIsTrue()
    {
        $links = Links::createFromArray(
            [
                "link" => ""
            ]
        );

        $this->assertTrue($links->hasLink("link"));
    }

    /**
     * @test
     */
    public function hasLinkIsFalse()
    {
        $links = Links::createFromArray([]);

        $this->assertFalse($links->hasLink("link"));
    }

    /**
     * @test
     */
    public function linkReturnsObject()
    {
        $links = Links::createFromArray(
            [
                "link" => [],
            ]
        );

        $this->assertInstanceOf(Link::class, $links->link("link"));
    }

    /**
     * @test
     */
    public function linkReturnsNull()
    {
        $links = Links::createFromArray([]);

        $this->assertNull($links->link("link"));
    }

    /**
     * @test
     */
    public function hasAnyLinksIsTrue()
    {
        $links = Links::createFromArray(
            [
                "self" => "",
            ]
        );

        $this->assertTrue($links->hasAnyLinks());
    }

    /**
     * @test
     */
    public function hasAnyLinksIsFalse()
    {
        $links = Links::createFromArray([]);

        $this->assertFalse($links->hasAnyLinks());
    }
}
