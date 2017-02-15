<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\JsonApi\Serializer;

use LogicException;
use Psr\Http\Message\RequestInterface;

class JsonSerializer implements SerializerInterface
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
     * @param array|string|null $content
     * @throws LogicException
     */
    public function serialize(RequestInterface $request, $content): RequestInterface
    {
        if (is_array($content)) {
            $content = json_encode($content, $this->options, $this->depth);
        } elseif ($content !== null && is_string($content) === false) {
            throw new LogicException("The content of the request can be a string, an array or null!");
        }

        if ($request->getBody()->isSeekable()) {
            $request->getBody()->rewind();
        }
        $request->getBody()->write($content);

        return $request;
    }
}
