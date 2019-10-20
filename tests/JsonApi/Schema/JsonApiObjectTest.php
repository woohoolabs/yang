<?php

declare(strict_types=1);

namespace WoohooLabs\Yang\Tests\JsonApi\Schema;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yang\JsonApi\Schema\JsonApiObject;

class JsonApiObjectTest extends TestCase
{
    /**
     * @test
     */
    public function fromArray(): void
    {
        $jsonApi = JsonApiObject::fromArray(
            [
                "version" => "1.1",
                "meta" => [
                    "abc" => "def",
                ],
            ]
        );

        $this->assertSame("1.1", $jsonApi->version());
        $this->assertTrue($jsonApi->hasMeta());
    }

    /**
     * @test
     */
    public function fromArrayWhenEmpty(): void
    {
        $jsonApi = JsonApiObject::fromArray([]);

        $this->assertSame("1.0", $jsonApi->version());
        $this->assertFalse($jsonApi->hasMeta());
    }

    /**
     * @test
     */
    public function toArray(): void
    {
        $jsonApi = JsonApiObject::fromArray(
            [
                "version" => "1.0",
                "meta" => [
                    "abc" => "def",
                ],
            ]
        );

        $this->assertSame(
            [
                "version" => "1.0",
                "meta" => [
                    "abc" => "def",
                ],
            ],
            $jsonApi->toArray()
        );
    }

    /**
     * @test
     */
    public function version(): void
    {
        $jsonApi = JsonApiObject::fromArray(
            [
                "version" => "1.0",
            ]
        );

        $this->assertSame("1.0", $jsonApi->version());
    }

    /**
     * @test
     */
    public function versionWhenNotString(): void
    {
        $jsonApi = JsonApiObject::fromArray(
            [
                "version" => 1.1,
            ]
        );

        $this->assertSame("1.0", $jsonApi->version());
    }

    /**
     * @test
     */
    public function versionWhenZero(): void
    {
        $jsonApi = JsonApiObject::fromArray(
            [
                "version" => "0",
            ]
        );

        $this->assertSame("0", $jsonApi->version());
    }

    /**
     * @test
     */
    public function versionWhenEmpty(): void
    {
        $jsonApi = JsonApiObject::fromArray(
            [
                "version" => "",
            ]
        );

        $this->assertSame("1.0", $jsonApi->version());
    }

    /**
     * @test
     */
    public function hasMetaIsTrue(): void
    {
        $jsonApi = JsonApiObject::fromArray(
            [
                "meta" => ["abc" => "def"],
            ]
        );

        $this->assertTrue($jsonApi->hasMeta());
    }

    /**
     * @test
     */
    public function hasMetaIsFalse(): void
    {
        $jsonApi = JsonApiObject::fromArray([]);

        $this->assertFalse($jsonApi->hasMeta());
    }

    /**
     * @test
     */
    public function meta(): void
    {
        $jsonApi = JsonApiObject::fromArray(
            [
                "meta" => ["abc" => "def"],
            ]
        );

        $this->assertSame(["abc" => "def"], $jsonApi->meta());
    }
}
