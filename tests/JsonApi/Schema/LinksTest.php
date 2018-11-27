<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\Tests\JsonApi\Schema;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yang\JsonApi\Exception\LinkException;
use WoohooLabs\Yang\JsonApi\Schema\Link;
use WoohooLabs\Yang\JsonApi\Schema\Links;

class LinksTest extends TestCase
{
    /**
     * @test
     */
    public function createFromArray()
    {
        $links = Links::fromArray(
            [
                "self" => "",
                "related" => [],
            ]
        );

        $linksArray = $links->links();

        $this->assertArrayHasKey("self", $linksArray);
        $this->assertArrayHasKey("related", $linksArray);
    }

    /**
     * @test
     */
    public function toArray()
    {
        $links = Links::fromArray(
            [
                "self" => "",
                "related" => [],
            ]
        );

        $linksArray = $links->toArray();

        $this->assertArrayHasKey("self", $linksArray);
        $this->assertArrayHasKey("related", $linksArray);
    }

    /**
     * @test
     */
    public function hasSelfIsTrue()
    {
        $links = Links::fromArray(
            [
                "self" => "",
            ]
        );

        $hasSelf = $links->hasSelf();

        $this->assertTrue($hasSelf);
    }

    /**
     * @test
     */
    public function hasSelfIsFalse()
    {
        $links = Links::fromArray([]);

        $hasSelf = $links->hasSelf();

        $this->assertFalse($hasSelf);
    }

    /**
     * @test
     */
    public function selfReturnsObject()
    {
        $links = Links::fromArray(
            [
                "self" => [],
            ]
        );

        $self = $links->self();

        $this->assertEquals(new Link(""), $self);
    }

    /**
     * @test
     */
    public function selfWhenMissing()
    {
        $links = Links::fromArray([]);

        $this->expectException(LinkException::class);

        $links->self();
    }

    /**
     * @test
     */
    public function hasRelatedIsTrue()
    {
        $links = Links::fromArray(
            [
                "related" => "",
            ]
        );

        $hasRelated = $links->hasRelated();

        $this->assertTrue($hasRelated);
    }

    /**
     * @test
     */
    public function hasRelatedIsFalse()
    {
        $links = Links::fromArray([]);

        $hasRelated = $links->hasRelated();

        $this->assertFalse($hasRelated);
    }

    /**
     * @test
     */
    public function relatedReturnsObject()
    {
        $links = Links::fromArray(
            [
                "related" => [],
            ]
        );

        $related = $links->related();

        $this->assertEquals(new Link(""), $related);
    }

    /**
     * @test
     */
    public function relatedWhenMissing()
    {
        $links = Links::fromArray([]);

        $this->expectException(LinkException::class);

        $links->related();
    }

    /**
     * @test
     */
    public function hasFirstIsTrue()
    {
        $links = Links::fromArray(
            [
                "first" => "",
            ]
        );

        $hasFirst = $links->hasFirst();

        $this->assertTrue($hasFirst);
    }

    /**
     * @test
     */
    public function hasFirstIsFalse()
    {
        $links = Links::fromArray([]);

        $hasFirst = $links->hasFirst();

        $this->assertFalse($hasFirst);
    }

    /**
     * @test
     */
    public function firstReturnsObject()
    {
        $links = Links::fromArray(
            [
                "first" => [],
            ]
        );

        $first = $links->first();

        $this->assertEquals(new Link(""), $first);
    }

    /**
     * @test
     */
    public function firstWhenMissing()
    {
        $links = Links::fromArray([]);

        $this->expectException(LinkException::class);

        $links->first();
    }

    /**
     * @test
     */
    public function hasLastIsTrue()
    {
        $links = Links::fromArray(
            [
                "last" => "",
            ]
        );

        $hasLast = $links->hasLast();

        $this->assertTrue($hasLast);
    }

    /**
     * @test
     */
    public function hasLastIsFalse()
    {
        $links = Links::fromArray([]);

        $hasLast = $links->hasLast();

        $this->assertFalse($hasLast);
    }

    /**
     * @test
     */
    public function lastReturnsObject()
    {
        $links = Links::fromArray(
            [
                "last" => [],
            ]
        );

        $last = $links->last();

        $this->assertEquals(new Link(""), $last);
    }

