<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\Tests\JsonApi\Request;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yang\JsonApi\Request\ResourceObject;
use WoohooLabs\Yang\JsonApi\Request\ToManyRelationship;
use WoohooLabs\Yang\JsonApi\Request\ToOneRelationship;

class ResourceObjectTest extends TestCase
{
    /**
     * @test
     */
    public function toArray(): void
    {
        $resource = ResourceObject::create("a", "0");

        $this->assertSame(
            [
                "data" => [
                    "type" => "a",
                    "id" => "0",
                ],
            ],
            $resource->toArray()
        );
    }

    /**
     * @test
     */
    public function type(): void
    {
        $resource = new ResourceObject("a");

        $type = $resource->type();

        $this->assertSame("a", $type);
    }

    /**
     * @test
     */
    public function typeToArray(): void
    {
        $resource = new ResourceObject("a");

        $this->assertSame(
            [
                "data" => [
                    "type" => "a",
                ],
            ],
            $resource->toArray()
        );
    }

    /**
     * @test
     */
    public function setType(): void
    {
        $resource = new ResourceObject("");

        $resource->setType("a");

        $this->assertSame(
            [
                "data" => [
                    "type" => "a",
                ],
            ],
            $resource->toArray()
        );
    }

    /**
     * @test
     */
    public function id(): void
    {
        $resource = new ResourceObject("", "1");

        $id = $resource->id();

        $this->assertSame("1", $id);
    }

    /**
     * @test
     */
    public function idToArray(): void
    {
        $resource = new ResourceObject("", "0");

        $this->assertSame(
            [
                "data" => [
                    "type" => "",
                    "id" => "0",
                ],
            ],
            $resource->toArray()
        );
    }

    /**
     * @test
     */
    public function setId(): void
    {
        $resource = new ResourceObject("");

        $resource->setId("0");

        $this->assertSame(
            [
                "data" => [
                    "type" => "",
                    "id" => "0",
                ],
            ],
            $resource->toArray()
        );
    }

    /**
     * @test
     */
    public function attributes(): void
    {
        $resource = new ResourceObject("", "");
        $resource
            ->setAttributes(["a" => "b", "c" => "d"]);

        $this->assertSame(
            [
                "a" => "b",
                "c" => "d",
            ],
            $resource->attributes()
        );
    }

    /**
     * @test
     */
    public function setAttributes(): void
    {
        $resource = new ResourceObject("", "");
        $resource
            ->setAttributes(["a" => "b", "c" => "d"]);

        $this->assertSame(
            [
                "data" => [
                    "type" => "",
                    "attributes" => [
                        "a" => "b",
                        "c" => "d",
                    ],
                ],
            ],
            $resource->toArray()
        );
    }

    /**
     * @test
     */
    public function setAttribute(): void
    {
        $resource = new ResourceObject("", "");
        $resource
            ->setAttribute("a", "b")
            ->setAttribute("c", "d");

        $this->assertSame(
            [
                "data" => [
                    "type" => "",
                    "attributes" => [
                        "a" => "b",
                        "c" => "d",
                    ],
                ],
            ],
            $resource->toArray()
        );
    }

    /**
     * @test
     */
    public function relationships(): void
    {
        $resource = new ResourceObject("", "");
        $resource
            ->setToOneRelationship("a", ToOneRelationship::create("", ""))
            ->setToManyRelationship("b", ToManyRelationship::create());

        $this->assertEquals(
            [
                "a" => ToOneRelationship::create("", ""),
                "b" => ToManyRelationship::create(),
            ],
            $resource->relationships()
        );
    }

    /**
     * @test
     */
    public function setToOneRelationship(): void
    {
        $resource = new ResourceObject("", "");
        $resource
            ->setToOneRelationship("a", ToOneRelationship::create("a", "a1"));

        $this->assertSame(
            [
                "data" => [
                    "type" => "",
                    "relationships" => [
                        "a" => [
                            "data" => [
                                "type" => "a",
                                "id" => "a1",
                            ],
                        ],
                    ],
                ],
            ],
            $resource->toArray()
        );
    }

    /**
     * @test
     */
    public function setToManyRelationship(): void
    {
        $resource = new ResourceObject("", "");
        $resource
            ->setToManyRelationship("a", ToManyRelationship::create())
            ->setToManyRelationship("b", ToManyRelationship::create());

        $this->assertSame(
            [
                "data" => [
                    "type" => "",
                    "relationships" => [
                        "a" => [
                            "data" => [],
                        ],
                        "b" => [
                            "data" => [],
                        ],
                    ],
                ],
            ],
            $resource->toArray()
        );
    }
}
