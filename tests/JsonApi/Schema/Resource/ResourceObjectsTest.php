<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\Tests\JsonApi\Schema\Resource;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yang\JsonApi\Exception\DocumentException;
use WoohooLabs\Yang\JsonApi\Schema\Resource\ResourceObject;
use WoohooLabs\Yang\JsonApi\Schema\Resource\ResourceObjects;

class ResourceObjectsTest extends TestCase
{
    /**
     * @test
     */
    public function isSinglePrimaryResourceIsTrue()
    {
        $resourceObjects = $this->createResourceObjectsFromSinglePrimaryData();

        $isSinglePrimaryResource = $resourceObjects->isSinglePrimaryResource();

        $this->assertTrue($isSinglePrimaryResource);
    }

    /**
     * @test
     */
    public function isSinglePrimaryResourceIsFalse()
    {
        $resourceObjects = $this->createResourceObjectsFromCollectionPrimaryData();

        $isSinglePrimaryResource = $resourceObjects->isSinglePrimaryResource();

        $this->assertFalse($isSinglePrimaryResource);
    }

    /**
     * @test
     */
    public function isPrimaryResourceCollectionIsTrue()
    {
        $resourceObjects = $this->createResourceObjectsFromCollectionPrimaryData();

        $isPrimaryResourceCollection = $resourceObjects->isPrimaryResourceCollection();

        $this->assertTrue($isPrimaryResourceCollection);
    }

    /**
     * @test
     */
    public function isPrimaryResourceCollectionIsFalse()
    {
        $resourceObjects = $this->createResourceObjectsFromSinglePrimaryData();

        $isPrimaryResourceCollection = $resourceObjects->isPrimaryResourceCollection();

        $this->assertFalse($isPrimaryResourceCollection);
    }

    /**
     * @test
     */
    public function hasResourceIsTrueWhenSinglePrimaryData()
    {
        $resourceObjects = $this->createResourceObjectsFromSinglePrimaryData(
            [
                "type" => "a",
                "id" => "1",
            ]
        );

        $hasResource = $resourceObjects->hasResource("a", "1");

        $this->assertTrue($hasResource);
    }

    /**
     * @test
     */
    public function hasResourceIsTrueWhenCollectionPrimaryData()
    {
        $resourceObjects = $this->createResourceObjectsFromCollectionPrimaryData(
            [
                [
                    "type" => "a",
                    "id" => "1",
                ],
            ]
        );

        $hasResource = $resourceObjects->hasResource("a", "1");

        $this->assertTrue($hasResource);
    }

    /**
     * @test
     */
    public function hasResourceIsTrueWhenIncluded()
    {
        $resourceObjects = $this->createResourceObjectsFromCollectionPrimaryData(
            [],
            [
                [
                    "type" => "a",
                    "id" => "1",
                ],
            ]
        );

        $hasResource = $resourceObjects->hasResource("a", "1");

        $this->assertTrue($hasResource);
    }

    /**
     * @test
     */
    public function hasResourceIsFalseWhenSinglePrimaryData()
    {
        $resourceObjects = $this->createResourceObjectsFromSinglePrimaryData(
            [
                "type" => "a",
                "id" => "1",
            ]
        );

        $hasResource = $resourceObjects->hasResource("b", "2");

        $this->assertFalse($hasResource);
    }

    /**
     * @test
     */
    public function hasResourceIsFalseWhenCollectionPrimaryData()
    {
        $resourceObjects = $this->createResourceObjectsFromCollectionPrimaryData(
            [
                [
                    "type" => "a",
                    "id" => "1",
                ],
            ]
        );

        $hasResource = $resourceObjects->hasResource("b", "2");

        $this->assertFalse($hasResource);
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

        $hasAnyPrimaryResources = $resourceObjects->hasAnyPrimaryResources();

        $this->assertTrue($hasAnyPrimaryResources);
    }

    /**
     * @test
     */
    public function hasAnyPrimaryResourcesIsFalse()
    {
        $resourceObjects = $this->createResourceObjectsFromSinglePrimaryData();

        $hasAnyPrimaryResources = $resourceObjects->hasAnyPrimaryResources();

        $this->assertFalse($hasAnyPrimaryResources);
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

        $primaryResource = $resourceObjects->primaryResource();

        $this->assertInstanceOf(ResourceObject::class, $primaryResource);
    }

    /**
     * @test
     */
    public function primaryResourceWhenCollectionDocument()
    {
        $resourceObjects = $this->createResourceObjectsFromCollectionPrimaryData(
            [
                [
                    "type" => "",
                    "id" => "",
                ],
            ]
        );

        $this->expectException(DocumentException::class);

        $resourceObjects->primaryResource();
    }

