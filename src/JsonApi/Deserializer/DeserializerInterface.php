<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\JsonApi\Deserializer;

use Psr\Http\Message\ResponseInterface;

interface DeserializerInterface
{
    /**
     * @return array|null
     */
    public function deserialize(ResponseInterface $response);
}
