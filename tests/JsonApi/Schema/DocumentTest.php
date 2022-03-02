<?php

declare(strict_types=1);

namespace BahaaAlhagar\Yang\Tests\JsonApi\Schema;

use PHPUnit\Framework\TestCase;
use BahaaAlhagar\Yang\JsonApi\Schema\Document;
use BahaaAlhagar\Yang\JsonApi\Schema\JsonApiObject;
use BahaaAlhagar\Yang\JsonApi\Schema\Link\DocumentLinks;
use BahaaAlhagar\Yang\JsonApi\Exception\DocumentException;

class DocumentTest extends TestCase
{
    /**
     * @test
     */
    public function toArrayWhenEmpty(): void
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
                ],
            ],
            $array
        );
    }

    /**
     * @test
     */
    public function toArray(): void
    {
        $document = $this->createDocument(
            [
                "jsonapi" => [
                    "version" => "1.0",
                ],
                "meta" => [
                    "a" => "b",
                ],
                "links" => [
                    "self" => "https://example.com/api/users/abcd",
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
    public function jsonApiReturnsObject(): void
    {
        $document = $this->createDocument([]);

        $jsonApi = $document->jsonApi();

        $this->assertEquals(new JsonApiObject("1.0", []), $jsonApi);
    }

    /**
     * @test
     */
    public function hasMetaIsTrue(): void
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
    public function hasMetaIsFalse(): void
    {
        $document = $this->createDocument([]);

        $hasMeta = $document->hasMeta();

        $this->assertFalse($hasMeta);
    }

    /**
     * @test
     */
    public function metaReturnsMetaObject(): void
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
    public function hasLinksIsTrue(): void
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
    public function hasLinksIsFalse(): void
    {
        $document = $this->createDocument([]);

        $hasLinks = $document->hasLinks();

        $this->assertFalse($hasLinks);
    }

    /**
     * @test
     */
    public function linksReturnsObject(): void
    {
        $document = $this->createDocument([]);

        $links = $document->links();

        $this->assertEquals(new DocumentLinks([], []), $links);
    }

    /**
     * @test
     */
    public function isSingleResourceDocumentIsTrue(): void
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
    public function isSingleResourceDocumentIsTrueWhenNull(): void
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
    public function isSingleResourceDocumentIsFalse(): void
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
    public function isResourceCollectionDocumentIsTrue(): void
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

        $isResourceCollectionDocument = $document->isResourceCollectionDocument();

        $this->assertTrue($isResourceCollectionDocument);
    }

    /**
     * @test
     */
    public function isResourceCollectionDocumentIsTrueWhenEmpty(): void
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
    public function isResourceCollectionDocumentIsFalse(): void
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
    public function hasAnyPrimaryResourcesIsTrueWhenHasSinglePrimaryData(): void
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
    public function hasAnyPrimaryResourcesIsFalseWhenNull(): void
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
    public function hasAnyPrimaryResourcesIsTrueWhenHasCollectionPrimaryData(): void
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
    public function hasAnyPrimaryResourcesIsFalseWhenEmpty(): void
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
    public function hasErrorsIsTrue(): void
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
    public function hasErrorsIsFalse(): void
    {
        $document = $this->createDocument();

        $hasErrors = $document->hasErrors();

        $this->assertFalse($hasErrors);
    }

    /**
     * @test
     */
    public function errorsReturnsErrorArray(): void
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

        $this->assertSame("400", $error->status());
    }

    /**
     * @test
     */
    public function errorsReturnsEmptyArray(): void
    {
        $document = $this->createDocument();

        $errors = $document->errors();

        $this->assertEmpty($errors);
    }

    /**
     * @test
     */
    public function errorCount(): void
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
    public function errorReturnsFirstError(): void
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

        $this->assertSame("400", $error->status());
    }

    /**
     * @test
     */
    public function errorReturnsNull(): void
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
