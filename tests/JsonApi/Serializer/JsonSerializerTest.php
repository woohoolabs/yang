<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\Tests\JsonApi\Serializer;

use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;
use WoohooLabs\Yang\JsonApi\Serializer\JsonSerializer;

class JsonSerializerTest extends TestCase
{
    /**
     * @test
     */
    public function serialize()
    {
        $body = [
            "data" => [
                "type" => "a",
                "id" => "1",
            ]
        ];

        $response = $this->createRequest($body);
        $serializer = new JsonSerializer();

        $this->assertSame(json_encode($body), $serializer->serialize($response, $body)->getBody()->__toString());
    }

    private function createRequest(array $body): Request
    {
        return new Request("", "", [], json_encode($body));
    }
}
