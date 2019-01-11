<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\JsonApi\Response;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

abstract class AbstractResponse implements ResponseInterface
{
    /**
     * @var ResponseInterface
     */
    protected $response;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    public function getProtocolVersion(): string
    {
        return $this->response->getProtocolVersion();
    }

    /**
     * @param string $version
     * @return $this
     */
    public function withProtocolVersion($version)
    {
        $response = clone $this;
        $response->response = $this->response->withProtocolVersion($version);

        return $response;
    }

    /**
     * @return string[][]
     */
    public function getHeaders(): array
    {
        return $this->response->getHeaders();
    }

    /**
     * @param string $name
     */
    public function hasHeader($name): bool
    {
        return $this->response->hasHeader($name);
    }

    /**
     * @param string $name
     * @return string[]
     */
    public function getHeader($name): array
    {
        return $this->response->getHeader($name);
    }

    /**
     * @param string $name
     */
    public function getHeaderLine($name): string
    {
        return $this->response->getHeaderLine($name);
    }

    /**
     * @param string $name
     * @param string|string[] $value
     * @return $this
     */
    public function withHeader($name, $value)
    {
        $response = clone $this;
        $response->response = $this->response->withHeader($name, $value);

        return $response;
    }

    /**
     * @param string $name
     * @param string|string[] $value
     * @return $this
     */
    public function withAddedHeader($name, $value)
    {
        $response = clone $this;
        $response->response = $this->response->withAddedHeader($name, $value);

        return $response;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function withoutHeader($name)
    {
        $response = clone $this;
        $response->response = $this->response->withoutHeader($name);

        return $response;
    }

    public function getBody(): StreamInterface
    {
        return $this->response->getBody();
    }

    /**
     * @return $this
     */
    public function withBody(StreamInterface $body)
    {
        $response = clone $this;
        $response->response = $this->response->withBody($body);

        return $response;
    }

    public function getStatusCode(): int
    {
        return $this->response->getStatusCode();
    }

    /**
     * @param int $code
     * @param string $reasonPhrase
     * @return $this
     */
    public function withStatus($code, $reasonPhrase = "")
    {
        $response = clone $this;
        $response->response = $this->response->withStatus($code, $reasonPhrase);

        return $response;
    }

    public function getReasonPhrase(): string
    {
        return $this->response->getReasonPhrase();
    }
}
