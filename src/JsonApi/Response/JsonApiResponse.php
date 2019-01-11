<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\JsonApi\Response;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yang\JsonApi\Exception\ResponseException;
use WoohooLabs\Yang\JsonApi\Schema\Document;
use WoohooLabs\Yang\JsonApi\Serializer\DeserializerInterface;
use WoohooLabs\Yang\JsonApi\Serializer\JsonDeserializer;
use function in_array;
use function is_array;

class JsonApiResponse extends AbstractResponse
{
    /**
     * @var DeserializerInterface
     */
    private $deserializer;

    /**
     * @var Document|false|null
     */
    private $document = false;

    public function __construct(ResponseInterface $response, ?DeserializerInterface $deserializer = null)
    {
        parent::__construct($response);
        $this->deserializer = $deserializer ?? new JsonDeserializer();
    }

    public function hasDocument(): bool
    {
        if ($this->document === false) {
            $this->document = $this->createDocument();
        }

        return $this->document !== null;
    }

    /**
     * @throws ResponseException
     */
    public function document(): Document
    {
        if ($this->document === false) {
            $this->document = $this->createDocument();
        }

        if ($this->document === null) {
            throw new ResponseException("The response doesn't contain any document!");
        }

        return $this->document;
    }

    public function isSuccessful(array $successfulStatusCodes = []): bool
    {
        $isStatusCodeSuccessful = empty($successfulStatusCodes) || in_array($this->getStatusCode(), $successfulStatusCodes, true);

        $hasNoErrors = $this->hasDocument() === false || $this->document()->hasErrors() === false;

        return $isStatusCodeSuccessful && $hasNoErrors;
    }

    public function isSuccessfulDocument(array $allowedStatusCodes = []): bool
    {
        return $this->isSuccessful($allowedStatusCodes) && $this->hasDocument();
    }

    private function createDocument(): ?Document
    {
        $content = $this->deserializer->deserialize($this->response);

        if (is_array($content) === false) {
            return null;
        }

        return Document::fromArray($content);
    }
}
