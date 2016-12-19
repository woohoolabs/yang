<?php
namespace WoohooLabs\Yang\JsonApi\Response;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use WoohooLabs\Yang\JsonApi\Deserializer\DefaultDeserializer;
use WoohooLabs\Yang\JsonApi\Deserializer\DeserializerInterface;
use WoohooLabs\Yang\JsonApi\Schema\Document;

class JsonApiResponse implements ResponseInterface
{
    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    private $response;

    /**
     * @var DeserializerInterface
     */
    private $deserializer;

    /**
     * @var \WoohooLabs\Yang\JsonApi\Schema\Document|false|null
     */
    private $document = false;

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     */
    public function __construct(ResponseInterface $response, DeserializerInterface $deserializer = null)
    {
        $this->response = $response;
        $this->deserializer = $deserializer !== null ? $deserializer : new DefaultDeserializer();
    }

    /**
     * @return bool
     */
    public function hasDocument()
    {
        return is_object($this->document());
    }

    /**
     * @return \WoohooLabs\Yang\JsonApi\Schema\Document|null
     */
    public function document()
    {
        if ($this->document === false) {
            $content = $this->deserializer->deserialize($this->response);
            $this->document = is_array($content) === true ? Document::createFromArray($content) : null;
        }

        return $this->document;
    }

    /**
     * @param array $successfulStatusCodes
     * @return bool
     */
    public function isSuccessful($successfulStatusCodes = [])
    {
        $isStatusCodeSuccessful = empty($successfulStatusCodes) === true ||
            in_array($this->getStatusCode(), $successfulStatusCodes, true);

        $hasNoErrors = $this->hasDocument() === false || $this->document()->hasErrors() === false;

        return $isStatusCodeSuccessful && $hasNoErrors;
    }

    /**
     * @param array $allowedStatusCodes
     * @return bool
     */
    public function isSuccessfulDocument($allowedStatusCodes = [])
    {
        return $this->isSuccessful($allowedStatusCodes) && $this->hasDocument();
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

    public function withStatus($code, $reasonPhrase = "")
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
