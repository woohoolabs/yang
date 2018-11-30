<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\Tests\JsonApi\Schema;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yang\JsonApi\Exception\DocumentException;
use WoohooLabs\Yang\JsonApi\Schema\Document;
use WoohooLabs\Yang\JsonApi\Schema\Error\Error;
use WoohooLabs\Yang\JsonApi\Schema\JsonApiObject;
use WoohooLabs\Yang\JsonApi\Schema\Link\DocumentLinks;

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
                "data" => [],
            ]
        );

        $array = $document->toArray();

        $this->assertSame(
            [
                "jsonapi" => [
                    "version" => "1.0",
                ]
            ],
            $array
        );
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
                    "self" => "https://example.com/api/users/abcd"
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

        $array = $document->toArray();

        $this->assertSame(
            [
                "jsonapi" => [
                    "version" => "1.0",
                ],
                "meta" => [
                    "a" => "b",
                ],
                "links" => [
                    "self" => [
                        "href" => "https://example.com/api/users/abcd",
                    ],
                ],
                "data" => [
                    "type" => "user",
                    "id" => "abcd",
                ],
                "errors" => [
                    [
                        "status" => "401",
                    ],
                ],
                "included" => [
                    [
                        "type" => "user",
                        "id" => "efgh",
                    ],
                ],
            ],
            $array
        );
    }

    /**
     * @test
     */
    public function jsonApiReturnsObject()
    {
        $document = $this->createDocument([]);

        $jsonApi = $document->jsonApi();

        $this->assertEquals(new JsonApiObject("1.0", []), $jsonApi);
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

        $hasMeta = $document->hasMeta();

        $this->assertTrue($hasMeta);
    }

    /**
     * @test
     */
    public function hasMetaIsFalse()
    {
        $document = $this->createDocument([]);

        $hasMeta = $document->hasMeta();

        $this->assertFalse($hasMeta);
    }

    /**
     * @test
     */
    public function metaReturnsMetaObject()
    {
        $document = $this->createDocument(
            [
                "meta" => [
                    "a" => "b",
                ],
            ]
        );

        $meta = $document->meta();

        $this->assertSame(["a" => "b"], $meta);
    }

    /**
     * @test
     */
    public function hasLinksIsTrue()
    {
        $document = $this->createDocument(
            [
                "links" => [
                    "self" => "https://example.com/api/users/abcd",
                ],
            ]
        );

        $hasLinks = $document->hasLinks();

        $this->assertTrue($hasLinks);
    }

    /**
     * @test
     */
    public function hasLinksIsFalse()
    {
        $document = $this->createDocument([]);

        $hasLinks = $document->hasLinks();

        $this->assertFalse($hasLinks);
    }

    /**
     * @test
     */
    public function linksReturnsObject()
    {
        $document = $this->createDocument([]);

        $links = $document->links();

        $this->assertEquals(new DocumentLinks([], []), $links);
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
                ],
            ]
        );

        $isSingleResourceDocument = $document->isSingleResourceDocument();

        $this->assertTrue($isSingleResourceDocument);
    }

    /**
     * @test
     */
    public function isSingleResourceDocumentIsTrueWhenNull()
    {
        $document = $this->createDocument(
            [
                "data" => null,
            ]
        );

        $isSingleResourceDocument = $document->isSingleResourceDocument();

        $this->assertTrue($isSingleResourceDocument);
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
                    ],
                ],
            ]
        );

        $isSingleResourceDocument = $document->isSingleResourceDocument();

        $this->assertFalse($isSingleResourceDocument);
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
                    ],
                ],
            ]
        );

        $isResourceCollectionDocument = $document->isResourceCollectionDocument();

        $this->assertTrue($isResourceCollectionDocument);
    }

    /**
     * @test
     */
    public function isResourceCollectionDocumentIsTrueWhenEmpty()
    {
        $document = $this->createDocument(
            [
                "data" => [],
            ]
        );

        $isResourceCollectionDocument = $document->isResourceCollectionDocument();

        $this->assertTrue($isResourceCollectionDocument);
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
                ],
            ]
        );

        $isResourceCollectionDocument = $document->isResourceCollectionDocument();

        $this->assertFalse($isResourceCollectionDocument);
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
                ],
            ]
        );

        $hasAnyPrimaryResources = $document->hasAnyPrimaryResources();

        $this->assertTrue($hasAnyPrimaryResources);
    }

    /**
     * @test
     */
    public function hasAnyPrimaryResourcesIsFalseWhenNull()
    {
        $document = $this->createDocument(
            [
                "data" => null,
            ]
        );

        $hasAnyPrimaryResources = $document->hasAnyPrimaryResources();

        $this->assertFalse($hasAnyPrimaryResources);
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
                    ],
                ],
            ]
        );

        $hasAnyPrimaryResources = $document->hasAnyPrimaryResources();

        $this->assertTrue($hasAnyPrimaryResources);
    }

    /**
     * @test
     */
    public function hasAnyPrimaryResourcesIsFalseWhenEmpty()
    {
        $document = $this->createDocument(
            [
                "data" => [],
            ]
        );

        $hasAnyPrimaryResources = $document->hasAnyPrimaryResources();

        $this->assertFalse($hasAnyPrimaryResources);
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
                        "status" => "400",
                    ],
                ],
            ]
        );

        $hasErrors = $document->hasErrors();

        $this->assertTrue($hasErrors);
    }

    /**
     * @test
     */
    public function hasErrorsIsFalse()
    {
        $document = $this->createDocument();

        $hasErrors = $document->hasErrors();

        $this->assertFalse($hasErrors);
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
                        "status" => "400",
                    ],
                ],
            ]
        );

        $error = $document->errors()[0];

        $this->assertInstanceOf(Error::class, $error);
    }

    /**
     * @test
     */
    public function errorsReturnsEmptyArray()
    {
        $document = $this->createDocument();

        $this->expectException(DocumentException::class);

        $document->errors();
    }

    /**
     * @test
     */
    public function errorCount()
    {
        $document = $this->createDocument(
            [
                "errors" => [
                    [
                        "status" => "400",
                    ],
                    [
                        "status" => "400",
                    ],
                ],
            ]
        );

        $errorCount = $document->errorCount();

        $this->assertEquals(2, $errorCount);
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
                        "status" => "400",
                    ],
                ],
            ]
        );

        $error = $document->error(0);

        $this->assertInstanceOf(Error::class, $error);
    }

    /**
     * @test
     */
    public function errorReturnsNull()
    {
        $document = $this->createDocument();

        $this->expectException(DocumentException::class);

        $document->error(0);
    }

    private function createDocument(array $body = []): Document
    {
        return Document::fromArray($body);
    }
}
