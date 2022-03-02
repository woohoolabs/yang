<?php

declare(strict_types=1);

namespace BahaaAlhagar\Yang\Tests\JsonApi\Serializer;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use BahaaAlhagar\Yang\JsonApi\Serializer\JsonDeserializer;

class JsonDeserializerTest extends TestCase
{
    /**
     * @test
     */
    public function deserialize(): void
    {
        $body = [
            "data" => [
                "type" => "a",
                "id" => "1",
            ],
        ];

        $response = $this->createResponse($body);
        $deserializer = new JsonDeserializer();

        $this->assertSame($body, $deserializer->deserialize($response));
    }

    private function createResponse(array $body): Response
    {
        $data = \json_encode($body) ?: null;

        return new Response(200, [], $data);
    }
}
