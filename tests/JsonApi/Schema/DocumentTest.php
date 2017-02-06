<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\Tests\JsonApi\Schema;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yang\JsonApi\Schema\Document;
use WoohooLabs\Yang\JsonApi\Schema\Error;
use WoohooLabs\Yang\JsonApi\Schema\JsonApi;
use WoohooLabs\Yang\JsonApi\Schema\Links;

class DocumentTest extends TestCase
{
    /**
     * @test
     */
    public function toArrayWhenEmpty()
    {
        $document = $this->createDocument(
            [
                "jsonapi" => [],
                "meta" => [],
                "data" => []
            ]
        );

        $this->assertEquals(
            [
                "jsonapi" => [
                    "version" => "1.0",
                ]
            ],
            $document->toArray());
    }

    /**
     * @test
     */
    public function toArray()
    {
        $document = $this->createDocument(
            [
                "jsonapi" => [
                    "version" => "1.0",
                ],
                "meta" => [
                    "a" => "b"
                ],
                "links" => [
                    "self" => "http://example.com/api/users/abcd"
                ],
                "data" => [
                    "type" => "user",
                    "id" => "abcd"
                ],
                "errors" => [
                    [
                        "status" => "401"
                    ]
                ],
                "included" => [
                    [
                        "type" => "user",
                        "id" => "efgh"
                    ]
                ]
            ]
        );

        $this->assertEquals(
            [
                "jsonapi" => [
                    "version" => "1.0",
                ],
                "meta" => [
                    "a" => "b"
                ],
                "links" => [
                    "self" => [
                        "href" => "http://example.com/api/users/abcd"
                    ]
                ],
                "data" => [
                    "type" => "user",
                    "id" => "abcd"
                ],
                "errors" => [
                    [
                        "status" => "401"
                    ]
                ],
                "included" => [
                    [
                        "type" => "user",
                        "id" => "efgh"
                    ]
                ]
            ],
            $document->toArray()
        );
    }

    /**
     * @test
     */
    public function jsonApiReturnsObject()
    {
        $document = $this->createDocument([]);

        $this->assertInstanceOf(JsonApi::class, $document->jsonApi());
    }

    /**
     * @test
     */
    public function hasMetaIsTrue()
    {
        $document = $this->createDocument(
            [
                "meta" => [
                    "a" => "b",
                ],
            ]
        );

        $this->assertTrue($document->hasMeta());
    }

    /**
     * @test
     */
    public function hasMetaIsFalse()
    {
        $document = $this->createDocument([]);

        $this->assertFalse($document->hasMeta());
    }

    /**
     * @test
     */
    public function metaReturnsObject()
    {
        $document = $this->createDocument(
            [
                "meta" => [
                    "a" => "b"
                ]
            ]
        );

        $this->assertEquals(["a" => "b"], $document->meta());
    }

    /**
     * @test
     */
    public function hasLinksIsTrue()
    {
        $document = $this->createDocument(
            [
                "links" => [
                    "self" => "http://example.com/api/users/abcd",
                ],
            ]
        );

        $this->assertTrue($document->hasLinks());
    }

    /**
     * @test
     */
    public function hasLinksIsFalse()
    {
        $document = $this->createDocument([]);

        $this->assertFalse($document->hasLinks());
    }

    /**
     * @test
     */
    public function linksReturnsObject()
    {
        $document = $this->createDocument([]);

        $this->assertInstanceOf(Links::class, $document->links());
    }

    /**
     * @test
     */
    public function isSingleResourceDocumentIsTrue()
    {
        $document = $this->createDocument(
            [
                "data" => [
                    "type" => "",
                    "id" => "",
                ]
            ]
        );

        $this->assertTrue($document->isSingleResourceDocument());
    }

    /**
     * @test
     */
    public function isSingleResourceDocumentIsTrueWhenNull()
    {
        $document = $this->createDocument(
            [
                "data" => null
            ]
        );

        $this->assertTrue($document->isSingleResourceDocument());
    }

