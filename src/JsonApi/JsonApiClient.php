<?php
namespace WoohooLabs\Yang\JsonApi;

use GuzzleHttp\Client;
use Psr\Http\Message\RequestInterface;
use WoohooLabs\Yang\JsonApi\Response\JsonApiResponse;

class JsonApiClient
{
    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * @param \Psr\Http\Message\RequestInterface $request
     * @return \WoohooLabs\Yang\JsonApi\Response\JsonApiResponse
     */
    public function request(RequestInterface $request)
    {
        return new JsonApiResponse($this->client->send($request));
    }

    /**
     * @param \Psr\Http\Message\RequestInterface $request
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function requestAsync(RequestInterface $request)
    {
        return $this->client->sendAsync($request);
    }
}
