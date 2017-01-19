<?php
namespace WoohooLabs\Yang\JsonApi\Deserializer;

use Psr\Http\Message\ResponseInterface;

class JsonDeserializer implements DeserializerInterface
{
    /**
     * @param ResponseInterface $response
     * @return array|null
     */
    public function deserialize(ResponseInterface $response)
    {
        return json_decode($response->getBody()->__toString(), true);
    }
}
