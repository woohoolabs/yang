<?php
namespace WoohooLabs\Yang\JsonApi;

use GuzzleHttp\Client;

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
}
