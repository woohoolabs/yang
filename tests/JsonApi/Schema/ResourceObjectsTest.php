<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\Tests\JsonApi\Schema;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yang\JsonApi\Schema\ResourceObject;
use WoohooLabs\Yang\JsonApi\Schema\ResourceObjects;

class ResourceObjectsTest extends TestCase
{
    /**
     * @test
     */
    public function isSinglePrimaryResourceIsTrue()
    {
        $resourceObjects = $this->createResourceObjectsFromSinglePrimaryData();

        $this->assertTrue($resourceObjects->isSinglePrimaryResource());
    }

    /**
     * @test
     */
    public function isSinglePrimaryResourceIsFalse()
    {
        $resourceObjects = $this->createResourceObjectsFromCollectionPrimaryData();

        $this->assertFalse($resourceObjects->isSinglePrimaryResource());
    }

    /**
     * @test
     */
    public function isPrimaryResourceCollectionIsTrue()
    {
        $resourceObjects = $this->createResourceObjectsFromCollectionPrimaryData();

        $this->assertTrue($resourceObjects->isPrimaryResourceCollection());
    }

    /**
     * @test
     */
    public function isPrimaryResourceCollectionIsFalse()
    {
        $resourceObjects = $this->createResourceObjectsFromSinglePrimaryData();

        $this->assertFalse($resourceObjects->isPrimaryResourceCollection());
    }

    /**
     * @test
     */
    public function hasAnyPrimaryResourcesIsTrue()
    {
        $resourceObjects = $this->createResourceObjectsFromSinglePrimaryData(
            [
                "type" => "",
                "id" => "",
            ]
        );

        $this->assertTrue($resourceObjects->hasAnyPrimaryResources());
    }

    /**
     * @test
     */
    public function hasAnyPrimaryResourcesIsFalse()
    {
        $resourceObjects = $this->createResourceObjectsFromSinglePrimaryData();

        $this->assertFalse($resourceObjects->hasAnyPrimaryResources());
    }

    /**
     * @test
     */
    public function primaryResourceReturnsObject()
    {
        $resourceObjects = $this->createResourceObjectsFromSinglePrimaryData(
            [
                "type" => "",
                "id" => "",
            ]
        );

        $this->assertInstanceOf(ResourceObject::class, $resourceObjects->primaryResource());
    }

    /**
     * @test
     */
    public function primaryResourceReturnsNull()
    {
        $resourceObjects = $this->createResourceObjectsFromSinglePrimaryData();

        $this->assertNull($resourceObjects->primaryResource());
    }

    /**
     * @test
     */
    public function primaryResourcesReturnsObjectArray()
    {
        $resourceObjects = $this->createResourceObjectsFromCollectionPrimaryData(
            [
                [
                    "type" => "",
                    "id" => "",
                ]
            ]
        );

        $this->assertInstanceOf(ResourceObject::class, $resourceObjects->primaryResources()[0]);
    }

    /**
     * @test
     */
    public function primaryResourcesReturnsEmptyArray()
    {
        $resourceObjects = $this->createResourceObjectsFromCollectionPrimaryData();

        $this->assertEmpty($resourceObjects->primaryResources());
    }

    /**
     * @test
     */
    public function resourceReturnsObjectWhenSinglePrimaryData()
    {
        $resourceObjects = $this->createResourceObjectsFromSinglePrimaryData(
            [
                "type" => "users",
                "id" => "abcd"
            ]
        );

        $this->assertInstanceOf(ResourceObject::class, $resourceObjects->resource("users", "abcd"));
    }

    /**
     * @test
     */
    public function resourceReturnsObjectWhenCollectionPrimaryData()
    {
        $resourceObjects = $this->createResourceObjectsFromCollectionPrimaryData(
            [
                [
                    "type" => "users",
                    "id" => "abcd"
                ]
            ]
        );

        $this->assertInstanceOf(ResourceObject::class, $resourceObjects->resource("users", "abcd"));
    }

    /**
     * @test
     */
    public function resourceReturnsNull()
    {
        $resourceObjects = $this->createResourceObjectsFromSinglePrimaryData();

        $this->assertNull($resourceObjects->resource("users", "abcd"));
    }

    /**
     * @test
     */
    public function hasAnyIncludedResourcesIsTrue()
    {
        $resourceObjects = $this->createResourceObjectsFromSinglePrimaryData(
            [],
            [
                [
                    "type" => "",
                    "id" => "",
                ]
            ]
        );

        $this->assertTrue($resourceObjects->hasAnyIncludedResources());
    }

    /**
     * @test
     */
    public function hasAnyIncludedResourcesIsFalse()
    {
        $resourceObjects = $this->createResourceObjectsFromSinglePrimaryData();

        $this->assertFalse($resourceObjects->hasAnyIncludedResources());
    }

    /**
     * @test
     */
    public function hasIncludedResourcesIsTrue()
    {
        $resourceObjects = $this->createResourceObjectsFromSinglePrimaryData(
            [],
            [
                [
                    "type" => "user",
                    "id" => "abcd",
                ]
            ]
        );

        $this->assertTrue($resourceObjects->hasIncludedResource("user", "abcd"));
    }

    /**
     * @test
     */
    public function hasIncludedResourceIsFalse()
    {
        $resourceObjects = $this->createResourceObjectsFromSinglePrimaryData();

        $this->assertFalse($resourceObjects->hasIncludedResource("user", "abcd"));
    }

    /**
     * @test
     */
    public function includedResourcesReturnsObjectArray()
    {
        $resourceObjects = $this->createResourceObjectsFromSinglePrimaryData(
            [],
            [
                [
                    "type" => "",
                    "id" => "",
                ]
            ]
        );

        $this->assertInstanceOf(ResourceObject::class, $resourceObjects->includedResources()[0]);
    }

    /**
     * @test
     */
    public function includedResourcesReturnsEmptyArray()
    {
        $resourceObjects = $this->createResourceObjectsFromCollectionPrimaryData();

        $this->assertEmpty($resourceObjects->includedResources());
    }

    private function createResourceObjectsFromSinglePrimaryData(array $data = [], array $included = []): ResourceObjects
    {
        return new ResourceObjects($data, $included, true);
    }

    private function createResourceObjectsFromCollectionPrimaryData(array $data = [], array $included = []): ResourceObjects
    {
        return new ResourceObjects($data, $included, false);
    }
}
