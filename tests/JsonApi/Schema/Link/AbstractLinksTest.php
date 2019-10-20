<?php

declare(strict_types=1);

namespace WoohooLabs\Yang\Tests\JsonApi\Schema\Link;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yang\JsonApi\Exception\DocumentException;
use WoohooLabs\Yang\JsonApi\Schema\Link\DocumentLinks;
use WoohooLabs\Yang\JsonApi\Schema\Link\Link;

class AbstractLinksTest extends TestCase
{
    /**
     * @test
     */
    public function hasLinkWhenTrue(): void
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
    public function hasLinkWhenFalse(): void
    {
        $links = DocumentLinks::fromArray([]);

        $hasLink = $links->hasLink("self");

        $this->assertFalse($hasLink);
    }

    /**
     * @test
     */
    public function linkWhenMissing(): void
    {
        $links = DocumentLinks::fromArray([]);

        $this->expectException(DocumentException::class);

        $links->link("link");
    }

    /**
     * @test
     */
    public function hasAnyLinksIsTrue(): void
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
    public function hasAnyLinksIsFalse(): void
    {
        $links = DocumentLinks::fromArray([]);

        $hasAnyLinks = $links->hasAnyLinks();

        $this->assertFalse($hasAnyLinks);
    }

    /**
     * @test
     */
    public function links(): void
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
    public function fromArray(): void
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
    public function toArray(): void
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
