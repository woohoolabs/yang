<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\Tests\JsonApi\Serializer;

use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;
use WoohooLabs\Yang\JsonApi\Exception\SerializationException;
use WoohooLabs\Yang\JsonApi\Serializer\JsonSerializer;
use function json_encode;

class JsonSerializerTest extends TestCase
{
    /**
     * @test
     */
    public function serializeWhenRequestIsInitiallyEmpty(): void
    {
        $serializer = new JsonSerializer();

        $request = $serializer->serialize(
            $this->createRequest(),
            [
                "data" => [
                    "type" => "a",
                    "id" => "1",
                ],
            ]
        );

        $this->assertSame(
            json_encode(
                [
                    "data" => [
                        "type" => "a",
                        "id" => "1",
                    ],
                ]
            ),
            $request->getBody()->__toString()
        );
    }

    /**
     * @test
     */
    public function serializeWhenRequestInitiallyHasContent(): void
    {
        $serializer = new JsonSerializer();

        $request = $serializer->serialize(
            $this->createRequest(["abcd"]),
            [
                "data" => [
                    "type" => "a",
                    "id" => "1",
                ],
            ]
        );

        $this->assertSame(
            json_encode(
                [
                    "data" => [
                        "type" => "a",
                        "id" => "1",
                    ],
                ]
            ),
            $request->getBody()->__toString()
        );
    }

    /**
     * @test
     */
    public function serializeWhenString(): void
    {
        $serializer = new JsonSerializer();

        $request = $serializer->serialize($this->createRequest(), "abc");

        $this->assertSame("abc", $request->getBody()->__toString());
    }

    /**
     * @test
     */
    public function serializeWhenNull(): void
    {
        $serializer = new JsonSerializer();

        $request = $serializer->serialize($this->createRequest(), null);

        $this->assertSame("", $request->getBody()->__toString());
    }

    /**
     * @test
     */
    public function serializeWhenContentInvalidType(): void
    {
        $serializer = new JsonSerializer();

        $this->expectException(SerializationException::class);

        $serializer->serialize($this->createRequest(), 123);
    }

    private function createRequest(?array $body = null): Request
    {
        $data = null;
        if ($body !== null) {
            $data = json_encode($body) ?: null;
        }

        return new Request("GET", "", [], $data);
    }
}
