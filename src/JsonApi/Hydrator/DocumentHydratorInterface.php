<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\JsonApi\Hydrator;

use WoohooLabs\Yang\JsonApi\Schema\Document;

interface DocumentHydratorInterface
{
    /**
     * Hydrates a document into an array/list of items regardless if the primary data is a single resource or a
     * collection of primary resources.
     *
     * @return iterable<mixed>
     */
    public function hydrate(Document $document): iterable;

    /**
     * Hydrates a document when its primary data is a single resource.
     *
     * @return mixed
     */
    public function hydrateSingleResource(Document $document);

    /**
     * Hydrates a document when its primary data is a collection of resources.
     *
     * @return iterable<mixed>
     */
    public function hydrateCollection(Document $document): iterable;
}
