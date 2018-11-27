<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\Tests\JsonApi\Response;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use WoohooLabs\Yang\JsonApi\Exception\ResponseException;
use WoohooLabs\Yang\JsonApi\Response\JsonApiResponse;
use WoohooLabs\Yang\JsonApi\Schema\Document;

class JsonApiResponseTest extends TestCase
{
    /**
     * @test
     */
    public function hasDocumentIsFalse()
    {
        $response = $this->createResponse();

        $document = $response->hasDocument();

        $this->assertFalse($document);
    }

    /**
     * @test
     */
    public function hasDocumentIsTrue()
    {
        $response = $this->createResponse(null, [], []);

        $document = $response->hasDocument();

        $this->assertTrue($document);
    }

    /**
     * @test
     */
    public function documentWhenEmpty()
    {
        $response = $this->createResponse();

        $this->expectException(ResponseException::class);

        $response->document();
    }

    /**
     * @test
     */
    public function documentWhenNotEmpty()
    {
        $response = $this->createResponse(null, [], []);

        $document = $response->document();

        $this->assertInstanceOf(Document::class, $document);
    }

    /**
     * @test
     */
    public function isSuccessfulIsTrue()
    {
        $response = $this->createResponse();

        $isSuccessful = $response->isSuccessful();

        $this->assertTrue($isSuccessful);
    }


    /**
     * @test
     */
    public function isSuccessfulWithStatusCodesIsTrue()
    {
        $response = $this->createResponse(200);

        $isSuccessful = $response->isSuccessful([200]);

        $this->assertTrue($isSuccessful);
    }

    /**
     * @test
     */
    public function isSuccessfulWithStatusCodesIsFalseWhenDocumentContainsErrors()
    {
        $response = $this->createResponse(
            400,
            [],
            [
                "errors" => [
                    [
                        "status" => "400",
                    ],
                ],
            ]
        );

        $isSuccessful = $response->isSuccessful([400]);

        $this->assertFalse($isSuccessful);
    }

    /**
     * @test
     */
    public function isSuccessfulIsFalse()
    {
        $response = $this->createResponse(
            400,
            [],
            [
                "errors" => [
                    [
                        "status" => "400",
                    ],
                ],
            ]
        );

        $isSuccessful = $response->isSuccessful();

        $this->assertFalse($isSuccessful);
    }

    /**
     * @test
     */
    public function isSuccessfulDocumentIsTrue()
    {
        $response = $this->createResponse(200, [], []);

        $isSuccessfulDocument = $response->isSuccessfulDocument();

        $this->assertTrue($isSuccessfulDocument);
    }

    /**
     * @test
     */
    public function isSuccessfulDocumentIsFalse()
    {
        $response = $this->createResponse(400, [], []);

        $isSuccessfulDocument = $response->isSuccessfulDocument([200]);

        $this->assertFalse($isSuccessfulDocument);
    }

    /**
     * @test
     */
    public function isSuccessfulDocumentIsFalseBecauseItDoesNotContainADocument()
    {
        $response = $this->createResponse(200);

        $isSuccessfulDocument = $response->isSuccessfulDocument([200]);

        $this->assertFalse($isSuccessfulDocument);
    }

    private function createResponse($statusCode = null, array $headers = [], array $body = null): JsonApiResponse
    {
        return new JsonApiResponse(new Response($statusCode ? $statusCode : null, $headers, json_encode($body)));
    }
}
