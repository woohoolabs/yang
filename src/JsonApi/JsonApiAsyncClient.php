<?php
namespace WoohooLabs\Yang\JsonApi;

use Http\Client\HttpAsyncClient;
use Psr\Http\Message\RequestInterface;

class JsonApiAsyncClient
{
    /**
     * @var \Http\Client\HttpAsyncClient
     */
    protected $client;

    /**
     * @param \Http\Client\HttpAsyncClient $client
     */
    public function __construct(HttpAsyncClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param \Psr\Http\Message\RequestInterface $request
     * @return \Http\Promise\Promise
     */
    public function sendAsyncRequest(RequestInterface $request)
    {
        return $this->client->sendAsyncRequest($request);
    }

    /**
     * @param \Psr\Http\Message\RequestInterface[] $requests
     * @return \Http\Promise\Promise[]
     */
    public function requestConcurrent(array $requests)
    {
        foreach ($requests as $key => $request) {
            $requests[$key] = $this->sendAsyncRequest($request);
        }

        return $requests;
    }
}
