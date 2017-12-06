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
    public function create()
    {
        $resource = ResourceObject::create("", "");

        $this->assertInstanceOf(ResourceObject::class, $resource);
    }

    /**
     * @test
     */
    public function getType()
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
    public function getId()
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
    public function setAttributes()
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
    public function setAttribute()
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
    public function setToOneRelationship()
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
    public function setToManyRelationship()
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
