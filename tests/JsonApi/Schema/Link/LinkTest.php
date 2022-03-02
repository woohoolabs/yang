<?php

declare(strict_types=1);

namespace BahaaAlhagar\Yang\Tests\JsonApi\Schema\Link;

use PHPUnit\Framework\TestCase;
use BahaaAlhagar\Yang\JsonApi\Schema\Link\Link;

class LinkTest extends TestCase
{
    /**
     * @test
     */
    public function href(): void
    {
        $link = new Link("abc");

        $this->assertSame("abc", $link->href());
    }

    /**
     * @test
     */
    public function hasMetaWhenTrue(): void
    {
        $link = Link::fromArray(
            [
                "href" => "",
                "meta" => [
                    "abc" => "def",
                ],
            ]
        );

        $this->assertTrue($link->hasMeta());
    }

    /**
     * @test
     */
    public function hasMetaWhenFalse(): void
    {
        $link = Link::fromArray(
            [
                "href" => "",
                "meta" => [],
            ]
        );

        $this->assertFalse($link->hasMeta());
    }

    /**
     * @test
     */
    public function meta(): void
    {
        $link = Link::fromArray(
            [
                "href" => "",
                "meta" => [
                    "abc" => "def",
                ],
            ]
        );

        $this->assertSame(["abc" => "def"], $link->meta());
    }

    /**
     * @test
     */
    public function fromString(): void
    {
        $link = Link::fromString("abc");

        $this->assertSame("abc", $link->href());
        $this->assertFalse($link->hasMeta());
    }

    /**
     * @test
     */
    public function fromArray(): void
    {
        $link = Link::fromArray(
            [
                "href" => "abc",
                "meta" => [
                    "abc" => "def",
                ],
            ]
        );

        $this->assertSame("abc", $link->href());
        $this->assertTrue($link->hasMeta());
    }

    /**
     * @test
     */
    public function fromArrayWithWrongTypes(): void
    {
        $link = Link::fromArray(
            [
                "href" => 123,
                "meta" => null,
            ]
        );

        $this->assertSame("", $link->href());
        $this->assertFalse($link->hasMeta());
    }

    /**
     * @test
     */
    public function toArray(): void
    {
        $link = Link::fromArray(
            [
                "href" => "abc",
                "meta" => [
                    "abc" => "def",
                ],
            ]
        );

        $this->assertSame(
            [
                "href" => "abc",
                "meta" => [
                    "abc" => "def",
                ],
            ],
            $link->toArray()
        );
    }
}
