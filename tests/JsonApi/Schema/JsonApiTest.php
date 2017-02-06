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

        $this->assertEquals("1.1", $jsonApi->version());
        $this->assertTrue($jsonApi->hasMeta());
    }

    /**
     * @test
     */
    public function createFromArrayWhenEmpty()
    {
        $jsonApi = JsonApi::createFromArray([]);

        $this->assertEquals("1.0", $jsonApi->version());
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

        $this->assertEquals(
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

        $this->assertEquals("1.0", $jsonApi->version());
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

        $this->assertEquals(["abc" => "def"], $jsonApi->meta());
    }
}
