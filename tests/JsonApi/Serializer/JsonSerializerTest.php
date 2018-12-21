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
    public function serializeWhenRequestIsInitiallyEmpty()
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
    public function serializeWhenRequestInitiallyHasContent()
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
    public function serializeWhenString()
    {
        $serializer = new JsonSerializer();

        $request = $serializer->serialize($this->createRequest(), "abc");

        $this->assertSame("abc", $request->getBody()->__toString());
    }

    /**
     * @test
     */
    public function serializeWhenNull()
    {
        $serializer = new JsonSerializer();

        $request = $serializer->serialize($this->createRequest(), null);

        $this->assertSame("", $request->getBody()->__toString());
    }

    /**
     * @test
     */
    public function serializeWhenContentInvalidType()
    {
        $serializer = new JsonSerializer();

        $this->expectException(SerializationException::class);

        $serializer->serialize($this->createRequest(), 123);
    }

    private function createRequest(?array $body = null): Request
    {
        return new Request("", "", [], $body !== null ? json_encode($body) : null);
    }
}
