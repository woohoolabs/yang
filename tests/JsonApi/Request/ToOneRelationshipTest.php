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
    public function toArrayWithoutMeta(): void
    {
        $relationship = ToOneRelationship::create("a", "1");

        $this->assertSame(
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
    public function toArrayWithMeta(): void
    {
        $relationship = ToOneRelationship::create("a", "1", ["x" => "y"]);

        $this->assertSame(
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
