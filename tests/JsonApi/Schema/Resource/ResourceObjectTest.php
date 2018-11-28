<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\Tests\JsonApi\Schema\Resource;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yang\JsonApi\Exception\DocumentException;
use WoohooLabs\Yang\JsonApi\Schema\Link\ResourceLinks;
use WoohooLabs\Yang\JsonApi\Schema\Relationship;
use WoohooLabs\Yang\JsonApi\Schema\Resource\ResourceObject;
use WoohooLabs\Yang\JsonApi\Schema\Resource\ResourceObjects;

class ResourceObjectTest extends TestCase
{
    /**
     * @test
     */
    public function toArray()
    {
        $resourceObject = $this->createResourceObject(
            [
                "type" => "user",
                "id" => "abc",
                "meta" => [
                    "a" => "b",
                ],
                "links" => [
                    "a" => "b",
                ],
                "attributes" => [
                    "a" => "b",
                ],
                "relationships" => [
                    "a" => [],
                ],
            ]
        );

        $array = $resourceObject->toArray();

        $this->assertSame(
            [
                "type" => "user",
                "id" => "abc",
                "meta" => [
                    "a" => "b",
                ],
                "links" => [
                    "a" => [
                        "href" => "b",
                    ],
                ],
                "attributes" => [
                    "a" => "b",
                ],
                "relationships" => [
                    "a" => [],
                ],
            ],
            $array
        );
    }

    /**
     * @test
     */
    public function toArrayWhenEmpty()
    {
        $resourceObject = $this->createResourceObject([]);

        $array = $resourceObject->toArray();

        $this->assertSame(
            [
                "type" => "",
                "id" => "",
            ],
            $array
        );
    }

    /**
     * @test
     */
    public function type()
    {
        $resourceObject = $this->createResourceObject(
            [
                "type" => "abc",
            ]
        );

        $type = $resourceObject->type();

        $this->assertSame("abc", $type);
    }

    /**
     * @test
     */
    public function id()
    {
        $resourceObject = $this->createResourceObject(
            [
                "id" => "abc",
            ]
        );

        $id = $resourceObject->id();

        $this->assertSame("abc", $id);
    }

    /**
     * @test
     */
    public function hasMetaIsTrue()
    {
        $resourceObject = $this->createResourceObject(
            [
                "meta" => [
                    "abc" => "def",
                ],
            ]
        );

        $hasMeta = $resourceObject->hasMeta();

        $this->assertTrue($hasMeta);
    }

    /**
     * @test
     */
    public function hasMetaIsFalse()
    {
        $resourceObject = $this->createResourceObject([]);

        $hasMeta = $resourceObject->hasMeta();

        $this->assertFalse($hasMeta);
    }

    /**
     * @test
     */
    public function meta()
    {
        $resourceObject = $this->createResourceObject(
            [
                "meta" => [
                    "abc" => "def",
                ],
            ]
        );

        $meta = $resourceObject->meta();

        $this->assertSame(["abc" => "def"], $meta);
    }

    /**
     * @test
     */
    public function hasLinksIsTrue()
    {
        $resourceObject = $this->createResourceObject(
            [
                "links" => [
                    "a" => "b",
                ],
            ]
        );

        $hasLinks = $resourceObject->hasLinks();

        $this->assertTrue($hasLinks);
    }

    /**
     * @test
     */
    public function hasLinksIsFalse()
    {
        $resourceObject = $this->createResourceObject([]);

        $hasLinks = $resourceObject->hasLinks();

        $this->assertFalse($hasLinks);
    }

    /**
     * @test
     */
    public function linksReturnsObject()
    {
        $resourceObject = $this->createResourceObject([]);

        $links = $resourceObject->links();

        $this->assertEquals(new ResourceLinks([]), $links);
    }

    /**
     * @test
     */
    public function attributes()
    {
        $resourceObject = $this->createResourceObject(
            [
                "attributes" => [
                    "a" => "b",
                ],
            ]
        );

        $attributes = $resourceObject->attributes();

        $this->assertSame(["a" => "b"], $attributes);
    }

    /**
     * @test
     */
    public function idAndAttributes()
    {
        $resourceObject = $this->createResourceObject(
            [
                "id" => "abc",
                "attributes" => [
                    "a" => "b",
                ],
            ]
        );

        $idAndAttributes = $resourceObject->idAndAttributes();

        $this->assertSame(["id" => "abc", "a" => "b"], $idAndAttributes);
    }

    /**
     * @test
     */
    public function hasAttributeIsTrue()
    {
        $resourceObject = $this->createResourceObject(
            [
                "attributes" => [
                    "a" => "b",
                ]
            ]
        );

        $hasAttribute = $resourceObject->hasAttribute("a");

        $this->assertTrue($hasAttribute);
    }

    /**
     * @test
     */
    public function hasAttributeIsTrueWhenNull()
    {
        $resourceObject = $this->createResourceObject(
            [
                "attributes" => [
                    "a" => null,
                ]
            ]
        );

        $hasAttribute = $resourceObject->hasAttribute("a");

        $this->assertTrue($hasAttribute);
    }

    /**
     * @test
     */
    public function hasAttributeIsFalse()
    {
        $resourceObject = $this->createResourceObject(
            [
                "attributes" => [
                    "a" => "b",
                ]
            ]
        );

        $hasAttribute = $resourceObject->hasAttribute("b");

        $this->assertFalse($hasAttribute);
    }

    /**
     * @test
     */
    public function attributeReturnsValue()
    {
        $resourceObject = $this->createResourceObject(
            [
                "attributes" => [
                    "a" => "b",
                ],
            ]
        );

        $attribute = $resourceObject->attribute("a");

        $this->assertSame("b", $attribute);
    }

    /**
     * @test
     */
    public function relationships()
    {
        $resourceObject = $this->createResourceObject(
            [
                "relationships" => [
                    "a" => [
                        "data" => [
                            "type" => "a",
                            "id" => "1",
                        ],
                    ],
                    "b" => [
                        "data" => [
                            "type" => "a",
                            "id" => "1",
                        ],
                    ],
                ],
            ]
        );

        $relationships = $resourceObject->relationships();

        $this->assertCount(2, $relationships);
        $this->assertInstanceOf(Relationship::class, $relationships["a"]);
        $this->assertInstanceOf(Relationship::class, $relationships["b"]);
    }

    /**
     * @test
     */
    public function hasRelationshipIsTrue()
    {
        $resourceObject = $this->createResourceObject(
            [
                "relationships" => [
                    "a" => [],
                ],
            ]
        );

        $hasRelationship = $resourceObject->hasRelationship("a");

        $this->assertTrue($hasRelationship);
    }

    /**
     * @test
     */
    public function hasRelationshipIsFalse()
    {
        $resourceObject = $this->createResourceObject([]);

        $hasRelationship = $resourceObject->hasRelationship("a");

        $this->assertFalse($hasRelationship);
    }

    /**
     * @test
     */
    public function relationshipReturnsObject()
    {
        $resourceObject = $this->createResourceObject(
            [
                "relationships" => [
                    "a" => [],
                ],
            ]
        );

        $relationship = $resourceObject->relationship("a");

        $this->assertInstanceOf(Relationship::class, $relationship);
    }

    /**
     * @test
     */
    public function relationshipWhenMissing()
    {
        $resourceObject = $this->createResourceObject([]);

        $this->expectException(DocumentException::class);

        $resourceObject->relationship("a");
    }

    private function createResourceObject(array $data): ResourceObject
    {
        return ResourceObject::fromArray($data, new ResourceObjects($data, [], true));
    }
}
