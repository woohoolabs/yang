<?php

declare(strict_types=1);

namespace BahaaAlhagar\Yang\Tests\JsonApi\Schema;

use PHPUnit\Framework\TestCase;
use BahaaAlhagar\Yang\JsonApi\Schema\Relationship;
use BahaaAlhagar\Yang\JsonApi\Exception\DocumentException;
use BahaaAlhagar\Yang\JsonApi\Schema\Link\RelationshipLinks;
use BahaaAlhagar\Yang\JsonApi\Schema\Resource\ResourceObjects;

class RelationshipTest extends TestCase
{
    /**
     * @test
     */
    public function name(): void
    {
        $relationship = $this->createRelationship([], "a");

        $name = $relationship->name();

        $this->assertSame("a", $name);
    }

    /**
     * @test
     */
    public function isToOneRelationshipIsTrueWhenEmpty(): void
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
    public function isToOneRelationshipIsTrue(): void
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
    public function isToOneRelationshipIsFalseWhenEmpty(): void
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
    public function isToOneRelationshipIsFalse(): void
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
    public function isToManyRelationshipIsTrueWhenEmpty(): void
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
    public function isToManyRelationshipIsTrue(): void
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
    public function isToManyRelationshipIsFalseWhenEmpty(): void
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
    public function isToManyRelationshipIsFalse(): void
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
    public function hasMetaIsTrue(): void
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
    public function hasMetaIsFalse(): void
    {
        $relationship = $this->createRelationship([]);

        $hasMeta = $relationship->hasMeta();

        $this->assertFalse($hasMeta);
    }

    /**
     * @test
     */
    public function meta(): void
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
    public function hasLinksIsTrue(): void
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
    public function hasLinksIsFalse(): void
    {
        $relationship = $this->createRelationship([]);

        $hasLinks = $relationship->hasLinks();

        $this->assertFalse($hasLinks);
    }

    /**
     * @test
     */
    public function linksReturnsObject(): void
    {
        $relationship = $this->createRelationship([]);

        $links = $relationship->links();

        $this->assertEquals(new RelationshipLinks([]), $links);
    }

    /**
     * @test
     */
    public function resourceLinksForToManyRelationship(): void
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

        $resourceLinks = $relationship->resourceLinks();

