<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\Tests\JsonApi\Schema\Link;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yang\JsonApi\Exception\LinkException;
use WoohooLabs\Yang\JsonApi\Schema\Link\ErrorLinks;
use WoohooLabs\Yang\JsonApi\Schema\Link\Link;

class ErrorLinksTest extends TestCase
{
    /**
     * @test
     */
    public function createFromArray()
    {
        $links = ErrorLinks::fromArray(
            [
                "about" => "",
                "type" => [],
            ]
        );

        $linksArray = $links->links();
        $typesArray = $links->types();

        $this->assertArrayHasKey("about", $linksArray);
        $this->assertArrayHasKey("type", $typesArray);
    }

    /**
     * @test
     */
    public function toArray()
    {
        $links = ErrorLinks::fromArray(
            [
                "about" => "",
                "type" => [],
            ]
        );

        $linksArray = $links->toArray();

        $this->assertArrayHasKey("about", $linksArray);
        $this->assertArrayHasKey("type", $linksArray);
    }

    /**
     * @test
     */
    public function hasAboutIsTrue()
    {
        $links = ErrorLinks::fromArray(
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
        $links = ErrorLinks::fromArray([]);

        $hasAbout = $links->hasAbout();

        $this->assertFalse($hasAbout);
    }

    /**
     * @test
     */
    public function aboutReturnsObject()
    {
        $links = ErrorLinks::fromArray(
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
        $links = ErrorLinks::fromArray([]);

        $this->expectException(LinkException::class);

        $links->about();
    }

    /**
     * @test
     */
    public function hasTypesIsTrue()
    {
        $links = ErrorLinks::fromArray(
            [
                "type" => [
                    "",
                    "",
                ],
            ]
        );

        $hasAnyTypes = $links->hasTypes();

        $this->assertTrue($hasAnyTypes);
    }

    /**
     * @test
     */
    public function hasAnyTypesIsFalse()
    {
        $links = ErrorLinks::fromArray([]);

        $hasTypes = $links->hasTypes();

        $this->assertFalse($hasTypes);
    }

    /**
     * @test
     */
    public function getTypes()
    {
        $links = ErrorLinks::fromArray(
            [
                "type" => [
                    "",
                    "",
                ],
            ]
        );

        $types = $links->types();

        $this->assertEquals(
            [
                new Link(""),
                new Link("")
            ],
            $types
        );
    }
}
