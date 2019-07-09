<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\Tests\JsonApi\Response;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use WoohooLabs\Yang\JsonApi\Response\JsonApiResponse;
use function json_encode;

class AbstractResponseTest extends TestCase
{
    /**
     * @test
     */
    public function getProtocolVersion(): void
    {
        $response = $this->createResponse(200);

        $this->assertSame("1.1", $response->getProtocolVersion());
    }

    /**
     * @test
     */
    public function withProtocolVersion(): void
    {
        $response = $this->createResponse(200);

        $newResponse = $response->withProtocolVersion("2.0");

        $this->assertSame("1.1", $response->getProtocolVersion());
        $this->assertSame("2.0", $newResponse->getProtocolVersion());
    }

    /**
     * @test
     */
    public function getHeaders(): void
    {
        $response = $this->createResponse(200, ["A" => "B"]);

        $this->assertSame(["A" => ["B"]], $response->getHeaders());
    }

    /**
     * @test
     */
    public function getHeader(): void
    {
        $response = $this->createResponse(200, ["A" => "B"]);

        $this->assertSame(["B"], $response->getHeader("A"));
    }

    /**
     * @test
     */
    public function getHeaderLine(): void
    {
        $response = $this->createResponse(200, ["A" => "B"]);

        $this->assertSame("B", $response->getHeaderLine("A"));
    }

    /**
     * @test
     */
    public function hasHeaderIsTrue(): void
    {
        $response = $this->createResponse(200, ["A" => "B"]);

        $this->assertTrue($response->hasHeader("A"));
    }

    /**
     * @test
     */
    public function hasHeaderIsFalse(): void
    {
        $response = $this->createResponse(200);

        $this->assertFalse($response->hasHeader("A"));
    }

    /**
     * @test
     */
    public function withHeader(): void
    {
        $response = $this->createResponse(200);

        $newResponse = $response->withHeader("A", "B");

        $this->assertSame([], $response->getHeader("A"));
        $this->assertSame(["B"], $newResponse->getHeader("A"));
    }

    /**
     * @test
     */
    public function withAddedHeader(): void
    {
        $response = $this->createResponse(200, ["A" => "B"]);

        $newResponse = $response->withAddedHeader("A", "C");

        $this->assertSame(["B"], $response->getHeader("A"));
        $this->assertSame(["B", "C"], $newResponse->getHeader("A"));
    }

    /**
     * @test
     */
    public function withoutHeader(): void
    {
        $response = $this->createResponse(200, ["A" => "B"]);

        $newResponse = $response->withoutHeader("A");

        $this->assertSame(["B"], $response->getHeader("A"));
        $this->assertSame([], $newResponse->getHeader("A"));
    }

    /**
     * @test
     */
    public function getStatusCode(): void
    {
        $response = $this->createResponse(201);

        $this->assertSame(201, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function withStatusCode(): void
    {
        $response = $this->createResponse(200);

        $newResponse = $response->withStatus(201);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame(201, $newResponse->getStatusCode());
    }

    /**
     * @test
     */
    public function getBody(): void
    {
        $response = $this->createResponse(200, [], ["meta" => ["a" => "b"]]);

        $this->assertSame('{"meta":{"a":"b"}}', $response->getBody()->__toString());
    }

    /**
     * @test
     */
    public function getReasonPhrase(): void
    {
        $response = $this->createResponse(200);

        $this->assertSame("OK", $response->getReasonPhrase());
    }

    private function createResponse(int $statusCode = 200, array $headers = [], ?array $body = null): JsonApiResponse
    {
        $data = json_encode($body) ?: null;

        return new JsonApiResponse(new Response($statusCode, $headers, $data));
    }
}
