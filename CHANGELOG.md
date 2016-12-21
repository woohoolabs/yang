## 1.0.0 - unreleased

ADDED:

CHANGED:

REMOVED:

FIXED:

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

- Added `JsonApiResource::setRelationship()`
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
