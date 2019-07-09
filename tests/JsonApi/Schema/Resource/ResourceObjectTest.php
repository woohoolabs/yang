<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\Tests\JsonApi\Schema\Resource;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yang\JsonApi\Exception\DocumentException;
use WoohooLabs\Yang\JsonApi\Schema\Link\ResourceLinks;
use WoohooLabs\Yang\JsonApi\Schema\Resource\ResourceObject;
use WoohooLabs\Yang\JsonApi\Schema\Resource\ResourceObjects;

class ResourceObjectTest extends TestCase
{
    /**
     * @test
     */
    public function type(): void
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
    public function id(): void
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
    public function hasMetaIsTrue(): void
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
    public function hasMetaIsFalse(): void
    {
        $resourceObject = $this->createResourceObject([]);

        $hasMeta = $resourceObject->hasMeta();

        $this->assertFalse($hasMeta);
    }

    /**
     * @test
     */
    public function meta(): void
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
    public function hasLinksIsTrue(): void
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
    public function hasLinksIsFalse(): void
    {
        $resourceObject = $this->createResourceObject([]);

        $hasLinks = $resourceObject->hasLinks();

        $this->assertFalse($hasLinks);
    }

    /**
     * @test
     */
    public function linksReturnsObject(): void
    {
        $resourceObject = $this->createResourceObject([]);

        $links = $resourceObject->links();

        $this->assertEquals(new ResourceLinks([]), $links);
    }

    /**
     * @test
     */
    public function attributes(): void
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
    public function idAndAttributes(): void
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
    public function hasAttributeIsTrue(): void
    {
        $resourceObject = $this->createResourceObject(
            [
                "attributes" => [
                    "a" => "b",
                ],
            ]
        );

        $hasAttribute = $resourceObject->hasAttribute("a");

        $this->assertTrue($hasAttribute);
    }

    /**
     * @test
     */
    public function hasAttributeIsTrueWhenNull(): void
    {
        $resourceObject = $this->createResourceObject(
            [
                "attributes" => [
                    "a" => null,
                ],
            ]
        );

        $hasAttribute = $resourceObject->hasAttribute("a");

        $this->assertTrue($hasAttribute);
    }

    /**
     * @test
     */
    public function hasAttributeIsFalse(): void
    {
        $resourceObject = $this->createResourceObject(
            [
                "attributes" => [
                    "a" => "b",
                ],
            ]
        );

        $hasAttribute = $resourceObject->hasAttribute("b");

        $this->assertFalse($hasAttribute);
    }

    /**
     * @test
     */
    public function attributeReturnsValue(): void
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
    public function attributeReturnsDefaultValue(): void
    {
        $resourceObject = $this->createResourceObject(
            [
                "attributes" => [],
            ]
        );

        $attribute = $resourceObject->attribute("a", "b");

        $this->assertSame("b", $attribute);
    }

    /**
     * @test
     */
    public function relationships(): void
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
        $this->assertSame(["type" => "a", "id" => "1"], $relationships["a"]->firstResourceLink());
        $this->assertSame(["type" => "a", "id" => "1"], $relationships["b"]->firstResourceLink());
    }

    /**
     * @test
     */
    public function hasRelationshipIsTrue(): void
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
    public function hasRelationshipIsFalse(): void
    {
        $resourceObject = $this->createResourceObject([]);

        $hasRelationship = $resourceObject->hasRelationship("a");

        $this->assertFalse($hasRelationship);
    }

    /**
     * @test
     */
    public function relationshipReturnsObject(): void
    {
        $resourceObject = $this->createResourceObject(
            [
                "relationships" => [
                    "a" => [],
                ],
            ]
        );

        $relationship = $resourceObject->relationship("a");

        $this->assertSame("a", $relationship->name());
    }

    /**
     * @test
     */
    public function relationshipWhenMissing(): void
    {
        $resourceObject = $this->createResourceObject([]);

        $this->expectException(DocumentException::class);

        $resourceObject->relationship("a");
    }

    /**
     * @test
     */
    public function relationshipWhenInvalid(): void
    {
        $resourceObject = $this->createResourceObject(
            [
                "type" => 1,
                "id" => 1,
                "meta" => "",
                "links" => "",
                "attributes" => "",
                "relationships" => [
                    1 => "",
                ],
            ]
        );

        $this->expectException(DocumentException::class);

        $resourceObject->relationship("1");
    }

    /**
     * @test
     */
    public function toArray(): void
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
    public function toArrayWhenEmpty(): void
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

    private function createResourceObject(array $data): ResourceObject
    {
        return ResourceObject::fromArray($data, new ResourceObjects($data, [], true));
    }
}
