<?php

declare(strict_types=1);

namespace BahaaAlhagar\Yang\Tests\JsonApi\Hydrator;

use PHPUnit\Framework\TestCase;
use BahaaAlhagar\Yang\JsonApi\Schema\Document;
use BahaaAlhagar\Yang\JsonApi\Hydrator\ClassDocumentHydrator;
use BahaaAlhagar\Yang\JsonApi\Schema\Resource\ResourceObject;
use BahaaAlhagar\Yang\JsonApi\Hydrator\DocumentHydratorInterface;

class CustomHydratorTest extends TestCase
{
    /**
     * @test
     */
    public function hydrateSingleResourceWhenSingleResource(): void
    {
        $document = [
            "data" => [
                "type" => "a",
                "id" => "1",
            ],
        ];

        $document = Document::fromArray($document);
        $hydrator = $this->getCustomHydrator();
        $object = $hydrator->hydrateSingleResource($document);

        $this->assertEquals("a", $object->type);
        $this->assertEquals("1", $object->id);
    }

    private function getCustomHydrator(): DocumentHydratorInterface
    {
        $hydrator = new class extends ClassDocumentHydrator {
            protected function createObject(ResourceObject $resource): object
            {
                $anonymousClass = new class {
                    //class is empty on purpose
                };

                return new $anonymousClass();
            }
        };

        return new $hydrator();
    }
}
