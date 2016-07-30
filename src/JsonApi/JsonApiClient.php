<?php
namespace WoohooLabs\Yang\JsonApi;

use Http\Client\HttpClient;
use Psr\Http\Message\RequestInterface;
use WoohooLabs\Yang\JsonApi\Response\JsonApiResponse;

class JsonApiClient
{
    /**
     * @var \Http\Client\HttpClient
     */
    protected $client;

    /**
     * @param \Http\Client\HttpClient $client
     */
    public function __construct(HttpClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param \Psr\Http\Message\RequestInterface $request
     * @return \WoohooLabs\Yang\JsonApi\Response\JsonApiResponse
     */
    public function sendRequest(RequestInterface $request)
    {
        return new JsonApiResponse($this->client->sendRequest($request));
    }
}
