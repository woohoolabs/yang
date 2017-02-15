<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\JsonApi\Serializer;

use Psr\Http\Message\RequestInterface;

interface SerializerInterface
{
    /**
     * @param array|string|mixed $content
     */
    public function serialize(RequestInterface $request, $content): RequestInterface;
}
