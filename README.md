# Woohoo Labs. Yang

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

**Woohoo Labs. Yin is a PHP framework which helps you to ease the communication with JSON:API servers.**

## Table of Contents

* [Introduction](#introduction)
** [Features](#features)
* [Install](#install)
* [Basic Usage](#basic-usage)
** [Request builder](#request-builder)
** [HTTP client](#http-client)
** [Response hydrator](#response)
* [Advanced Usage](#advanced-usage)
** [Custom deserialization](#custom-deserialization)
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
    ->withBody(                                                 // You can pass a valid JSON string
        '{
           "data": [
             { "type": "user", "id": "1" },
             { "type": "user", "id": "2" }
           ]
         }'
    )
    ->withBody(                                                 // or you can pass an array
        [
            "data" => [
                ["type" => "user", "id" => 1],
                ["type" => "user", "id" => 2],
            ]
        ]
    )
    ->withBody(                                                 // or you can pass a JsonApiResource instance
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
    ->withHeader("Accept", ""application/vnd.api+json"")
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

// Instantiate the JSON:API Client
$client = new JsonApiClient($guzzleClient);

// Send the request to retrieve the response
$response = $client->sendRequest($request);
```

Of course, you can use any available HTTP Clients or create you custom HTTP Client thanks to HTTPlug.

### Response hydrator

As soon as you have the server response, you can hydrate it 

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
