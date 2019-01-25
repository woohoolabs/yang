<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\JsonApi\Request;

use Psr\Http\Message\RequestInterface;
use WoohooLabs\Yang\JsonApi\Serializer\JsonSerializer;
use WoohooLabs\Yang\JsonApi\Serializer\SerializerInterface;
use function array_key_exists;
use function http_build_query;
use function implode;
use function is_array;
use function is_numeric;
use function parse_str;
use function parse_url;

class JsonApiRequestBuilder
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var string
     */
    private $method;

    /**
     * @var string
     */
    private $protocolVersion;

    /**
     * @var string
     */
    private $scheme;

    /**
     * @var string
     */
    private $host;

    /**
     * @var int
     */
    private $port;

    /**
     * @var string
     */
    private $path;

    /**
     * @var array
     */
    private $queryString;

    /**
     * @var array
     */
    private $headers;

    /**
     * @var string[]
     */
    private $appliedProfiles;

    /**
     * @var string[]
     */
    private $requestedProfiles;

    /**
     * @var string[]
     */
    private $requiredProfiles;

    /**
     * @var mixed
     */
    private $body;

    public function __construct(RequestInterface $request, ?SerializerInterface $serializer = null)
    {
        $this->request = $request;
        $this->serializer = $serializer ?? new JsonSerializer();
        $this->initialize();
    }

    public function initialize(): void
    {
        $this->method = "GET";
        $this->protocolVersion = "";
        $this->scheme = "http";
        $this->host = "";
        $this->path = "";
        $this->queryString = [];
        $this->headers = [];
        $this->appliedProfiles = [];
        $this->requestedProfiles = [];
        $this->requiredProfiles = [];
    }

    public function fetch(): JsonApiRequestBuilder
    {
        return $this->setMethod("GET");
    }

    public function create(): JsonApiRequestBuilder
    {
        return $this->setMethod("POST");
    }

    public function update(): JsonApiRequestBuilder
    {
        return $this->setMethod("PATCH");
    }

    public function delete(): JsonApiRequestBuilder
    {
        return $this->setMethod("DELETE");
    }

    public function setMethod(string $method): JsonApiRequestBuilder
    {
        $this->method = $method;

        return $this;
    }

    public function setProtocolVersion(string $version): JsonApiRequestBuilder
    {
        $this->protocolVersion = $version;

        return $this;
    }

    public function http(): JsonApiRequestBuilder
    {
        return $this->setUriScheme("http");
    }

    public function https(): JsonApiRequestBuilder
    {
        return $this->setUriScheme("https");
    }

    public function setUri(string $uri): JsonApiRequestBuilder
    {
        $parsedUri = parse_url($uri);

        if ($parsedUri === false) {
            return $this;
        }

        if ($this->isBlankKey($parsedUri, "scheme") === false) {
            $this->scheme = $parsedUri["scheme"];
        }

        if ($this->isBlankKey($parsedUri, "port") === false) {
            $this->port = $parsedUri["port"];
        }

        if ($this->isBlankKey($parsedUri, "host") === false) {
            $this->host = $parsedUri["host"];
        }

        if ($this->isBlankKey($parsedUri, "path") === false) {
            $this->path = $parsedUri["path"];
        }

        if ($this->isBlankKey($parsedUri, "query") === false) {
            parse_str($parsedUri["query"], $this->queryString);
        }

        return $this;
    }

    public function setUriScheme(string $scheme): JsonApiRequestBuilder
    {
        $this->scheme = $scheme;

        return $this;
    }

    public function setUriHost(string $host): JsonApiRequestBuilder
    {
        $this->host = $host;

        return $this;
    }

    public function setUriPort(int $port): JsonApiRequestBuilder
    {
        $this->port = $port;

        return $this;
    }

    public function setUriPath(string $path): JsonApiRequestBuilder
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @param string|array $value
     */
    public function setUriQueryParam(string $name, $value): JsonApiRequestBuilder
    {
        $this->queryString[$name] = $value;

        return $this;
    }

    /**
     * @param string|string[] $value
     */
    public function setHeader(string $name, $value): JsonApiRequestBuilder
    {
        $this->headers[$name] = $value;

        return $this;
    }

    public function setJsonApiFields(array $fields): JsonApiRequestBuilder
    {
        $this->setArrayQueryParam("fields", $fields);

        return $this;
    }

    public function setJsonApiSort(array $sort): JsonApiRequestBuilder
    {
        $this->setListQueryParam("sort", $sort);

        return $this;
    }

    public function setJsonApiPage(array $pagination): JsonApiRequestBuilder
    {
        $this->setArrayQueryParam("page", $pagination);

        return $this;
    }

    public function setJsonApiFilter(array $filter): JsonApiRequestBuilder
    {
        $this->setArrayQueryParam("filter", $filter);

        return $this;
    }

    /**
     * @param array|string $includes
     */
    public function setJsonApiIncludes($includes): JsonApiRequestBuilder
    {
        $this->setListQueryParam("include", $includes);

        return $this;
    }

    public function addJsonApiAppliedProfile(string $profile): JsonApiRequestBuilder
    {
        $this->appliedProfiles[] = $profile;

        return $this;
    }

    public function addJsonApiRequestedProfile(string $profile): JsonApiRequestBuilder
    {
        $this->requestedProfiles[] = $profile;

        return $this;
    }

    public function addJsonApiRequiredProfile(string $profile): JsonApiRequestBuilder
    {
        if (isset($this->queryString["profile"]) === false) {
            $this->queryString["profile"] = $profile;
        } else {
            $this->queryString["profile"] .= " " . $profile;
        }

        return $this;
    }

    /**
     * @param ResourceObject|array|string $body
     */
    public function setJsonApiBody($body): JsonApiRequestBuilder
    {
        if ($body instanceof ResourceObject) {
            $this->body = $body->toArray();
        } else {
            $this->body = $body;
        }

        return $this;
    }

    public function getRequest(): RequestInterface
    {
        $request = $this->request->withMethod($this->method);
        $uri = $request
            ->getUri()
            ->withScheme($this->scheme)
            ->withHost($this->host)
            ->withPort($this->port)
            ->withPath($this->path)
            ->withQuery($this->getQueryString());

        $request = $request
            ->withUri($uri)
            ->withProtocolVersion($this->protocolVersion)
            ->withHeader("accept", $this->getMediaTypeWithProfiles($this->requestedProfiles, true))
            ->withHeader("content-type", $this->getMediaTypeWithProfiles($this->appliedProfiles, false));

        foreach ($this->headers as $name => $value) {
            $request = $request->withHeader($name, $value);
        }

        return $this->serializer->serialize($request, $this->body);
    }

    private function getQueryString(): string
    {
        return http_build_query($this->queryString);
    }

    private function setArrayQueryParam(string $name, array $queryParam): void
    {
        foreach ($queryParam as $key => $value) {
            if (is_array($value)) {
                $this->queryString[$name][$key] = implode(",", $value);
            } else {
                $this->queryString[$name][$key] = $value;
            }
        }
    }

    /**
     * @param array|string $queryParam
     */
    private function setListQueryParam(string $name, $queryParam): void
    {
        if (is_array($queryParam)) {
            $this->queryString[$name] = implode(",", $queryParam);
        } else {
            $this->queryString[$name] = $queryParam;
        }
    }

    private function isBlankKey(array $array, string $key): bool
    {
        return array_key_exists($key, $array) === false || (empty($array[$key]) && is_numeric($array[$key]) === false);
    }

    private function getMediaTypeWithProfiles(array $profiles, bool $compatibility): string
    {
        $result = "application/vnd.api+json";

        if (empty($profiles) === false) {
            $result .= ';profile="' . implode(" ", $profiles) . '"' . ($compatibility ? ',application/vnd.api+json' : "");
        }

        return $result;
    }
}
