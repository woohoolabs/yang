<?php
declare(strict_types=1);

require "../vendor/autoload.php";

use GuzzleHttp\Psr7\Request;
use Http\Adapter\Guzzle6\Client as GuzzleClient;
use WoohooLabs\Yang\JsonApi\Client\JsonApiClient;
use WoohooLabs\Yang\JsonApi\Request\JsonApiRequestBuilder;

// Create request
$requestBuilder = new JsonApiRequestBuilder(new Request("", ""));
$request = $requestBuilder
    ->fetch()
    ->setUri("https://example.com/api/index.php?example=book&id=1")
    ->setJsonApiFields(["book" => "title,pages,authors,publisher"])
    ->setJsonApiIncludes(["authors", "publisher"])
    ->getRequest();

// Create an HTTP Client
$guzzleClient = GuzzleClient::createWithConfig([]);
$client = new JsonApiClient($guzzleClient);

// Retrieve response
$response = $client->sendRequest($request);

// Print the response status code
echo "Status: " . $response->getStatusCode() . "<br/>";

// Print the response body
echo "Body:<pre>";
print_r($response->document()->toArray());

// Print the book publisher
echo "Publisher:";
print_r($response->document()->primaryResource()->relationship("publisher")->resource()->toArray());