        $this->assertSame(
            [
                [
                    "type" => "a",
                    "id" => "b",
                ],
            ],
            $resourceLinks
        );
    }

    /**
     * @test
     */
    public function resourceLinksForToOneRelationship(): void
    {
        $relationship = $this->createRelationship(
            [
                "data" => [
                    "type" => "a",
                    "id" => "b",
                ],
            ]
        );

        $resourceLinks = $relationship->resourceLinks();

        $this->assertSame(
            [
                [
                    "type" => "a",
                    "id" => "b",
                ],
            ],
            $resourceLinks
        );
    }

    /**
     * @test
     */
    public function firstResourceLinkForToManyRelationship(): void
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
    public function firstResourceLinkForToOneRelationship(): void
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
    public function firstResourceLinkWhenEmpty(): void
    {
        $relationship = $this->createRelationship([]);

        $firstResourceLink = $relationship->firstResourceLink();

        $this->assertNull($firstResourceLink);
    }

    /**
     * @test
     */
    public function hasIncludedResourceWhenFalse(): void
    {
        $relationship = $this->createRelationship([]);

        $hasIncludedResource = $relationship->hasIncludedResource("", "");

        $this->assertFalse($hasIncludedResource);
    }

    /**
     * @test
     */
    public function resourcesWhenToOneRelationship(): void
    {
        $relationship = $this->createRelationship(
            [
                "data" => [
                    "type" => "a",
                    "id" => "1",
                ],
            ]
        );

        $this->expectException(DocumentException::class);

        $relationship->resources();
    }

    /**
     * @test
     */
    public function resourcesWhenToManyRelationship(): void
    {
        $relationship = $this->createRelationship(
            [
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
            "",
            [
                [
                    "type" => "a",
                    "id" => "1",
                ],
            ]
        );

        $resources = $relationship->resources();
        $resource = $resources[0];

        $this->assertCount(1, $resources);
        $this->assertSame(
            [
                "type" => "a",
                "id" => "1",
            ],
            $resource->toArray()
        );
    }

    /**
     * @test
     */
    public function resourceMap(): void
    {
        $relationship = $this->createRelationship(
            [
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
            "",
            [
                [
                    "type" => "a",
                    "id" => "1",
                ],
            ]
        );

        $resourceMap = $relationship->resourceMap();
        $resource = $resourceMap["a"]["1"];

        $this->assertCount(1, $resourceMap);
        $this->assertSame(
            [
                "type" => "a",
                "id" => "1",
            ],
            $resource->toArray()
        );
    }

    /**
     * @test
     */
    public function resourceWhenToOneRelationship(): void
    {
        $relationship = $this->createRelationship(
            [
                "data" => [
                    "type" => "a",
                    "id" => "1",
                ],
            ],
            "",
            [
                [
                    "type" => "a",
                    "id" => "1",
                ],
            ]
        );

        $resource = $relationship->resource();

        $this->assertSame(
            [
                "type" => "a",
                "id" => "1",
            ],
            $resource->toArray()
        );
    }

    /**
     * @test
     */
    public function resourceWhenEmptyToOneRelationship(): void
    {
        $relationship = $this->createRelationship([]);

        $this->expectException(DocumentException::class);

        $relationship->resource();
    }

    /**
     * @test
     */
    public function resourceWhenToManyRelationship(): void
    {
        $relationship = $this->createRelationship(
            [
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
            "",
            [
                [
                    "type" => "a",
                    "id" => "1",
                ],
            ]
        );

        $this->expectException(DocumentException::class);

        $relationship->resource();
    }

    /**
     * @test
     */
    public function resourceBy(): void
    {
        $relationship = $this->createRelationship(
            [
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
            "",
            [
                [
                    "type" => "a",
                    "id" => "1",
                ],
            ]
        );

        $resource = $relationship->resourceBy("a", "1");

        $this->assertSame(
            [
                "type" => "a",
                "id" => "1",
            ],
            $resource->toArray()
        );
    }

    /**
     * @test
     */
    public function resourceByWhenMissing(): void
    {
        $relationship = $this->createRelationship(
            [
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
            "",
            [
                [
                    "type" => "a",
                    "id" => "1",
                ],
            ]
        );

        $this->expectException(DocumentException::class);

        $relationship->resourceBy("a", "2");
    }

    /**
     * @test
     */
    public function resourceLinkMetaWhenToOne(): void
    {
        $relationship = $this->createRelationship(
            [
                "data" => [
                    "type" => "a",
                    "id" => "1",
                    "meta" => [
                        "abc" => "def",
                    ],
                ],
            ]
        );

        $resourceLinkMeta = $relationship->resourceLinkMeta("a", "1");

        $this->assertSame(
            [
                "abc" => "def",
            ],
            $resourceLinkMeta
        );
    }

    /**
     * @test
     */
    public function resourceLinkMetaWhenToMany(): void
    {
        $relationship = $this->createRelationship(
            [
                "data" => [
                    [
                        "type" => "a",
                        "id" => "1",
                        "meta" => [
                            "abc" => "def",
                        ],
                    ],
                ],
            ]
        );

        $resourceLinkMeta = $relationship->resourceLinkMeta("a", "1");

        $this->assertSame(
            [
                "abc" => "def",
            ],
            $resourceLinkMeta
        );
    }

    /**
     * @test
     */
    public function resourceLinkMetaWhenEmpty(): void
    {
        $relationship = $this->createRelationship(
            [
                "meta" => [],
                "data" => [
                    "type" => "a",
                    "id" => "1",
                    "meta" => [],
                ],
            ]
        );

        $resourceLinkMeta = $relationship->resourceLinkMeta("a", "1");

        $this->assertSame([], $resourceLinkMeta);
    }

    /**
     * @test
     */
    public function resourceLinkMetaWhenMissing(): void
    {
        $relationship = $this->createRelationship(
            [
                "data" => [
                    "type" => "a",
                    "id" => "1",
                ],
            ]
        );

        $resourceLinkMeta = $relationship->resourceLinkMeta("a", "2");

        $this->assertSame([], $resourceLinkMeta);
    }

    /**
     * @test
     */
    public function toArrayWhenDataIsMissing(): void
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
    public function toArrayWhenDataIsEmptyToOne(): void
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
    public function toArrayWhenDataIsEmptyToMany(): void
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
    public function toArrayWhenDataIsToOne(): void
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
    public function toArrayWhenDataIsToMany(): void
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

    private function createRelationship(array $relationship, string $name = "", array $included = []): Relationship
    {
        return Relationship::fromArray($name, $relationship, new ResourceObjects([], $included, true));
    }
}
