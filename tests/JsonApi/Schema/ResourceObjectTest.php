<?php
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
                    "a" => "b"
                ],
                "links" => [
                    "a" => "b"
                ],
                "attributes" => [
                    "a" => "b"
                ],
                "relationships" => [
                    "a" => []
                ]
            ]
        );

        $this->assertEquals(
            [
                "type" => "user",
                "id" => "abc",
                "meta" => [
                    "a" => "b"
                ],
                "links" => [
                    "a" => [
                        "href" => "b"
                    ]
                ],
                "attributes" => [
                    "a" => "b"
                ],
                "relationships" => [
                    "a" => []
                ]
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

        $this->assertEquals(
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
                "type" => "abc"
            ]
        );

        $this->assertEquals("abc", $resourceObject->type());
    }

    /**
     * @test
     */
    public function id()
    {
        $resourceObject = $this->createResourceObject(
            [
                "id" => "abc"
            ]
        );

        $this->assertEquals("abc", $resourceObject->id());
    }

    /**
     * @test
     */
    public function hasMetaIsTrue()
    {
        $resourceObject = $this->createResourceObject(
            [
                "meta" => [
                    "abc" => "def"
                ]
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
                    "abc" => "def"
                ]
            ]
        );

        $this->assertEquals(["abc" => "def"], $resourceObject->meta());
    }

    /**
     * @test
     */
    public function hasLinksIsTrue()
    {
        $resourceObject = $this->createResourceObject(
            [
                "links" => [
                    "a" => "b"
                ]
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
                    "a" => "b"
                ]
            ]
        );

        $this->assertEquals(["a" => "b"], $resourceObject->attributes());
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
                    "a" => "b"
                ]
            ]
        );

        $this->assertEquals("b", $resourceObject->attribute("a"));
    }

    /**
     * @test
     */
    public function hasRelationshipIsTrue()
    {
        $resourceObject = $this->createResourceObject(
            [
                "relationships" => [
                    "a" => []
                ]
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
                    "a" => []
                ]
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

    private function createResourceObject(array $data)
    {
        return ResourceObject::createFromArray($data, new ResourceObjects($data, [], true));
    }
}
