<?php
namespace WoohooLabs\Yang\JsonApi\Request;

use Psr\Http\Message\RequestInterface;

class RequestBuilder
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
        $this->scheme = "http";
        $this->host = "";
        $this->path = "";
        $this->queryString = [];
    }

    /**
     * @return $this
     */
    public function fetch()
    {
        $this->method = "GET";

        return $this;
    }

    /**
     * @return $this
     */
    public function create()
    {
        $this->method = "POST";
    }

    /**
     * @return $this
     */
    public function update()
    {
        $this->method = "PATCH";

        return $this;
    }

    /**
     * @return $this
     */
    public function delete()
    {
        $this->method = "DELETE";

        return $this;
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
     * @return $this
     */
    public function http()
    {
        $this->scheme = "http";

        return $this;
    }

    /**
     * @return $this
     */
    public function https()
    {
        $this->scheme = "https";

        return $this;
    }

    /**
     * @param string $scheme
     * @return $this
     */
    public function scheme($scheme)
    {
        $this->scheme = $scheme;

        return $this;
    }

    /**
     * @param string $host
     * @return $this
     */
    public function host($host)
    {
        $this->host = $host;

        return $this;
    }

    /**
     * @param string $port
     * @return $this
     */
    public function port($port)
    {
        $this->port = $port;

        return $this;
    }

    /**
     * @param string $path
     * @return $this
     */
    public function path($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @param array $fields
     * @return $this
     */
    public function fields(array $fields)
    {
        $this->setQueryParam("fields", $fields);

        return $this;
    }

    /**
     * @param array|string $fields
     * @return $this
     */
    public function sort($fields)
    {
        $this->setQueryParam("sort", $fields);

        return $this;
    }

    /**
     * @param array $paginate
     * @return $this
     */
    public function page(array $paginate)
    {
        $this->setQueryParam("page", $paginate);

        return $this;
    }

    /**
     * @param array|string $filter
     * @return $this
     */
    public function filter(array $filter)
    {
        $this->setQueryParam("filter", $filter);

        return $this;
    }

    /**
     * @param array|string $includes
     * @return $this
     */
    public function includes($includes)
    {
        if (is_array($includes)) {
            $this->queryString["includes"] = implode(",", $includes);
        } else {
            $this->queryString["includes"] = $includes;
        }

        return $this;
    }

    /**
     * @return \Psr\Http\Message\RequestInterface
     */
    public function getRequest()
    {
        $request = $this->request->withMethod($this->method);
        $request = $request->withUri($request->getUri()->withScheme($this->scheme));
        $request = $request->withUri($request->getUri()->withHost($this->host));
        $request = $request->withUri($request->getUri()->withPath($this->path));
        $request = $request->withUri($request->getUri()->withQuery($this->getQueryString()));
        $request = $request->withHeader("Content-Type", "application/vnd.api+json");
        $request = $request->withHeader("Accept", "application/vnd.api+json");

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
