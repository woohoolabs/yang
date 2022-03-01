<?php

declare(strict_types=1);

namespace BahaaAlhagar\Yang\JsonApi\Serializer;

use Psr\Http\Message\RequestInterface;

interface SerializerInterface
{
    /**
     * @param mixed $body
     */
    public function serialize(RequestInterface $request, $body): RequestInterface;
}