    /**
     * @test
     */
    public function primaryResourceWhenMissing()
    {
        $resourceObjects = $this->createResourceObjectsFromSinglePrimaryData();

        $this->expectException(DocumentException::class);

        $resourceObjects->primaryResource();
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
                ],
            ]
        );

        $resourceObject = $resourceObjects->primaryResources()[0];

        $this->assertInstanceOf(ResourceObject::class, $resourceObject);
    }

    /**
     * @test
     */
    public function primaryResourcesReturnsEmptyArray()
    {
        $resourceObjects = $this->createResourceObjectsFromCollectionPrimaryData();

        $primaryResources = $resourceObjects->primaryResources();

        $this->assertEmpty($primaryResources);
    }

    /**
     * @test
     */
    public function primaryResourcesWhenSingleResourceDocument()
    {
        $resourceObjects = $this->createResourceObjectsFromSinglePrimaryData(
            [
                "type" => "",
                "id" => "",
            ]
        );

        $this->expectException(DocumentException::class);

        $resourceObjects->primaryResources();
    }

    /**
     * @test
     */
    public function resourceReturnsObjectWhenSinglePrimaryData()
    {
        $resourceObjects = $this->createResourceObjectsFromSinglePrimaryData(
            [
                "type" => "users",
                "id" => "abcd",
            ]
        );

        $resourceObject = $resourceObjects->resource("users", "abcd");

        $this->assertInstanceOf(ResourceObject::class, $resourceObject);
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
                    "id" => "abcd",
                ],
            ]
        );

        $resourceObject = $resourceObjects->resource("users", "abcd");

        $this->assertInstanceOf(ResourceObject::class, $resourceObject);
    }

    /**
     * @test
     */
    public function resourceReturnsNull()
    {
        $resourceObjects = $this->createResourceObjectsFromSinglePrimaryData();

        $this->expectException(DocumentException::class);

        $resourceObjects->resource("users", "abcd");
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
                ],
            ]
        );

        $hasAnyIncludedResources = $resourceObjects->hasAnyIncludedResources();

        $this->assertTrue($hasAnyIncludedResources);
    }

    /**
     * @test
     */
    public function hasAnyIncludedResourcesIsFalse()
    {
        $resourceObjects = $this->createResourceObjectsFromSinglePrimaryData();

        $hasAnyIncludedResources = $resourceObjects->hasAnyIncludedResources();

        $this->assertFalse($hasAnyIncludedResources);
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
                ],
            ]
        );

        $hasIncludedResources = $resourceObjects->hasIncludedResource("user", "abcd");

        $this->assertTrue($hasIncludedResources);
    }

    /**
     * @test
     */
    public function hasIncludedResourceIsFalse()
    {
        $resourceObjects = $this->createResourceObjectsFromSinglePrimaryData();

        $hasIncludedResources = $resourceObjects->hasIncludedResource("user", "abcd");

        $this->assertFalse($hasIncludedResources);
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
                ],
            ]
        );

        $includedResource = $resourceObjects->includedResources()[0];

        $this->assertInstanceOf(ResourceObject::class, $includedResource);
    }

    /**
     * @test
     */
    public function includedResourcesReturnsEmptyArray()
    {
        $resourceObjects = $this->createResourceObjectsFromCollectionPrimaryData();

        $includedResources = $resourceObjects->includedResources();

        $this->assertEmpty($includedResources);
    }

    /**
     * @test
     */
    public function primaryDataToArrayWhenNoSingleResource()
    {
        $resourceObjects = $this->createResourceObjectsFromSinglePrimaryData([]);

        $array = $resourceObjects->primaryDataToArray();

        $this->assertNull($array);
    }

    /**
     * @test
     */
    public function primaryDataToArrayWhenSingleResource()
    {
        $resourceObjects = $this->createResourceObjectsFromSinglePrimaryData(
            [
                "type" => "a",
                "id" => "1",
            ]
        );

        $array = $resourceObjects->primaryDataToArray();

        $this->assertSame(
            [
                "type" => "a",
                "id" => "1",
            ],
            $array
        );
    }

    /**
     * @test
     */
    public function primaryDataToArrayWhenEmptyCollection()
    {
        $resourceObjects = $this->createResourceObjectsFromCollectionPrimaryData();

        $array = $resourceObjects->primaryDataToArray();

        $this->assertSame([], $array);
    }

    /**
     * @test
     */
    public function primaryDataToArrayWhenNotEmptyCollection()
    {
        $resourceObjects = $this->createResourceObjectsFromCollectionPrimaryData(
            [
                [
                    "type" => "a",
                    "id" => "1",
                ],
                [
                    "type" => "a",
                    "id" => "2",
                ],
            ]
        );

        $array = $resourceObjects->primaryDataToArray();

        $this->assertSame(
            [
                [
                    "type" => "a",
                    "id" => "1",
                ],
                [
                    "type" => "a",
                    "id" => "2",
                ],
            ],
            $array
        );
    }

    /**
     * @test
     */
    public function includedToArray()
    {
        $resourceObjects = $this->createResourceObjectsFromCollectionPrimaryData(
            [],
            [
                [
                    "type" => "a",
                    "id" => "1",
                ],
                [
                    "type" => "a",
                    "id" => "2",
                ],
            ]
        );

        $array = $resourceObjects->includedToArray();

        $this->assertSame(
            [
                [
                    "type" => "a",
                    "id" => "1",
                ],
                [
                    "type" => "a",
                    "id" => "2",
                ],
            ],
            $array
        );
    }

    private function createResourceObjectsFromSinglePrimaryData(array $data = [], array $included = []): ResourceObjects
    {
        return ResourceObjects::fromSinglePrimaryData($data, $included);
    }

    private function createResourceObjectsFromCollectionPrimaryData(array $data = [], array $included = []): ResourceObjects
    {
        return ResourceObjects::fromCollectionPrimaryData($data, $included);
    }
}
