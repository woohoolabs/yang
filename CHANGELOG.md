## 0.3.0 - unreleased

ADDED:

- Ability to define options for `JsonApiClient`
- `Document::error($number)` utility method to retrieve only one error
- `Response::isSuccessful()` to determine if the response was successful

CHANGED:

REMOVED:

FIXED:

- PHP version constraint in composer.json
- If the response didn't contain a valid JSON, `Response::document()` could have raised a fatal error
- The type and id weren't included in the request body
- Empty responses weren't considered successful by `JsonApiResponse::isSuccessful()`

## 0.2.0 - 2015-10-20

ADDED:

- Ability to send multiple async requests concurrently
- Ability to determine the type of documents and relationships

CHANGED:

- Shortened getter names (removed the "get" prefix)

## 0.1.0 - 2015-10-18

- Initial release
