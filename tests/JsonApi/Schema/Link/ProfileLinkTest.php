<?php

declare(strict_types=1);

namespace BahaaAlhagar\Yang\Tests\JsonApi\Schema\Link;

use PHPUnit\Framework\TestCase;
use BahaaAlhagar\Yang\JsonApi\Schema\Link\ProfileLink;

class ProfileLinkTest extends TestCase
{
    /**
     * @test
     */
    public function aliasesWhenEmpty(): void
    {
        $link = new ProfileLink("", [], []);

        $this->assertSame([], $link->aliases());
    }

    /**
     * @test
     */
    public function aliasesWhenNotEmpty(): void
    {
        $link = new ProfileLink("", [], ["a" => "abc"]);

        $this->assertSame(["a" => "abc"], $link->aliases());
    }

    /**
     * @test
     */
    public function fromString(): void
    {
        $link = ProfileLink::fromString("abc");

        $this->assertSame("abc", $link->href());
        $this->assertFalse($link->hasMeta());
        $this->assertEmpty($link->aliases());
    }

    /**
     * @test
     */
    public function fromArray(): void
    {
        $link = ProfileLink::fromArray(
            [
                "href" => "abc",
                "meta" => [
                    "abc" => "def",
                ],
                "aliases" => [
                    "a" => "abc",
                ],
            ]
        );

        $this->assertSame("abc", $link->href());
        $this->assertTrue($link->hasMeta());
        $this->assertSame(["a" => "abc"], $link->aliases());
    }

    /**
     * @test
     */
    public function fromArrayWithWrongTypes(): void
    {
        $link = ProfileLink::fromArray(
            [
                "href" => 123,
                "meta" => null,
                "aliases" => "",
            ]
        );

        $this->assertSame("", $link->href());
        $this->assertFalse($link->hasMeta());
        $this->assertEmpty($link->aliases());
    }

    /**
     * @test
     */
    public function toArray(): void
    {
        $link = ProfileLink::fromArray(
            [
                "href" => "abc",
                "meta" => [
                    "abc" => "def",
                ],
                "aliases" => [
                    "a" => "abc",
                ],
            ]
        );

        $this->assertSame(
            [
                "href" => "abc",
                "meta" => [
                    "abc" => "def",
                ],
                "aliases" => [
                    "a" => "abc",
                ],
            ],
            $link->toArray()
        );
    }
}
