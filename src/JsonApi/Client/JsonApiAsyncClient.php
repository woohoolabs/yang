<?php

declare(strict_types=1);

namespace BahaaAlhagar\Yang\JsonApi\Client;

use Http\Promise\Promise;
use Http\Client\HttpAsyncClient;
use Psr\Http\Message\RequestInterface;

class JsonApiAsyncClient
{
    /**
     * @var HttpAsyncClient
     */
    private $client;

    public function __construct(HttpAsyncClient $client)
    {
        $this->client = $client;
    }

    public function sendAsyncRequest(RequestInterface $request): Promise
    {
        return $this->client->sendAsyncRequest($request);
    }

    /**
     * @param RequestInterface[] $requests
     * @return Promise[]
     */
    public function sendConcurrentAsyncRequests(array $requests): array
    {
        $result = [];

        foreach ($requests as $key => $request) {
            $result[$key] = $this->sendAsyncRequest($request);
        }

        return $result;
    }
}
