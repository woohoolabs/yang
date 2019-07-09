<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\Tests\JsonApi\Response;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use WoohooLabs\Yang\JsonApi\Exception\ResponseException;
use WoohooLabs\Yang\JsonApi\Response\JsonApiResponse;
use function json_encode;

class JsonApiResponseTest extends TestCase
{
    /**
     * @test
     */
    public function hasDocumentIsFalse(): void
    {
        $response = $this->createResponse();

        $document = $response->hasDocument();

        $this->assertFalse($document);
    }

    /**
     * @test
     */
    public function hasDocumentIsTrue(): void
    {
        $response = $this->createResponse(200, [], []);

        $document = $response->hasDocument();

        $this->assertTrue($document);
    }

    /**
     * @test
     */
    public function documentWhenEmpty(): void
    {
        $response = $this->createResponse();

        $this->expectException(ResponseException::class);

        $response->document();
    }

    /**
     * @test
     */
    public function documentWhenNotEmpty(): void
    {
        $response = $this->createResponse(200, [], []);

        $response->document();

        $this->addToAssertionCount(1);
    }

    /**
     * @test
     */
    public function isSuccessfulIsTrue(): void
    {
        $response = $this->createResponse();

        $isSuccessful = $response->isSuccessful();

        $this->assertTrue($isSuccessful);
    }

    /**
     * @test
     */
    public function isSuccessfulWithStatusCodesIsTrue(): void
    {
        $response = $this->createResponse(200);

        $isSuccessful = $response->isSuccessful([200]);

        $this->assertTrue($isSuccessful);
    }

    /**
     * @test
     */
    public function isSuccessfulWithStatusCodesIsFalseWhenDocumentContainsErrors(): void
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
    public function isSuccessfulIsFalse(): void
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
    public function isSuccessfulDocumentIsTrue(): void
    {
        $response = $this->createResponse(200, [], []);

        $isSuccessfulDocument = $response->isSuccessfulDocument();

        $this->assertTrue($isSuccessfulDocument);
    }

    /**
     * @test
     */
    public function isSuccessfulDocumentIsFalse(): void
    {
        $response = $this->createResponse(400, [], []);

        $isSuccessfulDocument = $response->isSuccessfulDocument([200]);

        $this->assertFalse($isSuccessfulDocument);
    }

    /**
     * @test
     */
    public function isSuccessfulDocumentIsFalseBecauseItDoesNotContainADocument(): void
    {
        $response = $this->createResponse(200);

        $isSuccessfulDocument = $response->isSuccessfulDocument([200]);

        $this->assertFalse($isSuccessfulDocument);
    }

    private function createResponse(int $statusCode = 200, array $headers = [], ?array $body = null): JsonApiResponse
    {
        $data = json_encode($body) ?: null;

        return new JsonApiResponse(new Response($statusCode, $headers, $data));
    }
}
