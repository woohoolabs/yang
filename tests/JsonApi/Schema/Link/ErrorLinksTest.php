<?php

declare(strict_types=1);

namespace BahaaAlhagar\Yang\Tests\JsonApi\Schema\Link;

use PHPUnit\Framework\TestCase;
use BahaaAlhagar\Yang\JsonApi\Schema\Link\Link;
use BahaaAlhagar\Yang\JsonApi\Schema\Link\ErrorLinks;
use BahaaAlhagar\Yang\JsonApi\Exception\DocumentException;

class ErrorLinksTest extends TestCase
{
    /**
     * @test
     */
    public function hasAboutIsTrue(): void
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
    public function hasAboutIsFalse(): void
    {
        $links = ErrorLinks::fromArray([]);

        $hasAbout = $links->hasAbout();

        $this->assertFalse($hasAbout);
    }

    /**
     * @test
     */
    public function aboutReturnsObject(): void
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
    public function aboutWhenMissing(): void
    {
        $links = ErrorLinks::fromArray([]);

        $this->expectException(DocumentException::class);

        $links->about();
    }

    /**
     * @test
     */
    public function hasTypeWhenTrue(): void
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
    public function hasTypeWhenFalse(): void
    {
        $links = ErrorLinks::fromArray([]);

        $type = $links->hasType("");

        $this->assertFalse($type);
    }

    /**
     * @test
     */
    public function getTypeWhenPresent(): void
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
    public function getTypeWhenEmpty(): void
    {
        $links = ErrorLinks::fromArray([]);

        $this->expectException(DocumentException::class);

        $links->type("");
    }

    /**
     * @test
     */
    public function getTypeWhenMissing(): void
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
    public function hasAnyTypesWhenTrue(): void
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
    public function hasAnyTypesWhenFalse(): void
    {
        $links = ErrorLinks::fromArray([]);

        $hasAnyTypes = $links->hasAnyTypes();

        $this->assertFalse($hasAnyTypes);
    }

    /**
     * @test
     */
    public function getTypes(): void
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
    public function fromArray(): void
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
    public function toArray(): void
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
