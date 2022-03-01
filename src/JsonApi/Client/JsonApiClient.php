<?php

declare(strict_types=1);

namespace BahaaAlhagar\Yang\JsonApi\Client;

use Http\Client\HttpClient;
use Psr\Http\Message\RequestInterface;
use BahaaAlhagar\Yang\JsonApi\Response\JsonApiResponse;
use BahaaAlhagar\Yang\JsonApi\Serializer\JsonDeserializer;
use BahaaAlhagar\Yang\JsonApi\Serializer\DeserializerInterface;

class JsonApiClient
{
    /**
     * @var HttpClient
     */
    private $client;

    /**
     * @var DeserializerInterface
     */
    private $deserializer;

    public function __construct(HttpClient $client, ?DeserializerInterface $deserializer = null)
    {
        $this->client = $client;
        $this->deserializer = $deserializer ?? new JsonDeserializer();
    }

    public function sendRequest(RequestInterface $request): JsonApiResponse
    {
        return new JsonApiResponse($this->client->sendRequest($request), $this->deserializer);
    }
}
