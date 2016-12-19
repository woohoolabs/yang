<?php
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

        $this->assertEquals(
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
        $resource = new ResourceObject("", "b");

        $this->assertEquals(
            [
                "data" => [
                    "type" => "",
                    "id" => "b",
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

        $this->assertEquals(
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

        $this->assertEquals(
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
            ->setToOneRelationship("a", new ToOneRelationship("a", "a1"));

        $this->assertEquals(
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
            ->setToManyRelationship("a", new ToManyRelationship())
            ->setToManyRelationship("b", new ToManyRelationship());

        $this->assertEquals(
            [
                "data" => [
                    "type" => "",
                    "relationships" => [
                        "a" => [
                            "data" => []
                        ],
                        "b" => [
                            "data" => []
                        ],
                    ],
                ],
            ],
            $resource->toArray()
        );
    }
}
