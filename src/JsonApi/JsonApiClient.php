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

    /**
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->client = new Client($config);
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

    /**
     * @param \Psr\Http\Message\RequestInterface[] $requests
     * @return \Psr\Http\Message\ResponseInterface[]
     */
    public function requestConcurrent(array $requests)
    {
        foreach ($requests as $key => $request) {
            $requests[$key] = $this->client->sendAsync($request);
        }

        return \GuzzleHttp\Promise\unwrap($requests);
    }
}
