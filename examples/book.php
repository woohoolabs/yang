<?php
require "../vendor/autoload.php";

use GuzzleHttp\Psr7\Request;
use WoohooLabs\Yang\JsonApi\Request\RequestBuilder;
use WoohooLabs\Yang\JsonApi\JsonApiClient;

$requestBuilder = new RequestBuilder(new Request("", ""));
$request = $requestBuilder
    ->fetch()
    ->uri("http://yin.local/index.php?example=book&id=1")
    ->fields(["book" => "title,pages"])
    ->includes(["authors", "publisher"])
    ->getRequest();

$client = new JsonApiClient();
$response = $client->request($request);

echo "Status: " . $response->getStatusCode() . "<br/>";
echo "Body:<pre>";
print_r(json_decode($response->getBody()->getContents(), true));
