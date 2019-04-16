# Woohoo Labs. Yang

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]
[![Gitter][ico-gitter]][link-gitter]

**Woohoo Labs. Yang is a PHP framework which helps you to communicate with JSON:API servers more easily.**

## Table of Contents

* [Introduction](#introduction)
    * [Features](#features)
* [Install](#install)
* [Basic Usage](#basic-usage)
    * [Request builder](#request-builder)
    * [HTTP clients](#http-clients)
    * [Response](#response)
    * [Hydration](#hydration)
* [Advanced Usage](#advanced-usage)
    * [Custom serialization](#custom-serialization)
    * [Custom deserialization](#custom-deserialization)
* [Versioning](#versioning)
* [Change Log](#change-log)
* [Contributing](#contributing)
* [Support](#support)
* [Credits](#credits)
* [License](#license)

## Introduction

[JSON:API](https://jsonapi.org) specification
[reached 1.0 on 29th May 2015](https://www.programmableweb.com/news/new-json-api-specification-aims-to-speed-api-development/2015/06/10)
and we also believe it is a big day for RESTful APIs as this specification makes APIs more robust and future-proof
than they have ever been. Woohoo Labs. Yang (named after Yin-Yang) was born to bring efficiency and elegance to your
JSON:API clients, while [Woohoo Labs. Yin](https://github.com/woohoolabs/yin) is its server-side counterpart.

### Features

- 100% [PSR-7](https://www.php-fig.org/psr/psr-7/) compatibility
- 99% [JSON:API 1.1](https://jsonapi.org/) conformance (approximately)
- Provides a Request Builder to setup JSON:API request more easily
- Provides easy-to-use HTTP clients via [PSR-18](https://www.php-fig.org/psr/psr-18/) and [HTTPlug](https://github.com/php-http/httplug)
- Supports [hydrators](#hydration) out-of-the-box in order to easily convert API responses to objects

## Install

The only thing you need before getting started is [Composer](https://getcomposer.org).

### Install an HTTP client and message implementations:

Because Yang requires a HTTP client implementation, you must install one first. You may use [Guzzle 6 Adapter](https://github.com/php-http/guzzle6-adapter)
or any other library of your preference:

```bash
$ composer require php-http/guzzle6-adapter
```

### Install Yang:

To install the latest version of this library, run the command below:

```bash
$ composer require woohoolabs/yang
```

> Note: The tests and examples won't be downloaded by default. You have to use `composer require woohoolabs/yang --prefer-source`
or clone the repository if you need them.

Yang requires PHP 7.1 at least. You may use Yang 1.2 for PHP 7.0 and 0.9 for PHP 5.6.

## Basic Usage

Yang can help you in three ways to communicate with JSON:API servers. The following subsections will cover these topics. 

### Request builder

Yang comes with a powerful request builder with which you are able to setup PSR-7 `Request` objects in a JSON:API compliant way.
For this purpose, you may use the `JsonApiRequestBuilder` class as it can be seen in the following example.

```php
use GuzzleHttp\Psr7\Request;
use WoohooLabs\Yang\JsonApi\Request\JsonApiRequestBuilder

// Instantiate an empty PSR-7 request
$request = new Request("", "")

// Instantiate the request builder
$requestBuilder = new JsonApiRequestBuilder($request);

// Setup the request with general properties
$requestBuilder
    ->setProtocolVersion("1.1")
    ->setMethod("GET")
    ->setUri("https://www.example.com/api/users")
    ->setHeader("Accept-Charset", "utf-8");

// Setup the request with JSON:API specific properties
$requestBuilder
    ->setJsonApiFields(                                      // To define sparse fieldset
        [
            "users" => ["first_name", "last_name"],
            "address" => ["country", "city", "postal_code"]
        ]
    )
    ->setJsonApiIncludes(                                    // To include related resources
        ["address", "friends"]
    )
    ->setJsonApiIncludes(                                    // Or you can pass a string instead
        "address,friends"
    )
    ->setJsonApiSort(                                        // To sort resource collections
        ["last_name", "first_name"]
    )
    ->setJsonApiPage(                                        // To paginate the primary data
        ["number" => 1, "size" => 100]
    )
    ->setJsonApiFilter(                                      // To filter the primary data
        ["first_name" => "John"]
    )
    ->addJsonApiAppliedProfile(                              // To add a profile to the request (JSON:API 1.1 feature)
        ["https://example.com/profiles/last-modified"]
    )
    ->addJsonApiRequestedProfile(                            // To request the server to apply a profile (JSON:API 1.1 feature)
        ["https://example.com/profiles/last-modified"]
    )
    ->addJsonApiRequiredProfile(                             // To require the server to apply a profile (JSON:API 1.1 feature)
        ["https://example.com/profiles/last-modified"]
    );

// Setup the request body
$requestBuilder
    ->setJsonApiBody(                                        // You can pass the content as a JSON string
        '{
           "data": [
             { "type": "user", "id": "1" },
             { "type": "user", "id": "2" }
           ]
         }'
    )
    ->setJsonApiBody(                                        // or you can pass it as an array
        [
            "data" => [
                ["type" => "user", "id" => 1],
                ["type" => "user", "id" => 2],
            ],
        ]
    )
    ->setJsonApiBody(                                        // or as a ResourceObject instance
        new ResourceObject("user", 1)
    );

// Get the composed request
$request = $requestBuilder->getRequest();
```

If you do not want to use the built-in Request Builder, you can freely setup any PSR-7 `RequestInterface` instances
in order to proceed with the next steps:

```php
$request = new Request("", "");
$request = $request
    ->withProtocolVersion("1.1")
    ->withUri(new Uri("https://example.com/api/users?fields[users]=first_name,last_name"))
    ->withHeader("Accept", "application/vnd.api+json")
    ->withHeader("Content-Type", "application/vnd.api+json");
```

### HTTP clients

The library comes with support for [PSR-18](https://www.php-fig.org/psr/psr-18/) and [HTTPlug](https://github.com/php-http/httplug), 
so you can choose how you want to send your requests. If you installed the `php-http/guzzle6-adapter` package, then you
will be able to use Guzzle to do so:

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

Of course, you can use any available HTTP Clients or create a custom HTTP Client thanks to PSR-18 and HTTPlug.

### Response

As soon as you have retrieved the server response, you can start querying it. Yang uses the PSR-7 compatible
`JsonApiResponse` class for this purpose. If you used a HTTP client introduced above, you will automatically
get an object of this type, otherwise you have to take care of instantiating it with the right dependencies:

```php
// Instantiate a JSON:API response object from a PSR-7 response object with the default deserializer
$response = new JsonApiResponse($psr7Response);
```

The `JsonApiResponse` class - above the ones defined by PSR-7 - has some methods to make the handling of JSON:API
responses easier:

```php
// Checks if the response doesn't contain any errors
$isSuccessful = $response->isSuccessful();

// Checks if the response doesn't contain any errors, and has the status codes listed below
$isSuccessful = $response->isSuccessful([200, 202]);

// The same as the isSuccessful() method, but also ensures the response contains a document
$isSuccessfulDocument = $response->isSuccessfulDocument();

// Checks if the response contains a JSON:API document
$hasDocument = $response->hasDocument();

// Retrieves and deserializes the JSON:API document in the response body
$document = $response->document();
```

The `Document` class has various methods too:

```php
// Retrieves the "jsonapi" member as a JsonApiObject instance
$jsonApi = $document->jsonApi();

$jsonApiVersion = $jsonApi->version();
$jsonApiMeta = $jsonApi->meta();

// Checks if the document has the "meta" member
$hasMeta = $document->hasMeta();

// Retrieves the "meta" member as an array
$meta = $document->meta();

// Checks if the document has any links
$hasLinks = $document->hasLinks();

// Retrieves the "links" member as a DocumentLinks object
$links = $document->links();

// Checks if the document has any errors
$hasErrors = $document->hasErrors();

// Counts the number of errors in the document
$errorCount = $document->errorCount();

// Retrieves the "errors" member as an array of Error objects
$errors = $document->errors();

// Retrieves the first error as an Error object or throws an exception if it is missing
$firstError = $document->error(0);

// Checks if the document contains a single resource as its primary data
$isSingleResourceDocument = $document->isSingleResourceDocument();

// Checks if the document contains a collection of resources as its primary data
$isResourceCollectionDocument = $document->isResourceCollectionDocument();

// Checks if the document contains any primary data
$hasPrimaryData = $document->hasAnyPrimaryResources();

// Returns the primary resource as a ResourceObject instance if the document is a single-resource document
// or throws an exception otherwise or when the document is empty
$primaryResource = $document->primaryResource();

// Returns the primary resources as an array of ResourceObject instances if the document is a collection document
// or throws an exception otherwise
$primaryResources = $document->primaryResources();

// Checks if there are any included resources in the document
$hasIncludedResources = $document->hasAnyIncludedResources();

// Checks if there is a specific included resource in the document
$isUserIncluded = $document->hasIncludedResource("user", "1234");

// Retrieves all the included resources as an array of ResourceObject instances
$includedResources = $document->includedResources();
```

The `DocumentLinks` class features the following methods:

```php
// Checks if the "self" link is present
$hasSelf = $links->hasSelf();

// Returns the "self" link as a Link object or throws an exception if it is missing
$selfLink = $links->self();

// Checks if the "related" link is present
$hasRelated = $links->hasRelated();

// Returns the "related" link as a Link object or throws an exception if it is missing
$relatedLink = $links->related();

// Checks if the "first" link is present
$hasFirst = $links->hasFirst();

// Returns the "first" link as a Link object or throws an exception if it is missing
$firstLink = $links->first();

// Checks if the "last" link is present
$hasLast = $links->hasLast();

// Returns the "last" link as a Link object or throws an exception if it is missing
$lastLink = $links->last();

// Checks if the "prev" link is present
$hasPrev = $links->hasPrev();

// Returns the "prev" link as a Link object or throws an exception if it is missing
$prevLink = $links->prev();

// Checks if the "next" link is present
$hasNext = $links->hasNext();

// Returns the "next" link as a Link object or throws an exception if it is missing
$nextLink = $links->next();

// Checks if a specific link is present
$hasLink = $links->hasLink("next");

// Returns a specific link as a Link object or throws an exception if it is missing
$link = $links->link("next");

// Checks if the there is any profile defined
$hasProfiles = $links->hasAnyProfiles();

// Retrieves the profiles as an array of ProfileLink objects
$profiles = $links->profiles();

// Checks if there is a specific profile defined
$hasProfile = $links->hasProfile("https://example.com/profiles/last-modified");

// Retrieves a specific profile as a ProfileLink object
$profile = $links->profile("https://example.com/profiles/last-modified");
```

The `Error` class has the following methods:

```php
// Returns the "id" member of the error
$id = $firstError->id();

// Checks if the error has the "meta" member
$hasMeta = $firstError->hasMeta();

// Retrieves the "meta" member as an array
$meta = $firstError->meta();

// Checks if the error has any links
$hasLinks = $firstError->hasLinks();

// Retrieves the "links" member as an ErrorLinks object
$links = $firstError->links();

// Returns the "status" member
$status = $firstError->status();

// Returns the "code" member
$code = $firstError->code();

// Returns the "title" member
$title = $firstError->title();

// Returns the "detail" member
$detail = $firstError->detail();

// Checks if the error has the "source" member
$hasSource = $firstError->hasSource();

// Returns the "source" member as an ErrorSource object
$source = $firstError->source();
```

The `ResourceObject` class has the following methods:

```php
// Returns the type of the resource
$type = $primaryResource->type();

// Returns the id of the resource
$id = $primaryResource->id();

// Checks if the resource has the "meta" member
$hasMeta = $primaryResource->hasMeta();

// Returns the "meta" member as an array
$meta = $primaryResource->meta();

// Checks if the resource has any links
$hasLinks = $primaryResource->hasLinks();

// Returns the "links" member as a ResourceLinks object
$links = $primaryResource->links();

// Returns the attributes of the resource as an array
$attributes = $primaryResource->attributes();

// Returns the ID and attributes of the resource as an array
$idAndAttributes = $primaryResource->idAndAttributes();

// Checks if the resource has a specific attribute
$hasFirstName = $primaryResource->hasAttribute("first_name");

// Returns an attribute of the resource or null if it is missing
$firstName = $primaryResource->attribute("first_name");

// Returns an attribute of the resource or the default value if it is missing
$lastName = $primaryResource->attribute("last_name", "");

// Returns all relationships of the resource as an array of Relationship objects
$relationships = $primaryResource->relationships();

// Checks if the resource has a specific relationship
$hasAddress = $primaryResource->hasRelationship("address");

// Returns a relationship of the resource as a Relationship object or throws an exception if it is missing
$relationship = $primaryResource->relationship("address");
```
The `Relationship` object supports the following methods:

```php
// Checks if it is a to-one relationship
$isToOneRelationship = $relationship->isToOneRelationship(); 

// Checks if it is a to-many relationship
$isToManyRelationship = $relationship->isToManyRelationship();

// Returns the name of the relationship
$name = $relationship->name();

// Checks if the relationship has the "meta" member
$hasMeta = $relationship->hasMeta();

// Returns the "meta" member of the relationship as an array
$meta = $relationship->meta();

// Returns the "links" member of the relationship as a RelationshipLinks object
$links = $relationship->links();

// Returns the first resource linkage of the relationship as an array (e.g.: ["type" => "address", "id" => "123"])
// or null if there isn't any related data
$resourceLinkage = $relationship->firstResourceLink();
 
// Returns the resource linkage as an array of array (e.g.: [["type" => "address", "id" => "123"]])
$resourceLinkage = $relationship->resourceLinks();

// Checks if a specific resource object is included
$isIncluded = $relationship->hasIncludedResource("address", "abcd");

// Returns the resource object of a to-one relationship as a `ResourceObject` instance
// or throws an exception otherwise or when the relationship is empty
$resource = $relationship->resource();

// Returns the resource objects of a to-many relationship as an array of `ResourceObject` instances
// or throws an exception otherwise
$resources = $relationship->resources();
```

### Hydration

JSON:API responses with many related resources are not easily to process with the above approach. For example, if you want
to retrieve the value of an attribute of a related resource, you need the following code:

```php
$dogResource = $response->document()->primaryResource();

$breedName = $dogResource->relationship("breed")->resource()->attribute("name");
```

This is a bit too much code to write, and it gets a lot worse when you want to map complex response documents with many
relationships to objects:

```php
$dogResource = $response->document()->primaryResource();

$dog = new stdClass();
$dog->name = $dogResource->attribute("name");
$dog->age = $dogResource->attribute("age");
$dog->breed = $dogResource->relationship("breed")->resource()->attribute("name");
foreach ($dogResource->relationship("owners")->resources() as $ownerResource) {
    $owner = new stdClass();
    $owner->name = $ownerResource->attribute("name");
    
    $addressResource = $ownerResource->relationship("address")->resource();
    $owner->address = new stdClass();
    $owner->address->city = $addressResource->attribute("city");
    $owner->address->addressLine = $addressResource->attribute("city");

    $dog->owners[] = $owner;
}
```

This is the situation when using a hydrator can help you. Currently, Yang has only one hydrator, the `ClassDocumentHydrator` which - if the
response was successful - maps the specified document to an `stdClass` along with all the resource attributes and relationships.
It means that errors, links, meta data won't be present in the returned object. However, relationships are very easy to
access now.

Let's use the document from the last example for demonstrating the power of hydrators:

```php
// Check if hydration is possible
if ($document->isSingleResourceDocument()) {
    return;
}

// Hydrate the document to an stdClass
$hydrator = new ClassDocumentHydrator();
$dog = $hydrator->hydrateSingleResource($response->document());
```

That's all you need to do in order to create the same `$dog` object as in the first example! Now, you can display its properties:

```php
echo "Dog:\n";
echo "Name : " . $dog->name . "\n";
echo "Breed: " . $dog->breed->name . "\n\n";

echo "Owners:\n";
foreach ($dog->owners as $owner) {
    echo "Name   : " . $dog->owner->name . "\n";
    echo "Address: " . $dog->owner->address->city . ", " . $dog->owner->address->addressLine . "\n";
    echo "------------------\n";
}
```

> Note: The method `ClassDocumentHydrator::hydrateSingleResource()` throws `DocumentException` when the document doesn't
have any primary data or if the primary data is a collection. Otherwise - when the primary data is a single resource -
an `stdObject` along with all the attributes and relationships is returned.

Additionally, you may use the `ClassHydrator::hydrateCollection()` method for retrieving many dogs:

```php
// Check if hydration is possible
if ($document->hasAnyPrimaryResources() === false) {
    return;
}

// Hydrate the document to an array of stdClass
$hydrator = new ClassDocumentHydrator();
$dogs = $hydrator->hydrateCollection($response->document());
```

> Note: The method `ClassHydrator::hydrateCollection()` throws `DocumentException` when the primary data is a single resource.
Otherwise - when the primary data is a collection of resources - an array of `stdObject`s along with all the attributes and
relationship is returned.

Furthermore, there is a `hydrate()` method available for you when you don't care if the primary data is a single resource
or a collection of resources.

> Note: The method `ClassDocumentHydrator::hydrate()` returns an empty array when the document doesn't have any primary data.
It returns an array containing a single `stdClass` if the primary data is a single resource. Otherwise - when the primary data
is a collection of resources - an array of `stdObject`s is returned.

## Advanced Usage

### Custom serialization

Sometimes you might need to be tricky to serialize the request body in a custom way. For example, if you
dispatch a server request internally (within the original request), then you can send the request body as an array thanks
to this feature - so you don't need to serialize at client-side and then deserialize at server-size. If you use
Woohoo Labs. Yin and a [custom deserializer](https://github.com/woohoolabs/yin/#custom-deserialization) at server-side,
then this is an easy task to do.

At client-side, if you use Yang with the [Request Builder](#request-builder), then you only have to pass a second
constructor argument to it like below to take advantage of custom serialization:

```php
// Instantiate a PSR-7 request
$request = new Request();

// Instantiate your custom serializer
$mySerializer = new MyCustomSerializer();

// Instantiate the request builder with a custom serializer
$requestBuilder = new JsonApiRequestBuilder($request, $mySerializer);
```

You only have to make sure that your custom serializer implements the `SerializerInterface`.

### Custom deserialization

Sometimes you might need to be tricky to deserialize a server response in a custom way. For example, if you
dispatch a server request internally (within the original request), then you can receive the response body as an array thanks
to this feature - so you don't need to serialize at server-side and then deserialize at client-size. If you use
Woohoo Labs. Yin and a [custom serializer](https://github.com/woohoolabs/yin/#custom-serialization) at server-side,
then this is an easy task to do.

At client-side, if you use Yang with the default [HTTP Clients](#http-clients) then you only have to pass a second
constructor argument to them like below to take advantage of custom deserialization:

```php
use Http\Adapter\Guzzle6\Client;

// Instantiate the Guzzle HTTP Client
$guzzleClient = Client::createWithConfig([]);

// Instantiate your custom deserializer
$myDeserializer = new MyCustomDeserializer();

// Instantiate the syncronous JSON:API Client with a custom deserializer
$syncClient = new JsonApiClient($guzzleClient, $myDeserializer);

// Instantiate the asyncronous JSON:API Client with a custom deserializer
$asyncClient = new JsonApiAsyncClient($guzzleClient, $myDeserializer);
```

Otherwise pass your deserializer to the `JsonApiResponse` as its second argument like below:

```php
// Instantiate a JSON:API response from a PSR-7 response with a custom deserializer
$response = new JsonApiResponse($psr7Response, new MyCustomDeserializer());
```

You only have to make sure that your custom deserializer implements the `DeserializerInterface`.

## Examples

Have a look at the [examples directory](https://github.com/woohoolabs/yang/blob/master/examples/) for a really basic
example.

## Versioning

This library follows [SemVer v2.0.0](https://semver.org/).

## Change Log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

Woohoo Labs. Yang has a PHPUnit test suite. To run the tests, run the following command from the project folder:

``` bash
$ phpunit
```

Additionally, you may run `docker-compose up` or `make test` in order to execute the tests.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Support

Please see [SUPPORT](SUPPORT.md) for details.

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
[ico-gitter]: https://badges.gitter.im/woohoolabs/yang.svg

[link-packagist]: https://packagist.org/packages/woohoolabs/yang
[link-travis]: https://travis-ci.org/woohoolabs/yang
[link-scrutinizer]: https://scrutinizer-ci.com/g/woohoolabs/yang/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/woohoolabs/yang
[link-downloads]: https://packagist.org/packages/woohoolabs/yang
[link-author]: https://github.com/kocsismate
[link-contributors]: ../../contributors
[link-gitter]: https://gitter.im/woohoolabs/yang?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge
