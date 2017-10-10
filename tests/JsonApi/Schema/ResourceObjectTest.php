<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\Tests\JsonApi\Schema;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yang\JsonApi\Schema\Links;
use WoohooLabs\Yang\JsonApi\Schema\Relationship;
use WoohooLabs\Yang\JsonApi\Schema\ResourceObject;
use WoohooLabs\Yang\JsonApi\Schema\ResourceObjects;

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
            $resourceObject->toArray()
        );
    }

    /**
     * @test
     */
    public function toArrayWhenEmpty()
    {
        $resourceObject = $this->createResourceObject([]);

        $this->assertSame(
            [
                "type" => "",
                "id" => "",
            ],
            $resourceObject->toArray()
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

        $this->assertSame("abc", $resourceObject->type());
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

        $this->assertSame("abc", $resourceObject->id());
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

        $this->assertTrue($resourceObject->hasMeta());
    }

    /**
     * @test
     */
    public function hasMetaIsFalse()
    {
        $resourceObject = $this->createResourceObject([]);

        $this->assertFalse($resourceObject->hasMeta());
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

        $this->assertSame(["abc" => "def"], $resourceObject->meta());
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

        $this->assertTrue($resourceObject->hasLinks());
    }

    /**
     * @test
     */
    public function hasLinksIsFalse()
    {
        $resourceObject = $this->createResourceObject([]);

        $this->assertFalse($resourceObject->hasLinks());
    }

    /**
     * @test
     */
    public function linksReturnsObject()
    {
        $resourceObject = $this->createResourceObject([]);

        $this->assertInstanceOf(Links::class, $resourceObject->links());
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

        $this->assertSame(["a" => "b"], $resourceObject->attributes());
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

        $this->assertSame(["id" => "abc", "a" => "b"], $resourceObject->idAndAttributes());
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

        $this->assertTrue($resourceObject->hasAttribute("a"));
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

        $this->assertTrue($resourceObject->hasAttribute("a"));
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

        $this->assertFalse($resourceObject->hasAttribute("b"));
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

        $this->assertSame("b", $resourceObject->attribute("a"));
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

        $this->assertTrue($resourceObject->hasRelationship("a"));
    }

    /**
     * @test
     */
    public function hasRelationshipIsFalse()
    {
        $resourceObject = $this->createResourceObject([]);

        $this->assertFalse($resourceObject->hasRelationship("a"));
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

        $this->assertInstanceOf(Relationship::class, $resourceObject->relationship("a"));
    }

    /**
     * @test
     */
    public function relationshipReturnsNull()
    {
        $resourceObject = $this->createResourceObject([]);

        $this->assertNull(null, $resourceObject->relationship("a"));
    }

    private function createResourceObject(array $data): ResourceObject
    {
        return ResourceObject::createFromArray($data, new ResourceObjects($data, [], true));
    }
}
