<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\Tests\JsonApi\Schema\Link;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yang\JsonApi\Exception\DocumentException;
use WoohooLabs\Yang\JsonApi\Schema\Link\ErrorLinks;
use WoohooLabs\Yang\JsonApi\Schema\Link\Link;

class ErrorLinksTest extends TestCase
{
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

        $this->expectException(DocumentException::class);

        $links->about();
    }

    /**
     * @test
     */
    public function hasTypeWhenTrue()
    {
        $links = ErrorLinks::fromArray(
            [
                "type" => [
                    "",
                ],
            ]
        );

        $type = $links->hasType("");

        $this->assertTrue($type);
    }

    /**
     * @test
     */
    public function hasTypeWhenFalse()
    {
        $links = ErrorLinks::fromArray([]);

        $type = $links->hasType("");

        $this->assertFalse($type);
    }

    /**
     * @test
     */
    public function getTypeWhenPresent()
    {
        $links = ErrorLinks::fromArray(
            [
                "type" => [
                    "",
                ],
            ]
        );

        $type = $links->type("");

        $this->assertEquals(new Link(""), $type);
    }

    /**
     * @test
     */
    public function getTypeWhenEmpty()
    {
        $links = ErrorLinks::fromArray([]);

        $this->expectException(DocumentException::class);

        $links->type("");
    }

    /**
     * @test
     */
    public function getTypeWhenMissing()
    {
        $links = ErrorLinks::fromArray(
            [
                "type" => [
                    "",
                ],
            ]
        );

        $this->expectException(DocumentException::class);

        $links->type("abc");
    }

    /**
     * @test
     */
    public function hasAnyTypesWhenTrue()
    {
        $links = ErrorLinks::fromArray(
            [
                "type" => [
                    "",
                ],
            ]
        );

        $hasAnyTypes = $links->hasAnyTypes();

        $this->assertTrue($hasAnyTypes);
    }

    /**
     * @test
     */
    public function hasAnyTypesWhenFalse()
    {
        $links = ErrorLinks::fromArray([]);

        $hasAnyTypes = $links->hasAnyTypes();

        $this->assertFalse($hasAnyTypes);
    }

    /**
     * @test
     */
    public function getTypes()
    {
        $links = ErrorLinks::fromArray(
            [
                "type" => [
                    "a",
                    [
                        "href" => "b",
                    ],
                ],
            ]
        );

        $types = $links->types();

        $this->assertEquals(
            [
                new Link("a"),
                new Link("b"),
            ],
            $types
        );
    }

    /**
     * @test
     */
    public function fromArray()
    {
        $links = ErrorLinks::fromArray(
            [
                "about" => "",
                "type" => [],
            ]
        );

        $array = $links->toArray();

        $this->assertArrayHasKey("about", $array);
        $this->assertArrayNotHasKey("type", $array);
    }

    /**
     * @test
     */
    public function toArray()
    {
        $links = ErrorLinks::fromArray(
            [
                "about" => "",
                "type" => [
                    "",
                ],
            ]
        );

        $linksArray = $links->toArray();

        $this->assertArrayHasKey("about", $linksArray);
        $this->assertArrayHasKey("type", $linksArray);
    }
}