    /**
     * @test
     */
    public function lastWhenMissing()
    {
        $links = Links::fromArray([]);

        $this->expectException(LinkException::class);

        $links->last();
    }

    /**
     * @test
     */
    public function hasPrevIsTrue()
    {
        $links = Links::fromArray(
            [
                "prev" => "",
            ]
        );

        $hasPrev = $links->hasPrev();

        $this->assertTrue($hasPrev);
    }

    /**
     * @test
     */
    public function hasPrevIsFalse()
    {
        $links = Links::fromArray([]);

        $hasPrev = $links->hasPrev();

        $this->assertFalse($hasPrev);
    }

    /**
     * @test
     */
    public function prevReturnsObject()
    {
        $links = Links::fromArray(
            [
                "prev" => [],
            ]
        );

        $prev = $links->prev();

        $this->assertEquals(new Link(""), $prev);
    }

    /**
     * @test
     */
    public function prevWhenMissing()
    {
        $links = Links::fromArray([]);

        $this->expectException(LinkException::class);

        $links->prev();
    }

    /**
     * @test
     */
    public function hasNextIsTrue()
    {
        $links = Links::fromArray(
            [
                "next" => "",
            ]
        );

        $this->assertTrue($links->hasNext());
    }

    /**
     * @test
     */
    public function hasNextIsFalse()
    {
        $links = Links::fromArray([]);

        $hasNext = $links->hasNext();

        $this->assertFalse($hasNext);
    }

    /**
     * @test
     */
    public function nextReturnsObject()
    {
        $links = Links::fromArray(
            [
                "next" => [],
            ]
        );

        $next = $links->next();

        $this->assertEquals(new Link(""), $next);
    }

    /**
     * @test
     */
    public function nextReturnsNull()
    {
        $links = Links::fromArray([]);

        $this->expectException(LinkException::class);

        $links->next();
    }

    /**
     * @test
     */
    public function hasAboutIsTrue()
    {
        $links = Links::fromArray(
            [
                "about" => "",
            ]
        );

        $hasAbout = $links->hasAbout();

        $this->assertTrue($hasAbout);
    }

    /**
     * @test
     */
    public function hasAboutIsFalse()
    {
        $links = Links::fromArray([]);

        $hasAbout = $links->hasAbout();

        $this->assertFalse($hasAbout);
    }

    /**
     * @test
     */
    public function aboutReturnsObject()
    {
        $links = Links::fromArray(
            [
                "about" => [],
            ]
        );

        $about = $links->about();

        $this->assertEquals(new Link(""), $about);
    }

    /**
     * @test
     */
    public function aboutWhenMissing()
    {
        $links = Links::fromArray([]);

        $this->expectException(LinkException::class);

        $links->about();
    }

    /**
     * @test
     */
    public function hasLinkIsTrue()
    {
        $links = Links::fromArray(
            [
                "link" => ""
            ]
        );

        $hasLink = $links->hasLink("link");

        $this->assertTrue($hasLink);
    }

    /**
     * @test
     */
    public function hasLinkIsFalse()
    {
        $links = Links::fromArray([]);

        $hasLink = $links->hasLink("link");

        $this->assertFalse($hasLink);
    }

    /**
     * @test
     */
    public function linkReturnsObject()
    {
        $links = Links::fromArray(
            [
                "link" => [],
            ]
        );

        $link = $links->link("link");

        $this->assertEquals(new Link(""), $link);
    }

    /**
     * @test
     */
    public function linkWhenMissing()
    {
        $links = Links::fromArray([]);

        $this->expectException(LinkException::class);

        $links->link("link");
    }

    /**
     * @test
     */
    public function hasAnyLinksIsTrue()
    {
        $links = Links::fromArray(
            [
                "self" => "",
            ]
        );

        $hasAnyLinks = $links->hasAnyLinks();

        $this->assertTrue($hasAnyLinks);
    }

    /**
     * @test
     */
    public function hasAnyLinksIsFalse()
    {
        $links = Links::fromArray([]);

        $hasAnyLinks = $links->hasAnyLinks();

        $this->assertFalse($hasAnyLinks);
    }
}
