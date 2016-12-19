<?php
namespace WoohooLabs\Yang\JsonApi\Request;

use Psr\Http\Message\RequestInterface;

class JsonApiRequestBuilder
{
    /**
     * @var \Psr\Http\Message\RequestInterface
     */
    private $request;

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
     * @var string
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
     * @var string
     */
    private $body;

    /**
     * @param \Psr\Http\Message\RequestInterface $request
     */
    public function __construct(RequestInterface $request)
    {
        $this->request = $request;
        $this->initialize();
    }

    public function initialize()
    {
        $this->method = "GET";
        $this->protocolVersion = "";
        $this->scheme = "http";
        $this->host = "";
        $this->path = "";
        $this->queryString = [];
        $this->headers = [];
    }

    /**
     * @return $this
     */
    public function fetch()
    {
        return $this->setMethod("GET");
    }

    /**
     * @return $this
     */
    public function create()
    {
        return $this->setMethod("POST");
    }

    /**
     * @return $this
     */
    public function update()
    {
        return $this->setMethod("PATCH");
    }

    /**
     * @return $this
     */
    public function delete()
    {
        return $this->setMethod("DELETE");
    }

    /**
     * @param string $method
     * @return $this
     */
    public function setMethod($method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @param string $version
     * @return $this
     */
    public function setProtocolVersion($version)
    {
        $this->protocolVersion = $version;

        return $this;
    }

    /**
     * @return $this
     */
    public function http()
    {
        return $this->setUriScheme("http");
    }

    /**
     * @return $this
     */
    public function https()
    {
        return $this->setUriScheme("https");
    }

    /**
     * @param string $uri
     * @return $this
     */
    public function setUri($uri)
    {
        $parsedUrl = parse_url($uri);

        if ($parsedUrl === false) {
            return $this;
        }

        if (empty($parsedUrl["scheme"]) === false) {
            $this->scheme = $parsedUrl["scheme"];
        }

        if (empty($parsedUrl["port"]) === false) {
            $this->port = $parsedUrl["port"];
        }

        if (empty($parsedUrl["host"]) === false) {
            $this->host = $parsedUrl["host"];
        }

        if (empty($parsedUrl["path"]) === false) {
            $this->path = $parsedUrl["path"];
        }

        if (empty($parsedUrl["query"]) === false) {
            parse_str($parsedUrl["query"], $this->queryString);
        }

        return $this;
    }

    /**
     * @param string $scheme
     * @return $this
     */
    public function setUriScheme($scheme)
    {
        $this->scheme = $scheme;

        return $this;
    }

    /**
     * @param string $host
     * @return $this
     */
    public function setUriHost($host)
    {
        $this->host = $host;

        return $this;
    }

    /**
     * @param string $port
     * @return $this
     */
    public function setUriPort($port)
    {
        $this->port = $port;

        return $this;
    }

    /**
     * @param string $path
     * @return $this
     */
    public function setUriPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @param string $name
     * @param string|array $value
     * @return $this
     */
    public function setUriQueryParam($name, $value)
    {
        $this->queryString[$name] = $value;

        return $this;
    }

    /**
     * @param string $name
     * @param string|string[] $value
     * @return $this
     */
    public function setHeader($name, $value)
    {
        $this->headers[$name] = $value;

        return $this;
    }

    /**
     * @param array $fields
     * @return $this
     */
    public function setJsonApiFields(array $fields)
    {
        $this->setArrayQueryParam("fields", $fields);

        return $this;
    }

    /**
     * @param array|string $sort
     * @return $this
     */
    public function setJsonApiSort($sort)
    {
        $this->setListQueryParam("sort", $sort);

        return $this;
    }

    /**
     * @param array $paginate
     * @return $this
     */
    public function setJsonApiPage(array $paginate)
    {
        $this->setArrayQueryParam("page", $paginate);

        return $this;
    }

    /**
     * @param array|string $filter
     * @return $this
     */
    public function setJsonApiFilter(array $filter)
    {
        $this->setArrayQueryParam("filter", $filter);

        return $this;
    }

    /**
     * @param array|string $includes
     * @return $this
     */
    public function setJsonApiIncludes($includes)
    {
        $this->setListQueryParam("include", $includes);

        return $this;
    }

    /**
     * @param \WoohooLabs\Yang\JsonApi\Request\ResourceObject|array|object|string $body
     * @return $this
     */
    public function setJsonApiBody($body)
    {
        if (is_string($body)) {
            $this->body = $body;
        } elseif ($body instanceof ResourceObject) {
            $this->body = json_encode($body->toArray());
        } elseif (is_array($body) || is_object($body)) {
            $this->body = json_encode($body);
        }

        return $this;
    }

    /**
     * @return \Psr\Http\Message\RequestInterface
     */
    public function getRequest()
    {
        $request = $this->request->withMethod($this->method);
        $uri = $request
            ->getUri()
            ->withScheme($this->scheme)
            ->withHost($this->host)
            ->withPort($this->port)
            ->withPath($this->path)
            ->withQuery($this->getQueryString());
        $request = $request->withUri($uri);
        $request = $request->withProtocolVersion($this->protocolVersion);
        $request = $request->withHeader("Accept", "application/vnd.api+json");
        $request = $request->withHeader("Content-Type", "application/vnd.api+json");
        foreach ($this->headers as $name => $value) {
            $request = $request->withHeader($name, $value);
        }
        $request->getBody()->write($this->body);

        return $request;
    }

    /**
     * @return string
     */
    private function getQueryString()
    {
        return http_build_query($this->queryString);
    }

    /**
     * @param string $name
     * @param array $queryParam
     */
    private function setArrayQueryParam($name, array $queryParam)
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
     * @param string $name
     * @param array|string $queryParam
     */
    private function setListQueryParam($name, $queryParam)
    {
        if (is_array($queryParam)) {
            $this->queryString[$name] = implode(",", $queryParam);
        } else {
            $this->queryString[$name] = $queryParam;
        }
    }
}
