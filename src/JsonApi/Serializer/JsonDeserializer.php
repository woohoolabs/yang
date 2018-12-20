<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\JsonApi\Serializer;

use Psr\Http\Message\ResponseInterface;
use function json_decode;

final class JsonDeserializer implements DeserializerInterface
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
     * @return array|mixed|null
     */
    public function deserialize(ResponseInterface $response)
    {
        return json_decode($response->getBody()->__toString(), true, $this->depth, $this->options);
    }
}
