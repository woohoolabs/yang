<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\JsonApi\Hydrator;

use WoohooLabs\Yang\JsonApi\Exception\DocumentException;
use WoohooLabs\Yang\JsonApi\Schema\Document;

interface DocumentHydratorInterface extends HydratorInterface
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
     * @throws DocumentException when the document is empty or it has a collection as primary data.
     */
    public function hydrateSingleResource(Document $document);

    /**
     * Hydrates a document when its primary data is a collection of resources.
     *
     * @return iterable<mixed>
     * @throws DocumentException when the document has a single resource as primary data.
     */
    public function hydrateCollection(Document $document): iterable;
}
