<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\Tests\JsonApi\Request;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yang\JsonApi\Request\ToOneRelationship;

class ToOneRelationshipTest extends TestCase
{
    /**
     * @test
     */
    public function create()
    {
        $relationship = ToOneRelationship::create("", "");

        $this->assertInstanceOf(ToOneRelationship::class, $relationship);
    }

    /**
     * @test
     */
    public function toArrayWithoutMeta()
    {
        $relationship = ToOneRelationship::create("a", "1");

        $this->assertEquals(
            [
                "data" => [
                    "type" => "a",
                    "id" => "1",
                ],
            ],
            $relationship->toArray()
        );
    }

    /**
     * @test
     */
    public function toArrayWithMeta()
    {
        $relationship = ToOneRelationship::create("a", "1", ["x" => "y"]);

        $this->assertEquals(
            [
                "data" => [
                    "type" => "a",
                    "id" => "1",
                    "meta" => [
                        "x" => "y",
                    ],
                ],
            ],
            $relationship->toArray()
        );
    }
}
