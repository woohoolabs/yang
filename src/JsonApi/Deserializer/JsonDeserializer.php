<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\JsonApi\Deserializer;

use Psr\Http\Message\ResponseInterface;

class JsonDeserializer implements DeserializerInterface
{
    /**
     * @return array|null
     */
    public function deserialize(ResponseInterface $response)
    {
        return json_decode($response->getBody()->__toString(), true);
    }
}
