<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\Tests\JsonApi\Serializer;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use WoohooLabs\Yang\JsonApi\Serializer\JsonDeserializer;

class JsonDeserializerTest extends TestCase
{
    /**
     * @test
     */
    public function deserialize()
    {
        $body = [
            "data" => [
                "type" => "a",
                "id" => "1",
            ]
        ];

        $response = $this->createResponse($body);
        $deserializer = new JsonDeserializer();

        $this->assertSame($body, $deserializer->deserialize($response));
    }

    private function createResponse(array $body): Response
    {
        return new Response(200, [], json_encode($body));
    }
}
