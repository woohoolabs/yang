<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\JsonApi\Hydrator;

use WoohooLabs\Yang\JsonApi\Schema\Document;

interface HydratorInterface
{
    /**
     * @return mixed
     */
    public function hydrate(Document $document);
}
