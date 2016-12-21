<?php
namespace WoohooLabs\Yang\JsonApi\Schema;

use PHPUnit\Framework\TestCase;

class RelationshipTest extends TestCase
{
    /**
     * @test
     */
    public function toArray()
    {
        $relationship = $this->createRelationship(
            [
                "meta" => [
                    "a" => "b"
                ],
                "links" => [
                    "a" => "b"
                ],
                "data" => [
                    "type" => "a",
                    "id" => "b",
                ]
            ]
        );

        $this->assertEquals(
            [
                "meta" => [
                    "a" => "b"
                ],
                "links" => [
                    "a" => [
                        "href" => "b"
                    ]
                ],
                "data" => [
                    "type" => "a",
                    "id" => "b",
                ]
            ],
            $relationship->toArray()
        );
    }

    /**
     * @test
     */
    public function name()
    {
        $relationship = $this->createRelationship([], "a");

        $this->assertEquals("a", $relationship->name());
    }

    /**
     * @test
     */
    public function isToOneRelationshipIsTrue()
    {
        $relationship = $this->createRelationship(
            [
                "data" => [
                    "type" => "a",
                    "id" => "b",
                ]
            ]
        );

        $this->assertTrue($relationship->isToOneRelationship());
    }

    /**
     * @test
     */
    public function isToOneRelationshipIsFalse()
    {
        $relationship = $this->createRelationship(
            [
                "data" => [
                    [
                        "type" => "a",
                        "id" => "b",
                    ]
                ]
            ]
        );

        $this->assertFalse($relationship->isToOneRelationship());
    }

    /**
     * @test
     */
    public function isToManyRelationshipIsTrue()
    {
        $relationship = $this->createRelationship(
            [
                "data" => [
                    [
                        "type" => "a",
                        "id" => "b",
                    ]
                ]
            ]
        );

        $this->assertTrue($relationship->isToManyRelationship());
    }

    /**
     * @test
     */
    public function isToManyRelationshipIsFalse()
    {
        $relationship = $this->createRelationship(
            [
                "data" => [
                    "type" => "a",
                    "id" => "b",
                ]
            ]
        );

        $this->assertFalse($relationship->isToManyRelationship());
    }

    /**
     * @test
     */
    public function hasMetaIsTrue()
    {
        $relationship = $this->createRelationship(
            [
                "meta" => [
                    "a" => "b",
                ]
            ]
        );

        $this->assertTrue($relationship->hasMeta());
    }

    /**
     * @test
     */
    public function hasMetaIsFalse()
    {
        $relationship = $this->createRelationship([]);

        $this->assertFalse($relationship->hasMeta());
    }

    /**
     * @test
     */
    public function meta()
    {
        $relationship = $this->createRelationship(
            [
                "meta" => [
                    "a" => "b",
                ]
            ]
        );

        $this->assertEquals(["a" => "b"], $relationship->meta());
    }

    /**
     * @test
     */
    public function hasLinksIsTrue()
    {
        $relationship = $this->createRelationship(
            [
                "links" => [
                    "a" => "b",
                ]
            ]
        );

        $this->assertTrue($relationship->hasLinks());
    }

    /**
     * @test
     */
    public function hasLinksIsFalse()
    {
        $relationship = $this->createRelationship([]);

        $this->assertFalse($relationship->hasLinks());
    }

    /**
     * @test
     */
    public function linksReturnsObject()
    {
        $relationship = $this->createRelationship([]);

        $this->assertInstanceOf(Links::class, $relationship->links());
    }

    /**
     * @test
     */
    public function resourceLinksForToManyRelationship()
    {
        $relationship = $this->createRelationship(
            [
                "data" => [
                    [
                        "type" => "a",
                        "id" => "b",
                    ]
                ]
            ]
        );

        $this->assertEquals(
            [
                [
                    "type" => "a",
                    "id" => "b",
                ]
            ],
            $relationship->resourceLinks()
        );
    }

    /**
     * @test
     */
    public function resourceLinksForToOneRelationship()
    {
        $relationship = $this->createRelationship(
            [
                "data" => [
                    "type" => "a",
                    "id" => "b",
                ]
            ]
        );

        $this->assertEquals(
            [
                [
                    "type" => "a",
                    "id" => "b",
                ]
            ],
            $relationship->resourceLinks()
        );
    }

    /**
     * @test
     */
    public function firstResourceLinkForToManyRelationship()
    {
        $relationship = $this->createRelationship(
            [
                "data" => [
                    [
                        "type" => "a",
                        "id" => "b",
                    ]
                ]
            ]
        );

        $this->assertEquals(
            [
                "type" => "a",
                "id" => "b",
            ],
            $relationship->firstResourceLink()
        );
    }

    /**
     * @test
     */
    public function firstResourceLinkForToOneRelationship()
    {
        $relationship = $this->createRelationship(
            [
                "data" => [
                    [
                        "type" => "a",
                        "id" => "b",
                    ]
                ]
            ]
        );

        $this->assertEquals(
            [
                "type" => "a",
                "id" => "b",
            ],
            $relationship->firstResourceLink()
        );
    }

    /**
     * @test
     */
    public function firstResourceLinkWhenEmpty()
    {
        $relationship = $this->createRelationship([]);

        $this->assertNull($relationship->firstResourceLink());
    }

    /**
     * @param array $relationship
     * @param string $name
     * @return Relationship
     */
    private function createRelationship(array $relationship, $name = "")
    {
        return Relationship::createFromArray($name, $relationship, new ResourceObjects([], [], true));
    }
}
