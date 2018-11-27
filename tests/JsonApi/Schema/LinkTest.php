<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\Tests\JsonApi\Schema;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yang\JsonApi\Schema\Link;

class LinkTest extends TestCase
{
    /**
     * @test
     */
    public function createFromString()
    {
        $link = Link::createFromString("abc");

        $this->assertSame("abc", $link->href());
        $this->assertFalse($link->hasMeta());
    }

    /**
     * @test
     */
    public function createFromArray()
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
    public function createFromArrayWithWrongTypes()
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
    public function toArray()
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

    /**
     * @test
     */
    public function href()
    {
        $link = new Link("abc");

        $this->assertSame("abc", $link->href());
    }

    /**
     * @test
     */
    public function hasMetaIsTrue()
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
    public function hasMetaIsFalse()
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
    public function meta()
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
}
