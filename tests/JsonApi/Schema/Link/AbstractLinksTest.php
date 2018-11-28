<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\Tests\JsonApi\Schema\Link;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yang\JsonApi\Exception\LinkException;
use WoohooLabs\Yang\JsonApi\Schema\Link\DocumentLinks;
use WoohooLabs\Yang\JsonApi\Schema\Link\Link;

class AbstractLinksTest extends TestCase
{
    /**
     * @test
     */
    public function hasLinkIsTrue()
    {
        $links = DocumentLinks::fromArray(
            [
                "self" => "",
            ]
        );

        $hasLink = $links->hasLink("self");

        $this->assertTrue($hasLink);
    }

    /**
     * @test
     */
    public function hasLinkIsFalse()
    {
        $links = DocumentLinks::fromArray([]);

        $hasLink = $links->hasLink("self");

        $this->assertFalse($hasLink);
    }

    /**
     * @test
     */
    public function linkWhenMissing()
    {
        $links = DocumentLinks::fromArray([]);

        $this->expectException(LinkException::class);

        $links->link("link");
    }

    /**
     * @test
     */
    public function hasAnyLinksIsTrue()
    {
        $links = DocumentLinks::fromArray(
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
        $links = DocumentLinks::fromArray([]);

        $hasAnyLinks = $links->hasAnyLinks();

        $this->assertFalse($hasAnyLinks);
    }

    /**
     * @test
     */
    public function links()
    {
        $links = DocumentLinks::fromArray(
            [
                "self" => "",
            ]
        );

        $links = $links->links();

        $this->assertEquals(["self" => new Link("")], $links);
    }

    /**
     * @test
     */
    public function createFromArray()
    {
        $links = DocumentLinks::fromArray(
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
        $links = DocumentLinks::fromArray(
            [
                "self" => "",
                "related" => [],
            ]
        );

        $linksArray = $links->toArray();

        $this->assertArrayHasKey("self", $linksArray);
        $this->assertArrayHasKey("related", $linksArray);
    }
}
