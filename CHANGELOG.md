## 2.2.0 - unreleased

ADDED:

CHANGED:

DEPRECATED:

REMOVED:

FIXED:

## 2.1.0 - 2019-04-17

ADDED:

- [#13](https://github.com/woohoolabs/yang/issues/13): `DocumentHydratorInterface` and `ClassDocumentHydrator` in order to fix some issues with the `HydratorInterface` and `ClassHydrator`
- [#15](https://github.com/woohoolabs/yang/issues/15): New accessor and mutator methods for `WoohooLabs\Yang\JsonApi\Request\ResourceObject`: `id()`, `setId()`, `type()`, `setType()`,
`attributes()`, `relationships()`

DEPRECATED:

- `HydratorInterface`: use the `DocumentHydratorInterface` instead
- `ClassHydrator`: use the `ClassDocumentHydrator` instead

## 2.0.0 - 2019-03-04

### Changes since beta1:

CHANGED:

- Apply the Woohoo Labs. Coding Standard

### Full change set:

ADDED:

- JSON:API 1.1 related features:
    - Support for Profiles
    - Support for `type` links in errors
- `Document::errorCount()` to easily count errors in the document
- Support for defining a default value when using the `ResourceObject::attribute()` method

CHANGED:

- Improve type-safety by eliminating `null` return values (__BREAKING CHANGE__):
    - `JsonApiResponse::document()` throws an exception instead of returning null if the response doesn't contain a document.
    - `Document::primaryResources()` throws an exception if the document is a single-resource or error document 
    - `Document::primaryResource()` throws an exception if the document is a collection or error document or the primary resource is missing 
    - `Document::resource()` throws an exception instead of returning null if the requested resource is missing
    - `Document::error()` throws an exception instead of returning null if the document does not contain the requested error
    - `Relationship::resources()` throws an exception instead of returning an empty array if the relationship is a to-one
    - `Relationship::resource()` throws an exception instead of returning null if the relationship is a to-many or empty
    - `Relationship::resourceBy()` throws an exception instead of returning null if the requested resource is missing
    - `ResourceObject::relationship()` throws an exception instead of returning null if the requested relationship is missing
- Move errors, links, and resources to their own namespace (__BREAKING CHANGE__):
    - `WoohooLabs\Yang\JsonApi\Schema\Error` to `WoohooLabs\Yang\JsonApi\Schema\Error\Error`
    - `WoohooLabs\Yang\JsonApi\Schema\ErrorSource` to `WoohooLabs\Yang\JsonApi\Schema\Error\ErrorSource`
    - `WoohooLabs\Yang\JsonApi\Schema\Link` to `WoohooLabs\Yang\JsonApi\Schema\Link\Link`
    - `WoohooLabs\Yang\JsonApi\Schema\ResourceObjects` to `WoohooLabs\Yang\JsonApi\Schema\Resource\ResourceObjects`
    - `WoohooLabs\Yang\JsonApi\Schema\ResourceObject` to `WoohooLabs\Yang\JsonApi\Schema\Resource\ResourceObject`
- Return separate classes instead of a general `Links` for the different types of links (__BREAKING CHANGE__):
    - `DocumentLinks` when using `Document::links()`
    - `ResourceLinks` when using `ResourceObject::links()`
    - `RelationshipLinks` when using `Relationship::links()`
    - `ErrorLinks` when using `Error::links()`
- `JsonSerializer::serialize()` will throw a `RequestException` instead of `LogicException` if the body is of invalid type (__BREAKING CHANGE__)
- Rename `JsonApi` to `JsonApiObject` (__BREAKING CHANGE__)
- Apply the Woohoo Labs. Coding Standard

REMOVED:

- The generic `Link` class (__BREAKING CHANGE__)

FIXED:

- Issues with 0 when converting to array

## 2.0.0-beta1 - 2018-12-04

ADDED:

- JSON:API 1.1 related features:
    - Support for Profiles
    - Support for `type` links in errors
- `Document::errorCount()` to easily count errors in the document
- Support for defining a default value when using the `ResourceObject::attribute()` method

CHANGED:

- Improve type-safety by eliminating `null` return values (__BREAKING CHANGE__):
    - `JsonApiResponse::document()` throws an exception instead of returning null if the response doesn't contain a document
    - `Document::primaryResources()` throws an exception if the document is a single-resource or error document 
    - `Document::primaryResource()` throws an exception if the document is a collection or error document or the primary resource is missing 
    - `Document::resource()` throws an exception instead of returning null if the requested resource is missing
    - `Document::error()` throws an exception instead of returning null if the document does not contain the requested error
    - `Relationship::resources()` throws an exception instead of returning an empty array if the relationship is a to-one
    - `Relationship::resource()` throws an exception instead of returning null if the relationship is a to-many or empty
    - `Relationship::resourceBy()` throws an exception instead of returning null if the requested resource is missing
    - `ResourceObject::relationship()` throws an exception instead of returning null if the requested relationship is missing
- Move errors, links, and resources to their own namespace (__BREAKING CHANGE__):
    - `WoohooLabs\Yang\JsonApi\Schema\Error` to `WoohooLabs\Yang\JsonApi\Schema\Error\Error`
    - `WoohooLabs\Yang\JsonApi\Schema\ErrorSource` to `WoohooLabs\Yang\JsonApi\Schema\Error\ErrorSource`
    - `WoohooLabs\Yang\JsonApi\Schema\Link` to `WoohooLabs\Yang\JsonApi\Schema\Link\Link`
    - `WoohooLabs\Yang\JsonApi\Schema\ResourceObjects` to `WoohooLabs\Yang\JsonApi\Schema\Resource\ResourceObjects`
    - `WoohooLabs\Yang\JsonApi\Schema\ResourceObject` to `WoohooLabs\Yang\JsonApi\Schema\Resource\ResourceObject`
- Return separate classes instead of a general `Links` for the different types of links (__BREAKING CHANGE__):
    - `DocumentLinks` when using `Document::links()`
    - `ResourceLinks` when using `ResourceObject::links()`
    - `RelationshipLinks` when using `Relationship::links()`
    - `ErrorLinks` when using `Error::links()`
- `JsonSerializer::serialize()` will throw a `RequestException` instead of `LogicException` if the body is of invalid type (__BREAKING CHANGE__)
- Rename `JsonApi` to `JsonApiObject` (__BREAKING CHANGE__)

REMOVED:

- The generic `Link` class (__BREAKING CHANGE__)

FIXED:

- Issues with 0 when converting to array

## 1.5.0 - 2018-11-15

ADDED:

- Support for PSR-18 and HTTPlug 2.0

CHANGED:

- Update PHPUnit to 7.0

## 1.4.1 - 2017-12-24

ADDED:

- `ClassHydrator::hydrateObject()` method

## 1.4.0 - 2017-12-12

ADDED:

- Support for hydrating collections via `HydratorInterface::hydrateCollection()`

## 1.3.2 - 2017-12-06

FIXED:

- [#9](https://github.com/woohoolabs/yang/issues/9): Resources with an ID of "0" disappear when using `ClassHydrator`
- Other issues with "0" affecting `ResourceObject::toString()` and `JsonApiRequestBuilder::toString()`

## 1.3.1 - 2017-11-23

FIXED:

- [#8](https://github.com/woohoolabs/yang/pull/8): Fix for unexpected behaviour when To-One relationship data is null

## 1.3.0 - 2017-10-17

CHANGED:

- Increased minimum PHP version requirement to 7.1

## 1.2.0 - 2017-10-10

ADDED:

- Ability to hydrate responses to objects via `ClassHydrator`
- Ability to retrieve all relationships of a resource via `ResourceObject::getRelationships()`

## 1.1.0 - 2017-09-01

ADDED:

- [#4](https://github.com/woohoolabs/yang/pull/4): Allow relationship's resource identifier objects to have a "meta" value

CHANGED:

- `ToManyRelationship::addResourceIdentifier()` uses fluent interface
- Added missing parameter type declarations

## 1.0.1 - 2017-03-10

ADDED:

- `ResourceObject::idAndAttributes()` method

## 1.0.0 - 2017-02-28

ADDED:

- Support for custom serialization
- Ability to configure the `JsonDeserializer`

CHANGED:

- Increased minimum PHP version requirement to 7.0
- Renamed `DefaultDeserializer` to `JsonDeserializer`
- Moved `JsonApiClient` and `JsonApiAsyncClient` into the `JsonApi\Client` namespace
- Moved deserializers into the `JsonApi\Serializer` namespace along with serializers

## 0.9.0 - 2016-12-21

ADDED:

- Better support for "about" links

CHANGED:

- `Error::createFromArray()` performs more type checks before instantiating an `Error` object
- Renamed `ErrorSource::fromArray()` to `ErrorSource::createFromArray()`
- `ErrorSource::createFromArray()` performs more type checks before instantiating an `ErrorSource` object
- Added `Relationships::createFromArray()` instead of the constructor
- Renamed `Relationship::resourceLink` to `Relationship::firstResourceLink`

FIXED:

- Representing error status as string as per the spec

## 0.8.0 - 2016-12-20

CHANGED:

- `Link::createFromArray()` performs type checks before instantiating a `Link` object
- Added `ResourceObject::createFromArray()` instead of the constructor
- `ResourceObject::createFromArray()` performs more type checks before instantiating a `ResourceObject`
- The "jsonapi" member is always present when using `Document::toArray()`
- The "links" member won't be present in a relationship when it is empty when using `ResourceObject::toArray()`

REMOVED:

- `Links::hasLinks()` method
- `JsonApi::hasJsonApi()` method

FIXED:

- The "jsonapi" member was incorrectly recognized as "jsonApi"
- If the "jsonapi" member doesn't define the `version`, "1.0" will be the default now as per the spec
- Invocation of `Links::hasLink()` and thus `Links::hasSelf()` etc. methods resulted in an infinite loop
- `Relationship::toArray()` showed the "data" member incorrectly for to-one relationships

## 0.7.0 - 2016-12-19

ADDED:

- `JsonApiResource::setRelationship()` method
- Unit tests
- Docker support to run tests
- `Links::hasAnyLinks()` method

CHANGED:

- Added `JsonApiResource::setToOneRelationship()` instead of `JsonApiResource::setToOneResourceIdentifier()`
- Added `JsonApiResource::setToManyRelationship()` instead of `JsonApiResource::addToManyResourceIdentifier()`
- The "links" member won't be present in the "data" member when it is empty when using `Document::toArray()`
- Renamed several methods of `ResourceObjects` (removed `get` prefixes)
- Renamed `JsonApiRelationshipInterface` class to `RelationshipInterface`
- Renamed `JsonApiResource` class to `ResourceObject`
- Renamed `JsonApiToManyRelationship` class to `ToManyRelationship`
- Renamed `JsonApiToOneRelationship` class to `ToOneRelationship`

FIXED:

- `JsonApiResource` didn't add relationships to the request
- The `type` constructor argument became required for `JsonApiResource`
- `JsonApiRequestBuilder::setPort()` didn't do anything
- `JsonApiRequestBuilder::setJsonApi*()` methods didn't work as intended
- `JsonApiRequestBuilder::update()` will now set the request method to "PATCH" instead of "UPDATE"
- Error objects are correctly listed under the "errors" member when using `Document::toArray()`
- Correctly transforming "included" member when using `Document::toArray()`
- `ResourceObjects::isSingleResourceDocument()` `Document::isResourceDocument()` returned wrong value when "data" member was null 
- `ResourceObjects::hasAnyPrimaryResources()` returned wrong value when "data" member was null 

## 0.6.0 - 2016-12-18

CHANGED:

- Renamed `JsonApiAsyncClient::requestConcurrent()` to `JsonApiAsyncClient::sendConcurrentAsyncRequests()`
- Renamed `Document::hasPrimaryResources()` to `Docment::hasAnyPrimaryResources()`
- Renamed `Document::hasIncludedResources()` to `Docment::hasAnyIncludedResources()`
- Renamed almost all methods in `JsonApiRequestBuilder`
- Do not sort included resources by type and id
- Improved documentation

## 0.5.0 - 2016-11-01

ADDED:

- Support for custom deserialization

CHANGED:

- Updated minimum PHP version requirement to 5.6
- Renamed `Resource` to `ResourceObject` and `Resources` to `ResourceObjects`

## 0.4.0 - 2016-09-16

ADDED:

- Support for HTTPlug library to abstract the HTTP client away
- `JsonApiResource::setAttributes()` method

CHANGED:

- Moved asyncronous functionalities of `JsonApiClient` into `JsonApiAsyncClient`
- `JsonApiClient::request()` method was renamed to `JsonApiClient::sendRequest()`
- `JsonApiAsyncClient::requestAsync()` method was renamed to `JsonApiAsyncClient::sendAsyncRequest()`

FIXED:

- Error when serialized response body is apparently empty even though it contains data

## 0.3.0 - 2016-07-30

ADDED:

- Ability to define options for `JsonApiClient`
- `Document::error($number)` utility method to retrieve only one error
- `Response::isSuccessful()` to determine if the response was successful
- `Response::isSuccessfulDocument()` to determine if the response was successful and contains a document
- Support for PHPUnit 5.0

CHANGED:

- Improved performance

FIXED:

- PHP version constraint in composer.json
- If the response didn't contain a valid JSON, `Response::document()` could have raised a fatal error
- The type and id weren't included in the request body
- Empty responses weren't considered successful by `JsonApiResponse::isSuccessful()`
- Empty relationship data can now be discovered
- Sorting does not happen on resource ID

## 0.2.0 - 2015-10-20

ADDED:

- Ability to send multiple async requests concurrently
- Ability to determine the type of documents and relationships

CHANGED:

- Shortened getter names (removed the "get" prefix)

## 0.1.0 - 2015-10-18

- Initial release
