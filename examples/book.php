<?php
require "../vendor/autoload.php";

use GuzzleHttp\Psr7\Request;
use Http\Adapter\Guzzle6\Client as GuzzleClient;
use WoohooLabs\Yang\JsonApi\JsonApiClient;
use WoohooLabs\Yang\JsonApi\Request\JsonApiRequestBuilder;

$requestBuilder = new JsonApiRequestBuilder(new Request("", ""));
$request = $requestBuilder
    ->fetch()
    ->uri("http://yin.local/index.php?example=book&id=1")
    ->withFields(["book" => "title,pages,authors,publisher"])
    ->withIncludes(["authors", "publisher"])
    ->getRequest();

$guzzleClient = GuzzleClient::createWithConfig([]);
$client = new JsonApiClient($guzzleClient);
$response = $client->request($request);

echo "Status: " . $response->getStatusCode() . "<br/>";
echo "Body:<pre>";
print_r($response->document()->toArray());

echo "Publisher:";
print_r($response->document()->primaryResource()->relationship("publisher")->resource()->toArray());
