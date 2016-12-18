<?php
namespace WoohooLabs\Yang\Tests\JsonApi\Request;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yang\JsonApi\Request\JsonApiResource;
use WoohooLabs\Yang\JsonApi\Request\JsonApiToManyRelationship;
use WoohooLabs\Yang\JsonApi\Request\JsonApiToOneRelationship;

class JsonApiResourceTest extends TestCase
{
    /**
     * @test
     */
    public function create()
    {
        $resource = JsonApiResource::create("", "");

          $this->assertInstanceOf(JsonApiResource::class, $resource);
    }

    /**
     * @test
     */
    public function getType()
    {
        $resource = new JsonApiResource("a");

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
        $resource = new JsonApiResource("", "b");

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
        $resource = new JsonApiResource("", "");
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
        $resource = new JsonApiResource("", "");
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
        $resource = new JsonApiResource("", "");
        $resource
            ->setToOneRelationship("a", new JsonApiToOneRelationship("a", "a1"));

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
        $resource = new JsonApiResource("", "");
        $resource
            ->setToManyRelationship("a", new JsonApiToManyRelationship())
            ->setToManyRelationship("b", new JsonApiToManyRelationship());

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
