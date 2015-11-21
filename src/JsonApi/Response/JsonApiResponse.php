<?php
namespace WoohooLabs\Yang\JsonApi\Response;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use WoohooLabs\Yang\JsonApi\Schema\Document;

class JsonApiResponse implements ResponseInterface
{
    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    protected $response;

    /**
     * @var \WoohooLabs\Yang\JsonApi\Schema\Document
     */
    protected $document;

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     */
    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    /**
     * @return bool
     */
    public function hasDocument()
    {
        return $this->document() !== null;
    }

    /**
     * @return \WoohooLabs\Yang\JsonApi\Schema\Document|null
     */
    public function document()
    {
        if ($this->document === null) {
            $response = json_decode($this->response->getBody()->getContents(), true);
            $this->document = is_array($response) === true ? Document::createFromArray($response) : null;
        }

        return $this->document;
    }

    /**
     * @param array $allowedStatusCodes
     * @return bool
     */
    public function isSuccessful($allowedStatusCodes = [])
    {
        return (empty($allowedStatusCodes) === true || in_array($this->getStatusCode(), $allowedStatusCodes)) &&
            $this->hasDocument() === true &&
            $this->document()->hasErrors() === false;
    }

    public function getProtocolVersion()
    {
        return $this->response->getProtocolVersion();
    }

    /**
     * @param string $version
     * @return mixed
     */
    public function withProtocolVersion($version)
    {
        $response = clone $this;
        $response->response = $this->response->withProtocolVersion($version);
        return $response;
    }

    public function getHeaders()
    {
        return $this->response->getHeaders();
    }

    public function hasHeader($name)
    {
        return $this->response->hasHeader($name);
    }

    public function getHeader($name)
    {
        return $this->response->getHeader($name);
    }

    public function getHeaderLine($name)
    {
        return $this->response->getHeaderLine($name);
    }

    public function withHeader($name, $value)
    {
        $response = clone $this;
        $response->response = $this->response->withHeader($name, $value);
        return $response;
    }

    public function withAddedHeader($name, $value)
    {
        $response = clone $this;
        $response->response = $this->response->withAddedHeader($name, $value);
        return $response;
    }

    public function withoutHeader($name)
    {
        $response = clone $this;
        $response->response = $this->response->withoutHeader($name);
        return $response;
    }

    public function getBody()
    {
        return $this->response->getBody();
    }

    public function withBody(StreamInterface $body)
    {
        $response = clone $this;
        $response->response = $this->response->withBody($body);
        return $response;
    }

    public function getStatusCode()
    {
        return $this->response->getStatusCode();
    }

    public function withStatus($code, $reasonPhrase = '')
    {
        $response = clone $this;
        $response->response = $this->response->withStatus($code, $reasonPhrase);
        return $response;
    }

    public function getReasonPhrase()
    {
        return $this->response->getReasonPhrase();
    }
}
