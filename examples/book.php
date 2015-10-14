<?php
require "../vendor/autoload.php";

use GuzzleHttp\Psr7\Request;
use WoohooLabs\Yang\JsonApi\Request\JsonApiRequestBuilder;
use WoohooLabs\Yang\JsonApi\JsonApiClient;

$requestBuilder = new JsonApiRequestBuilder(new Request("", ""));
$request = $requestBuilder
    ->fetch()
    ->uri("http://yin.local/index.php?example=book&id=1")
    ->withFields(["book" => "title,pages,authors,publisher"])
    ->withIncludes(["authors", "publisher"])
    ->getRequest();

$client = new JsonApiClient();
$response = $client->request($request);

echo "Status: " . $response->getStatusCode() . "<br/>";
echo "Body:<pre>";
print_r($response->getDocument()->toArray());
