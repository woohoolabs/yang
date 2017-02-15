<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\JsonApi\Serializer;

use Psr\Http\Message\ResponseInterface;

interface DeserializerInterface
{
    /**
     * @return array|mixed|null
     */
    public function deserialize(ResponseInterface $response);
}
