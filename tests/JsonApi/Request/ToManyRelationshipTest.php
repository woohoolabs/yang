<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\Tests\JsonApi\Request;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yang\JsonApi\Request\ToManyRelationship;

class ToManyRelationshipTest extends TestCase
{
    /**
     * @test
     */
    public function create()
    {
        $relationship = ToManyRelationship::create();

        $this->assertInstanceOf(ToManyRelationship::class, $relationship);
    }

    /**
     * @test
     */
    public function toArrayWithOneItemWithoutMeta()
    {
        $relationship = ToManyRelationship::create();
        $relationship->addResourceIdentifier("a", "1");

        $this->assertEquals(
            [
                "data" => [
                    [
                        "type" => "a",
                        "id" => "1",
                    ],
                ],
            ],
            $relationship->toArray()
        );
    }

    /**
     * @test
     */
    public function toArrayWithMultipleItemsAndMeta()
    {
        $relationship = ToManyRelationship::create();
        $relationship
            ->addResourceIdentifier("a", "1", ["x" => "y"])
            ->addResourceIdentifier("a", "1");

        $this->assertEquals(
            [
                "data" => [
                    [
                        "type" => "a",
                        "id" => "1",
                        "meta" => [
                            "x" => "y",
                        ],
                    ],
                    [
                        "type" => "a",
                        "id" => "1",
                    ],
                ],
            ],
            $relationship->toArray()
        );
    }
}
