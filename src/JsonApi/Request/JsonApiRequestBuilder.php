<?php
namespace WoohooLabs\Yang\JsonApi\Request;

use Psr\Http\Message\RequestInterface;

class JsonApiRequestBuilder
{
    /**
     * @var \Psr\Http\Message\RequestInterface
     */
    protected $request;

    /**
     * @var string
     */
    protected $method;

    /**
     * @var string
     */
    protected $protocolVersion;

    /**
     * @var string
     */
    protected $scheme;

    /**
     * @var string
     */
    protected $host;

    /**
     * @var string
     */
    protected $port;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var array
     */
    protected $queryString;

    /**
     * @var array
     */
    protected $headers;

    /**
     * @var string
     */
    protected $body;

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
        return $this->method("GET");
    }

    /**
     * @return $this
     */
    public function create()
    {
        return $this->method("POST");
    }

    /**
     * @return $this
     */
    public function update()
    {
        return $this->method("UPDATE");
    }

    /**
     * @return $this
     */
    public function delete()
    {
        return $this->method("DELETE");
    }

    /**
     * @param string $method
     * @return $this
     */
    public function method($method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @param string $version
     * @return $this
     */
    public function protocolVersion($version)
    {
        $this->protocolVersion = $version;

        return $this;
    }

    /**
     * @return $this
     */
    public function http()
    {
        return $this->uriScheme("http");
    }

    /**
     * @return $this
     */
    public function https()
    {
        return $this->uriScheme("https");
    }

    /**
     * @param string $uri
     * @return $this
     */
    public function uri($uri)
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
    public function uriScheme($scheme)
    {
        $this->scheme = $scheme;

        return $this;
    }

    /**
     * @param string $host
     * @return $this
     */
    public function uriHost($host)
    {
        $this->host = $host;

        return $this;
    }

    /**
     * @param string $port
     * @return $this
     */
    public function uriPort($port)
    {
        $this->port = $port;

        return $this;
    }

    /**
     * @param string $path
     * @return $this
     */
    public function uriPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @param string $name
     * @param string|array $value
     * @return $this
     */
    public function uriQueryParam($name, $value)
    {
        $this->queryString[$name] = $value;

        return $this;
    }

    /**
     * @param string $name
     * @param string|string[] $value
     * @return $this
     */
    public function withHeader($name, $value)
    {
        $this->headers[$name] = $value;

        return $this;
    }

    /**
     * @param array $fields
     * @return $this
     */
    public function withFields(array $fields)
    {
        $this->setQueryParam("fields", $fields);

        return $this;
    }

    /**
     * @param array|string $fields
     * @return $this
     */
    public function withSort($fields)
    {
        $this->setQueryParam("sort", $fields);

        return $this;
    }

    /**
     * @param array $paginate
     * @return $this
     */
    public function withPage(array $paginate)
    {
        $this->setQueryParam("page", $paginate);

        return $this;
    }

    /**
     * @param array|string $filter
     * @return $this
     */
    public function withFilter(array $filter)
    {
        $this->setQueryParam("filter", $filter);

        return $this;
    }

    /**
     * @param array|string $includes
     * @return $this
     */
    public function withIncludes($includes)
    {
        if (is_array($includes)) {
            $this->queryString["include"] = implode(",", $includes);
        } else {
            $this->queryString["include"] = $includes;
        }

        return $this;
    }

    /**
     * @param \WoohooLabs\Yang\JsonApi\Request\JsonApiResource|array|object|string $body
     * @return $this
     */
    public function withBody($body)
    {
        if (is_string($body)) {
            $this->body = $body;
        } elseif ($body instanceof JsonApiResource) {
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
        $request = $request->withProtocolVersion($this->protocolVersion);
        $request = $request->getUri()->withScheme($this->scheme);
        $request = $request->getUri()->withHost($this->host);
        $request = $request->getUri()->withPath($this->path);
        $request = $request->getUri()->withQuery($this->getQueryString());
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
    protected function getQueryString()
    {
        return http_build_query($this->queryString);
    }

    /**
     * @param string $name
     * @param array|string $queryParam
     */
    protected function setQueryParam($name, $queryParam)
    {
        if (is_array($queryParam)) {
            foreach ($queryParam as $key => $value) {
                $this->queryString[$name][$key] = $value;
            }
        } else {
            $this->queryString[$name] = $queryParam;
        }
    }
}
