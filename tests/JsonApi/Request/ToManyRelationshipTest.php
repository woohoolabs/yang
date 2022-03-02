<?php

declare(strict_types=1);

namespace BahaaAlhagar\Yang\Tests\JsonApi\Request;

use PHPUnit\Framework\TestCase;
use BahaaAlhagar\Yang\JsonApi\Request\ToManyRelationship;

class ToManyRelationshipTest extends TestCase
{
    /**
     * @test
     */
    public function toArrayWithOneItemWithoutMeta(): void
    {
        $relationship = ToManyRelationship::create();
        $relationship->addResourceIdentifier("a", "1");

        $this->assertSame(
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
    public function toArrayWithMultipleItemsAndMeta(): void
    {
        $relationship = ToManyRelationship::create();
        $relationship
            ->addResourceIdentifier("a", "1", ["x" => "y"])
            ->addResourceIdentifier("a", "1");

        $this->assertSame(
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
