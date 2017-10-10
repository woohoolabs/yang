<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\Tests\JsonApi\Schema;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yang\JsonApi\Schema\JsonApi;

class JsonApiTest extends TestCase
{
    /**
     * @test
     */
    public function createFromArray()
    {
        $jsonApi = JsonApi::createFromArray(
            [
                "version" => "1.1",
                "meta" => [
                    "abc" => "def"
                ],
            ]
        );

        $this->assertSame("1.1", $jsonApi->version());
        $this->assertTrue($jsonApi->hasMeta());
    }

    /**
     * @test
     */
    public function createFromArrayWhenEmpty()
    {
        $jsonApi = JsonApi::createFromArray([]);

        $this->assertSame("1.0", $jsonApi->version());
        $this->assertFalse($jsonApi->hasMeta());
    }

    /**
     * @test
     */
    public function toArray()
    {
        $jsonApi = JsonApi::createFromArray(
            [
                "version" => "1.0",
                "meta" => [
                    "abc" => "def"
                ],
            ]
        );

        $this->assertSame(
            [
                "version" => "1.0",
                "meta" => [
                    "abc" => "def"
                ],
            ],
            $jsonApi->toArray()
        );
    }

    /**
     * @test
     */
    public function version()
    {
        $jsonApi = JsonApi::createFromArray(
            [
                "version" => "1.0",
            ]
        );

        $this->assertSame("1.0", $jsonApi->version());
    }

    /**
     * @test
     */
    public function hasMetaIsTrue()
    {
        $jsonApi = JsonApi::createFromArray(
            [
                "meta" => ["abc" => "def"]
            ]
        );

        $this->assertTrue($jsonApi->hasMeta());
    }

    /**
     * @test
     */
    public function hasMetaIsFalse()
    {
        $jsonApi = JsonApi::createFromArray([]);

        $this->assertFalse($jsonApi->hasMeta());
    }

    /**
     * @test
     */
    public function meta()
    {
        $jsonApi = JsonApi::createFromArray(
            [
                "meta" => ["abc" => "def"],
            ]
        );

        $this->assertSame(["abc" => "def"], $jsonApi->meta());
    }
}
