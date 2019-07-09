<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\Tests\JsonApi\Schema\Resource;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yang\JsonApi\Exception\DocumentException;
use WoohooLabs\Yang\JsonApi\Schema\Resource\ResourceObjects;

class ResourceObjectsTest extends TestCase
{
    /**
     * @test
     */
    public function isSinglePrimaryResourceIsTrue(): void
    {
        $resourceObjects = $this->createResourceObjectsFromSinglePrimaryData();

        $isSinglePrimaryResource = $resourceObjects->isSinglePrimaryResource();

        $this->assertTrue($isSinglePrimaryResource);
    }

    /**
     * @test
     */
    public function isSinglePrimaryResourceIsFalse(): void
    {
        $resourceObjects = $this->createResourceObjectsFromCollectionPrimaryData();

        $isSinglePrimaryResource = $resourceObjects->isSinglePrimaryResource();

        $this->assertFalse($isSinglePrimaryResource);
    }

    /**
     * @test
     */
    public function isPrimaryResourceCollectionIsTrue(): void
    {
        $resourceObjects = $this->createResourceObjectsFromCollectionPrimaryData();

        $isPrimaryResourceCollection = $resourceObjects->isPrimaryResourceCollection();

        $this->assertTrue($isPrimaryResourceCollection);
    }

    /**
     * @test
     */
    public function isPrimaryResourceCollectionIsFalse(): void
    {
        $resourceObjects = $this->createResourceObjectsFromSinglePrimaryData();

        $isPrimaryResourceCollection = $resourceObjects->isPrimaryResourceCollection();

        $this->assertFalse($isPrimaryResourceCollection);
    }

    /**
     * @test
     */
    public function hasResourceIsTrueWhenSinglePrimaryData(): void
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
    public function hasResourceIsTrueWhenCollectionPrimaryData(): void
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
    public function hasResourceIsTrueWhenIncluded(): void
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
    public function hasResourceIsFalseWhenSinglePrimaryData(): void
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
    public function hasResourceIsFalseWhenCollectionPrimaryData(): void
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
    public function hasAnyPrimaryResourcesIsTrue(): void
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
    public function hasAnyPrimaryResourcesIsFalse(): void
    {
        $resourceObjects = $this->createResourceObjectsFromSinglePrimaryData();

        $hasAnyPrimaryResources = $resourceObjects->hasAnyPrimaryResources();

        $this->assertFalse($hasAnyPrimaryResources);
    }

    /**
     * @test
     */
    public function primaryResourceReturnsObject(): void
    {
        $resourceObjects = $this->createResourceObjectsFromSinglePrimaryData(
            [
                "type" => "",
                "id" => "",
            ]
        );

        $primaryResource = $resourceObjects->primaryResource();

        $this->assertSame("", $primaryResource->type());
        $this->assertSame("", $primaryResource->id());
    }

    /**
     * @test
     */
    public function primaryResourceWhenCollectionDocument(): void
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
    public function primaryResourceWhenMissing(): void
    {
        $resourceObjects = $this->createResourceObjectsFromSinglePrimaryData();

        $this->expectException(DocumentException::class);

        $resourceObjects->primaryResource();
    }

    /**
     * @test
     */
    public function primaryResourcesReturnsObjectArray(): void
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

        $this->assertSame("", $resourceObject->type());
        $this->assertSame("", $resourceObject->id());
    }

    /**
     * @test
     */
    public function primaryResourcesReturnsEmptyArray(): void
    {
        $resourceObjects = $this->createResourceObjectsFromCollectionPrimaryData();

        $primaryResources = $resourceObjects->primaryResources();

        $this->assertEmpty($primaryResources);
    }

    /**
     * @test
     */
    public function primaryResourcesWhenSingleResourceDocument(): void
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
    public function resourceReturnsObjectWhenSinglePrimaryData(): void
    {
        $resourceObjects = $this->createResourceObjectsFromSinglePrimaryData(
            [
                "type" => "users",
                "id" => "abcd",
            ]
        );

        $resourceObject = $resourceObjects->resource("users", "abcd");

        $this->assertSame("users", $resourceObject->type());
        $this->assertSame("abcd", $resourceObject->id());
    }

    /**
     * @test
     */
    public function resourceReturnsObjectWhenCollectionPrimaryData(): void
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

        $this->assertSame("users", $resourceObject->type());
        $this->assertSame("abcd", $resourceObject->id());
    }

    /**
     * @test
     */
    public function resourceReturnsNull(): void
    {
        $resourceObjects = $this->createResourceObjectsFromSinglePrimaryData();

        $this->expectException(DocumentException::class);

        $resourceObjects->resource("users", "abcd");
    }

    /**
     * @test
     */
    public function hasAnyIncludedResourcesIsTrue(): void
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
    public function hasAnyIncludedResourcesIsFalse(): void
    {
        $resourceObjects = $this->createResourceObjectsFromSinglePrimaryData();

        $hasAnyIncludedResources = $resourceObjects->hasAnyIncludedResources();

        $this->assertFalse($hasAnyIncludedResources);
    }

    /**
     * @test
     */
    public function hasIncludedResourcesIsTrue(): void
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
    public function hasIncludedResourceIsFalse(): void
    {
        $resourceObjects = $this->createResourceObjectsFromSinglePrimaryData();

        $hasIncludedResources = $resourceObjects->hasIncludedResource("user", "abcd");

        $this->assertFalse($hasIncludedResources);
    }

    /**
     * @test
     */
    public function includedResourcesReturnsObjectArray(): void
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

        $this->assertSame("", $includedResource->type());
        $this->assertSame("", $includedResource->id());
    }

    /**
     * @test
     */
    public function includedResourcesReturnsEmptyArray(): void
    {
        $resourceObjects = $this->createResourceObjectsFromCollectionPrimaryData();

        $includedResources = $resourceObjects->includedResources();

        $this->assertEmpty($includedResources);
    }

    /**
     * @test
     */
    public function primaryDataToArrayWhenNoSingleResource(): void
    {
        $resourceObjects = $this->createResourceObjectsFromSinglePrimaryData([]);

        $array = $resourceObjects->primaryDataToArray();

        $this->assertNull($array);
    }

    /**
     * @test
     */
    public function primaryDataToArrayWhenSingleResource(): void
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
    public function primaryDataToArrayWhenEmptyCollection(): void
    {
        $resourceObjects = $this->createResourceObjectsFromCollectionPrimaryData();

        $array = $resourceObjects->primaryDataToArray();

        $this->assertSame([], $array);
    }

    /**
     * @test
     */
    public function primaryDataToArrayWhenNotEmptyCollection(): void
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
    public function includedToArray(): void
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
