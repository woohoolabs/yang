<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\JsonApi\Serializer;

use Psr\Http\Message\RequestInterface;
use WoohooLabs\Yang\JsonApi\Exception\SerializationException;
use function is_array;
use function is_string;
use function json_encode;

final class JsonSerializer implements SerializerInterface
{
    /**
     * @var int
     */
    private $options;

    /**
     * @var int
     */
    private $depth;

    public function __construct(int $options = 0, int $depth = 512)
    {
        $this->options = $options;
        $this->depth = $depth;
    }

    /**
     * @param mixed $body
     * @throws SerializationException
     */
    public function serialize(RequestInterface $request, $body): RequestInterface
    {
        if (is_array($body)) {
            $body = json_encode($body, $this->options, $this->depth);
        } elseif ($body !== null && is_string($body) === false) {
            throw new SerializationException("The request body can only be provided as a string, an array or null!");
        }

        if ($request->getBody()->isSeekable()) {
            $request->getBody()->rewind();
        }
        $request->getBody()->write((string) $body);

        return $request;
    }
}
