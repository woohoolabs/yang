<?php
namespace WoohooLabs\Yang\JsonApi\Deserializer;

use Psr\Http\Message\ResponseInterface;

interface DeserializerInterface
{
    /**
     * @param ResponseInterface $response
     * @return array|null
     */
    public function deserialize(ResponseInterface $response);
}
