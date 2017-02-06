<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\Tests\JsonApi\Response;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
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

        $this->assertFalse($response->hasDocument());
    }

    /**
     * @test
     */
    public function hasDocumentIsTrue()
    {
        $response = $this->createResponse(null, [], []);

        $this->assertTrue($response->hasDocument());
    }

    /**
     * @test
     */
    public function documentReturnsNull()
    {
        $response = $this->createResponse();

        $this->assertNull($response->document());
    }

    /**
     * @test
     */
    public function documentReturnsObject()
    {
        $response = $this->createResponse(null, [], []);

        $this->assertInstanceOf(Document::class, $response->document());
    }

    /**
     * @test
     */
    public function isSuccessfulIsTrue()
    {
        $response = $this->createResponse();

        $this->assertTrue($response->isSuccessful());
    }


    /**
     * @test
     */
    public function isSuccessfulWithStatusCodesIsTrue()
    {
        $response = $this->createResponse(200);

        $this->assertTrue($response->isSuccessful([200]));
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
                        "status" => "400"
                    ]
                ]
            ]
        );

        $this->assertFalse($response->isSuccessful([400]));
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
                        "status" => "400"
                    ]
                ]
            ]
        );

        $this->assertFalse($response->isSuccessful());
    }

    /**
     * @test
     */
    public function isSuccessfulDocumentIsTrue()
    {
        $response = $this->createResponse(200, [], []);

        $this->assertTrue($response->isSuccessfulDocument());
    }

    /**
     * @test
     */
    public function isSuccessfulDocumentIsFalse()
    {
        $response = $this->createResponse(400, [], []);

        $this->assertFalse($response->isSuccessfulDocument([200]));
    }

    /**
     * @test
     */
    public function isSuccessfulDocumentIsFalseBecauseItDoesNotContainADocument()
    {
        $response = $this->createResponse(200);

        $this->assertFalse($response->isSuccessfulDocument([200]));
    }

    /**
     * @test
     */
    public function getProtocolVersion()
    {
        $response = $this->createResponse(200);

        $this->assertEquals("1.1", $response->getProtocolVersion());
    }

    /**
     * @test
     */
    public function withProtocolVersion()
    {
        $response = $this->createResponse(200);

        $newResponse = $response->withProtocolVersion("2.0");

        $this->assertEquals("1.1", $response->getProtocolVersion());
        $this->assertEquals("2.0", $newResponse->getProtocolVersion());
    }

    /**
     * @test
     */
    public function getHeaders()
    {
        $response = $this->createResponse(200, ["A" => "B"]);

        $this->assertEquals(["A" => ["B"]], $response->getHeaders());
    }

    /**
     * @test
     */
    public function getHeader()
    {
        $response = $this->createResponse(200, ["A" => "B"]);

        $this->assertEquals(["B"], $response->getHeader("A"));
    }

    /**
     * @test
     */
    public function getHeaderLine()
    {
        $response = $this->createResponse(200, ["A" => "B"]);

        $this->assertEquals("B", $response->getHeaderLine("A"));
    }

    /**
     * @test
     */
    public function hasHeaderIsTrue()
    {
        $response = $this->createResponse(200, ["A" => "B"]);

        $this->assertTrue($response->hasHeader("A"));
    }

    /**
     * @test
     */
    public function hasHeaderIsFalse()
    {
        $response = $this->createResponse(200);

        $this->assertFalse($response->hasHeader("A"));
    }

    /**
     * @test
     */
    public function withHeader()
    {
        $response = $this->createResponse(200);

        $newResponse = $response->withHeader("A", "B");

        $this->assertEquals([], $response->getHeader("A"));
        $this->assertEquals(["B"], $newResponse->getHeader("A"));
    }

    /**
     * @test
     */
    public function withAddedHeader()
    {
        $response = $this->createResponse(200, ["A" => "B"]);

        $newResponse = $response->withAddedHeader("A", "C");

        $this->assertEquals(["B"], $response->getHeader("A"));
        $this->assertEquals(["B", "C"], $newResponse->getHeader("A"));
    }

    /**
     * @test
     */
    public function withoutHeader()
    {
        $response = $this->createResponse(200, ["A" => "B"]);

        $newResponse = $response->withoutHeader("A");

        $this->assertEquals(["B"], $response->getHeader("A"));
        $this->assertEquals([], $newResponse->getHeader("A"));
    }

    /**
     * @test
     */
    public function getStatusCode()
    {
        $response = $this->createResponse(201);

        $this->assertEquals(201, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function withStatusCode()
    {
        $response = $this->createResponse(200);

        $newResponse = $response->withStatus(201);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(201, $newResponse->getStatusCode());
    }

    /**
     * @test
     */
    public function getBody()
    {
        $response = $this->createResponse(200, [], ["meta" => ["a" => "b"]]);

        $this->assertEquals('{"meta":{"a":"b"}}', $response->getBody()->__toString());
    }

    /**
     * @test
     */
    public function getReasonPhrase()
    {
        $response = $this->createResponse(200);

        $this->assertEquals("OK", $response->getReasonPhrase());
    }

    private function createResponse($statusCode = null, array $headers = [], array $body = null): JsonApiResponse
    {
        return new JsonApiResponse(new Response($statusCode ? $statusCode : null, $headers, json_encode($body)));
    }
}
