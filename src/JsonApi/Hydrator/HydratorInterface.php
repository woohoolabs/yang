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
     * Hydrates a document regardless if the primary data is a single resource or * collection of primary resources.
     *
     * @return mixed
     */
    public function hydrate(Document $document);

    /**
     * Hydrates a document when its primary data is a collection of resources.
     *
     * @return iterable<mixed>
     */
    public function hydrateCollection(Document $document): iterable;
}
