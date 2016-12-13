# Woohoo Labs. Yang

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

**Woohoo Labs. Yin is a PHP framework which helps you to communicate with JSON:API servers more easily.**

## Table of Contents

* [Introduction](#introduction)
    * [Features](#features)
* [Install](#install)
* [Basic Usage](#basic-usage)
    * [Request builder](#request-builder)
    * [HTTP client](#http-client)
    * [Response](#response)
* [Advanced Usage](#advanced-usage)
    * [Custom deserialization](#custom-deserialization)
* [Versioning](#versioning)
* [Change Log](#change-log)
* [Contributing](#contributing)
* [Credits](#credits)
* [License](#license)

## Introduction

[JSON:API](http://jsonapi.org) specification
[reached 1.0 on 29th May 2015](http://www.programmableweb.com/news/new-json-api-specification-aims-to-speed-api-development/2015/06/10)
and we also believe it is a big day for RESTful APIs as this specification makes APIs more robust and future-proof
than they have ever been. Woohoo Labs. Yang (named after Yin-Yang) was born to bring efficiency and elegance to your
JSON:API clients, while [Woohoo Labs. Yin](https://woohoolabs/yin) is its server-side counterpart.

### Features

- 100% [PSR-7](http://www.php-fig.org/psr/psr-7/) compatibility
- 99% [JSON:API 1.0](http://jsonapi.org/) compatibility (approximately)
- Provides a Request Builder to setup JSON:API request more easily
- Provides an easy-to-use HTTP client via [HTTPlug](https://github.com/php-http/httplug)

## Install

You need [Composer](https://getcomposer.org) to install this library. Run the command below and you will get the latest
version:

```bash
$ composer require woohoolabs/yang
```

Yang needs PHP 5.6+.

If you want to use Guzzle 6 as the HTTP client implementation, you should run the following command too: 

```bash
$ composer require php-http/guzzle6-adapter
```

## Basic Usage

Yang can help you in 3 ways to communicate with JSON:API servers. The following subsections will cover these topics. 

### Request builder

Yang comes with a powerful request builder with which you are able to setup PSR-7 `Request` objects. For this purpose,
you may use the `JsonApiRequestBulder` class as it can be seen in the following example.

```php
use GuzzleHttp\Psr7\Request;
use WoohooLabs\Yang\JsonApi\Request\JsonApiRequestBuilder

// Instantiate an empty PSR-7 request
$request = new Request("", "")

// Instantiate the request builder
$requestBuilder = new JsonApiRequestBuilder($request);

// Setup the request with general properties
$requestBuilder
    ->protocolVersion("1.1")
    ->uri("https://www.example.com/api/users")
    ->withHeader("Accept-Charset", "utf-8");

// Setup the request with JSON:API specific properties
$requestBuilder
    ->withFields(                                               // To define sparse fieldset
        [
            "users" => ["first_name", "last_name"],
            "address" => ["country", "city", "postal_code"]
        ]
    )
    ->withIncludes(                                             // To include related resources
        ["address", "friends"]
    )
    ->withIncludes(                                             // Or you can pass a string instead
        "address,friends"
    )
    ->withSort(                                                 // To sort resource collections
        ["last_name", "first_name"]
    )
    ->withPage(                                                 // To paginate the primary data
        ["number" => 1, "size" => 100]
    )
    ->withFilter(                                               // To filter the primary data
        ["first_name" => "John"]
    );
    
// Setup the request with a body
$requestBuilder
    ->withBody(                                                 // You can pass a JSON as string
        '{
           "data": [
             { "type": "user", "id": "1" },
             { "type": "user", "id": "2" }
           ]
         }'
    )
    ->withBody(                                                 // or you can pass it as an array
        [
            "data" => [
                ["type" => "user", "id" => 1],
                ["type" => "user", "id" => 2],
            ]
        ]
    )
    ->withBody(                                                 // or a JsonApiResource instance
        new JsonApiResource("user", 1)
    );

// Get the composed request
$request = $requestBuilder->getRequest();
```

If you do not want to use the built-in Request Builder, you can freely setup any PSR-7 `RequestInterface` instances
in order to proceed with the next steps like this:

```php
$request = new Request("", "");
$request
    ->withProtocolVersion("1.1")
    ->withUri(new Uri("https://example.com/api/users?fields[users]=first_name,last_name"))
    ->withHeader("Accept", "application/vnd.api+json")
    ->withHeader("Content-Type", "application/vnd.api+json");
```

### HTTP client

The library comes with support for [HTTPlug](https://github.com/php-http/httplug) which is a clever HTTP client
abstraction so you can choose how you want to send your requests. If you installed the `php-http/guzzle6-adapter`
package, then you will be able to use Guzzle to do so:

```php
use Http\Adapter\Guzzle6\Client;

// Instantiate the Guzzle HTTP Client
$guzzleClient = Client::createWithConfig([]);

// Instantiate the syncronous JSON:API Client
$client = new JsonApiClient($guzzleClient);

// Send the request syncronously to retrieve the response
$response = $client->sendRequest($request);

// Instantiate the asyncronous JSON:API Client
$client = new JsonApiAsyncClient($guzzleClient);

// Send the request asyncronously to retrieve a promise
$promise = $client->sendAsyncRequest($request);

// Send multiple request asyncronously to retrieve an array of promises
$promises = $client->sendConcurrentAsyncRequests([$request, $request]);
```

Of course, you can use any available HTTP Clients or create you custom HTTP Client thanks to HTTPlug.

### Response

As soon as you have retrieved the server response, you can start querying it. Yang uses the PSR-7 compatible
`JsonApiResponse` class for this purpose. If you used the HTTP client introduced above, you will automatically
get an object of this kind, otherwise you have to take care of instantiating it with the right dependencies.

```php
// Instantiate a JSON:API response from a PSR-7 response with the default deserializer
$response = new JsonApiResponse($psr7Response);

// Instantiate a JSON:API response from a PSR-7 response with a custom deserializer
$response = new JsonApiResponse($psr7Response, new MyCustomDeserializer());
```

The `JsonApiResponse` class - above the ones defined by PSR-7 - has some methods to make handling JSON:API responses
easier:

```php
// Checks if the response contains a JSON:API Document
$hasDocument = $response->hasDocument();

// Checks if the response doesn't contain errors
$isSuccessful = $response->isSuccessful();

// Checks if the response has the status codes listed below and doesn't contain errors
$isSuccessful = $response->isSuccessful([200, 202]);

// The same as the isSuccessful() method, but also ensures the response contains a document
$isSuccessfulDocument = $response->isSuccessfulDocument();

// Retrieves and deserializes the JSON:API document in the response body
$document = $response->document();
```

The `Document` class has various useful methods too:

```php
// Checks if the document has a jsonapi member
$hasJsonApi = $document->hasJsonApi();

// Retrieves the jsonapi member as a JsonApi instance
$jsonApi = $document->jsonApi();

$jsonApiVersion = $jsonApi->version();
$jsonApiMeta = $jsonApi->meta();

// Checks if the document has a meta member
$hasMeta = $document->hasMeta();

// Retrieves the meta member as an array
$meta = $document->meta();

// Checks if the document has a links member
$hasLinks = $document->hasLinks();

// Retrieves the links member as a Links instance
$links = $document->links();

$selfLink = $links->self();         // Returns a Link instance or null
$firstLink = $links->first();       // Returns a Link instance or null
$nextLink = $links->link("next");   // Returns a Link instance or null

// Checks if the document has errors
$hasErrors = $document->hasErrors();

// Retrieves the errors member as an array of Error instances
$errors = $document->errors();

// Retrieves the first error as an Error instance or null
$firstError = $document->error(0);

// Checks if the document contains a single resource as the primary data
$isSingleResourceDocument = $document->isSingleResourceDocument();

// Checks if the document contains a collection of resources as the primary data
$isResourceCollectionDocument = $document->isResourceCollectionDocument();

// Checks if the document contains any primary data
$hasPrimaryData = $document->hasAnyPrimaryResources();

// Returns the primary resource as a ResourceObject instance or null if it is a collection document
$primaryResource = $document->primaryResource();

// Returns the primary resources as an array of ResourceObject instances or null if it is a single resource document
$primaryResources = $document->primaryResources();

// Checks if there is any included resources in the document
$hasIncludedResources = $document->hasAnyIncludedResources();

// Checks if there is a specific included resource in the document
$isUserIncluded = $document->hasIncludedResource("user", "abcdefg");

// Retrieves all the included resources as an array of ResourceObject instances
$includedResources = $document->includedResources();
```

The `ResourceObject` class has useful methods too: 

```php
// Returns the type of the resource
$type = $primaryResource->type();

// Returns the id of the resource
$id = $primaryResource->id();

// Checks if the resource has meta information
$hasMeta = $primaryResource->hasMeta();

// Returns the meta information as an array
$meta = $primaryResource->meta();

// Checks if the resource has links
$hasLinks = $primaryResource->hasLinks();

// Returns the links member as a Links instance
$links = $primaryResource->links();

// Returns the attributes of the resource
$attributes = $primaryResource->attributes();

// Checks if the resource has an attribute
$hasFirstName = $primaryResource->hasAttribute("first_name");

// Returns an attribute of the resource or null if it is missing
$firstName = $primaryResource->attribute("first_name");

// Checks if the resource has a relationship
$hasAddress = $primaryResource->hasRelationship("address");

// Returns a relationship of the resource or null if it is missing
$address = $primaryResource->relationship("address");
```

## Advanced Usage

### Custom deserialization

## Examples

## Versioning

This library follows [SemVer v2.0.0](http://semver.org/).

## Change Log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

Woohoo Labs. Yang has a PHPUnit test suite. To run the tests, run the following command from the project folder
after you have copied phpunit.xml.dist to phpunit.xml:

``` bash
$ phpunit
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Máté Kocsis][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see the [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/woohoolabs/yang.svg
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg
[ico-travis]: https://img.shields.io/travis/woohoolabs/yang/master.svg
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/woohoolabs/yang.svg
[ico-code-quality]: https://img.shields.io/scrutinizer/g/woohoolabs/yang.svg
[ico-downloads]: https://img.shields.io/packagist/dt/woohoolabs/yang.svg

[link-packagist]: https://packagist.org/packages/woohoolabs/yang
[link-travis]: https://travis-ci.org/woohoolabs/yang
[link-scrutinizer]: https://scrutinizer-ci.com/g/woohoolabs/yang/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/woohoolabs/yang
[link-downloads]: https://packagist.org/packages/woohoolabs/yang
[link-author]: https://github.com/kocsismate
[link-contributors]: ../../contributors
