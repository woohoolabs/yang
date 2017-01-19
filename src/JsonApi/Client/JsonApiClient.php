<?php
namespace WoohooLabs\Yang\JsonApi\Client;

use Http\Client\HttpClient;
use Psr\Http\Message\RequestInterface;
use WoohooLabs\Yang\JsonApi\Deserializer\JsonDeserializer;
use WoohooLabs\Yang\JsonApi\Deserializer\DeserializerInterface;
use WoohooLabs\Yang\JsonApi\Response\JsonApiResponse;

class JsonApiClient
{
    /**
     * @var \Http\Client\HttpClient
     */
    private $client;

    /**
     * @var DeserializerInterface
     */
    private $deserializer;

    public function __construct(HttpClient $client, DeserializerInterface $deserializer = null)
    {
        $this->client = $client;
        $this->deserializer = $deserializer ? $deserializer : new JsonDeserializer();
    }

    /**
     * @param \Psr\Http\Message\RequestInterface $request
     * @return \WoohooLabs\Yang\JsonApi\Response\JsonApiResponse
     */
    public function sendRequest(RequestInterface $request)
    {
        return new JsonApiResponse($this->client->sendRequest($request), $this->deserializer);
    }
}
