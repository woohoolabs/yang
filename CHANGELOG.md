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
