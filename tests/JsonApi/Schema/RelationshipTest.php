<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\JsonApi\Schema;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yang\JsonApi\Schema\Link\RelationshipLinks;
use WoohooLabs\Yang\JsonApi\Schema\Resource\ResourceObjects;

class RelationshipTest extends TestCase
{
    /**
     * @test
     */
    public function name()
    {
        $relationship = $this->createRelationship([], "a");

        $name = $relationship->name();

        $this->assertSame("a", $name);
    }

    /**
     * @test
     */
    public function isToOneRelationshipIsTrueWhenEmpty()
    {
        $relationship = $this->createRelationship(
            [
                "data" => null,
            ]
        );

        $isToOneRelationship = $relationship->isToOneRelationship();

        $this->assertTrue($isToOneRelationship);
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
                ],
            ]
        );

        $isToOneRelationship = $relationship->isToOneRelationship();

        $this->assertTrue($isToOneRelationship);
    }

    /**
     * @test
     */
    public function isToOneRelationshipIsFalseWhenEmpty()
    {
        $relationship = $this->createRelationship(
            [
                "data" => [],
            ]
        );

        $isToOneRelationship = $relationship->isToOneRelationship();

        $this->assertFalse($isToOneRelationship);
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
                    ],
                ],
            ]
        );

        $isToOneRelationship = $relationship->isToOneRelationship();

        $this->assertFalse($isToOneRelationship);
    }

    /**
     * @test
     */
    public function isToManyRelationshipIsTrueWhenEmpty()
    {
        $relationship = $this->createRelationship(
            [
                "data" => [],
            ]
        );

        $isToManyRelationship = $relationship->isToManyRelationship();

        $this->assertTrue($isToManyRelationship);
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
                    ],
                ],
            ]
        );

        $isToManyRelationship = $relationship->isToManyRelationship();

        $this->assertTrue($isToManyRelationship);
    }

    /**
     * @test
     */
    public function isToManyRelationshipIsFalseWhenEmpty()
    {
        $relationship = $this->createRelationship(
            [
                "data" => null,
            ]
        );

        $isToManyRelationship = $relationship->isToManyRelationship();

        $this->assertFalse($isToManyRelationship);
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
                ],
            ]
        );

        $isToManyRelationship = $relationship->isToManyRelationship();

        $this->assertFalse($isToManyRelationship);
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
                ],
            ]
        );

        $hasMeta = $relationship->hasMeta();

        $this->assertTrue($hasMeta);
    }

    /**
     * @test
     */
    public function hasMetaIsFalse()
    {
        $relationship = $this->createRelationship([]);

        $hasMeta = $relationship->hasMeta();

        $this->assertFalse($hasMeta);
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
                ],
            ]
        );

        $meta = $relationship->meta();

        $this->assertSame(["a" => "b"], $meta);
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
                ],
            ]
        );

        $hasLinks = $relationship->hasLinks();

        $this->assertTrue($hasLinks);
    }

    /**
     * @test
     */
    public function hasLinksIsFalse()
    {
        $relationship = $this->createRelationship([]);

        $hasLinks = $relationship->hasLinks();

        $this->assertFalse($hasLinks);
    }

    /**
     * @test
     */
    public function linksReturnsObject()
    {
        $relationship = $this->createRelationship([]);

        $links = $relationship->links();

        $this->assertEquals(new RelationshipLinks([]), $links);
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

        $resourceLinks = $relationship->resourceLinks();

        $this->assertSame(
            [
                [
                    "type" => "a",
                    "id" => "b",
                ]
            ],
            $resourceLinks
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

        $resourceLinks = $relationship->resourceLinks();

        $this->assertSame(
            [
                [
                    "type" => "a",
                    "id" => "b",
                ]
            ],
            $resourceLinks
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

        $firstResourceLink = $relationship->firstResourceLink();

        $this->assertSame(
            [
                "type" => "a",
                "id" => "b",
            ],
            $firstResourceLink
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
                    ],
                ],
            ]
        );

        $firstResourceLink = $relationship->firstResourceLink();

        $this->assertSame(
            [
                "type" => "a",
                "id" => "b",
            ],
            $firstResourceLink
        );
    }

    /**
     * @test
     */
    public function firstResourceLinkWhenEmpty()
    {
        $relationship = $this->createRelationship([]);

        $firstResourceLink = $relationship->firstResourceLink();

        $this->assertNull($firstResourceLink);
    }

    /**
     * @test
     */
    public function hasIncludedResourceWhenFalse()
    {
        $relationship = $this->createRelationship([]);

        $hasIncludedResource = $relationship->hasIncludedResource("", "");

        $this->assertFalse($hasIncludedResource);
    }

    /**
     * @test
     */
    public function toArrayWhenDataIsMissing()
    {
        $relationship = $this->createRelationship(
            [
                "meta" => [
                    "a" => "b",
                ],
                "links" => [
                    "a" => "b",
                ],
            ]
        );

        $array = $relationship->toArray();

        $this->assertSame(
            [
                "meta" => [
                    "a" => "b",
                ],
                "links" => [
                    "a" => [
                        "href" => "b",
                    ],
                ],
            ],
            $array
        );
    }

    /**
     * @test
     */
    public function toArrayWhenDataIsEmptyToOne()
    {
        $relationship = $this->createRelationship(
            [
                "meta" => [
                    "a" => "b",
                ],
                "links" => [
                    "a" => "b",
                ],
                "data" => null,
            ]
        );

        $array = $relationship->toArray();

        $this->assertSame(
            [
                "meta" => [
                    "a" => "b",
                ],
                "links" => [
                    "a" => [
                        "href" => "b",
                    ],
                ],
                "data" => null,
            ],
            $array
        );
    }

    /**
     * @test
     */
    public function toArrayWhenDataIsEmptyToMany()
    {
        $relationship = $this->createRelationship(
            [
                "meta" => [
                    "a" => "b",
                ],
                "links" => [
                    "a" => "b",
                ],
                "data" => [],
            ]
        );

        $array = $relationship->toArray();

        $this->assertSame(
            [
                "meta" => [
                    "a" => "b",
                ],
                "links" => [
                    "a" => [
                        "href" => "b",
                    ],
                ],
                "data" => [],
            ],
            $array
        );
    }

    /**
     * @test
     */
    public function toArrayWhenDataIsToOne()
    {
        $relationship = $this->createRelationship(
            [
                "meta" => [
                    "a" => "b",
                ],
                "links" => [
                    "a" => "b",
                ],
                "data" => [
                    "type" => "a",
                    "id" => "b",
                ],
            ]
        );

        $array = $relationship->toArray();

        $this->assertSame(
            [
                "meta" => [
                    "a" => "b",
                ],
                "links" => [
                    "a" => [
                        "href" => "b",
                    ],
                ],
                "data" => [
                    "type" => "a",
                    "id" => "b",
                ],
            ],
            $array
        );
    }

    /**
     * @test
     */
    public function toArrayWhenDataIsToMany()
    {
        $relationship = $this->createRelationship(
            [
                "meta" => [
                    "a" => "b",
                ],
                "links" => [
                    "a" => "b",
                ],
                "data" => [
                    [
                        "type" => "a",
                        "id" => "1",
                    ],
                    [
                        "type" => "a",
                        "id" => "2",
                    ],
                ],
            ]
        );

        $array = $relationship->toArray();

        $this->assertSame(
            [
                "meta" => [
                    "a" => "b",
                ],
                "links" => [
                    "a" => [
                        "href" => "b",
                    ],
                ],
                "data" => [
                    [
                        "type" => "a",
                        "id" => "1",
                    ],
                    [
                        "type" => "a",
                        "id" => "2",
                    ],
                ],
            ],
            $array
        );
    }

    private function createRelationship(array $relationship, string $name = ""): Relationship
    {
        return Relationship::fromArray($name, $relationship, new ResourceObjects([], [], true));
    }
}
