<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\JsonApi\Hydrator;

use WoohooLabs\Yang\JsonApi\Schema\Document;

/**
 * @deprecated Use the DocumentHydratorInterface instead.
 */
interface HydratorInterface
{
    /**
     * @return mixed
     */
    public function hydrate(Document $document);

    public function hydrateCollection(Document $document): iterable;
}
