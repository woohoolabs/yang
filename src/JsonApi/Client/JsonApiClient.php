<?php

declare(strict_types=1);

namespace WoohooLabs\Yang\JsonApi\Client;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use WoohooLabs\Yang\JsonApi\Response\JsonApiResponse;
use WoohooLabs\Yang\JsonApi\Serializer\DeserializerInterface;
use WoohooLabs\Yang\JsonApi\Serializer\JsonDeserializer;

class JsonApiClient
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var DeserializerInterface
     */
    private $deserializer;

    public function __construct(ClientInterface $client, ?DeserializerInterface $deserializer = null)
    {
        $this->client = $client;
        $this->deserializer = $deserializer ?? new JsonDeserializer();
    }

    public function sendRequest(RequestInterface $request): JsonApiResponse
    {
        return new JsonApiResponse($this->client->sendRequest($request), $this->deserializer);
    }
}