    /**
     * @test
     */
    public function isSingleResourceDocumentIsFalse()
    {
        $document = $this->createDocument(
            [
                "data" => [
                    [
                        "type" => "",
                        "id" => "",
                    ]
                ]
            ]
        );

        $this->assertFalse($document->isSingleResourceDocument());
    }

    /**
     * @test
     */
    public function isResourceCollectionDocumentIsTrue()
    {
        $document = $this->createDocument(
            [
                "data" => [
                    [
                        "type" => "",
                        "id" => ""
                    ]
                ]
            ]
        );

        $this->assertTrue($document->isResourceCollectionDocument());
    }

    /**
     * @test
     */
    public function isResourceCollectionDocumentIsTrueWhenEmpty()
    {
        $document = $this->createDocument(
            [
                "data" => []
            ]
        );

        $this->assertTrue($document->isResourceCollectionDocument());
    }

    /**
     * @test
     */
    public function isResourceCollectionDocumentIsFalse()
    {
        $document = $this->createDocument(
            [
                "data" => [
                    "type" => "",
                    "id" => "",
                ]
            ]
        );

        $this->assertFalse($document->isResourceCollectionDocument());
    }

    /**
     * @test
     */
    public function hasAnyPrimaryResourcesIsTrueWhenHasSinglePrimaryData()
    {
        $document = $this->createDocument(
            [
                "data" => [
                    "type" => "",
                    "id" => "",
                ]
            ]
        );

        $this->assertTrue($document->hasAnyPrimaryResources());
    }

    /**
     * @test
     */
    public function hasAnyPrimaryResourcesIsFalseWhenNull()
    {
        $document = $this->createDocument(
            [
                "data" => null
            ]
        );

        $this->assertFalse($document->hasAnyPrimaryResources());
    }

    /**
     * @test
     */
    public function hasAnyPrimaryResourcesIsTrueWhenHasCollectionPrimaryData()
    {
        $document = $this->createDocument(
            [
                "data" => [
                    [
                        "type" => "",
                        "id" => "",
                    ]
                ]
            ]
        );

        $this->assertTrue($document->hasAnyPrimaryResources());
    }

    /**
     * @test
     */
    public function hasAnyPrimaryResourcesIsFalseWhenEmpty()
    {
        $document = $this->createDocument(
            [
                "data" => []
            ]
        );

        $this->assertFalse($document->hasAnyPrimaryResources());
    }

    /**
     * @test
     */
    public function hasErrorsIsTrue()
    {
        $document = $this->createDocument(
            [
                "errors" => [
                    [
                        "status" => "400"
                    ]
                ]
            ]
        );

        $this->assertTrue($document->hasErrors());
    }

    /**
     * @test
     */
    public function hasErrorsIsFalse()
    {
        $document = $this->createDocument();

        $this->assertFalse($document->hasErrors());
    }

    /**
     * @test
     */
    public function errorsReturnsErrorArray()
    {
        $document = $this->createDocument(
            [
                "errors" => [
                    [
                        "status" => "400"
                    ]
                ]
            ]
        );

        $this->assertInstanceOf(Error::class, $document->errors()[0]);
    }

    /**
     * @test
     */
    public function errorsReturnsEmptyArray()
    {
        $document = $this->createDocument();

        $this->assertEmpty($document->errors());
    }

    /**
     * @test
     */
    public function errorReturnsFirstError()
    {
        $document = $this->createDocument(
            [
                "errors" => [
                    [
                        "status" => "400"
                    ]
                ]
            ]
        );

        $this->assertInstanceOf(Error::class, $document->error(0));
    }

    /**
     * @test
     */
    public function errorReturnsNull()
    {
        $document = $this->createDocument();

        $this->assertNull($document->error(0));
    }

    private function createDocument(array $body = []): Document
    {
        return Document::createFromArray($body);
    }
}
