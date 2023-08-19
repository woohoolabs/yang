<?php

declare(strict_types=1);

namespace WoohooLabs\Yang\Tests\JsonApi\Hydrator;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yang\JsonApi\Hydrator\ClassDocumentHydrator;
use WoohooLabs\Yang\JsonApi\Hydrator\DocumentHydratorInterface;
use WoohooLabs\Yang\JsonApi\Schema\Document;
use WoohooLabs\Yang\JsonApi\Schema\Resource\ResourceObject;

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
        /** @var object{type: string, id: string} $object */
        $object = $hydrator->hydrateSingleResource($document);

        $this->assertEquals("a", $object->type);
        $this->assertEquals("1", $object->id);
    }

    private function getCustomHydrator(): DocumentHydratorInterface
    {
        $hydrator = new class extends ClassDocumentHydrator{
            protected function createObject(ResourceObject $resource): object
            {
                $anonymousClass = new class {
                    public string $id;
                    public string $type;

                    //class is empty on purpose
                };

                return new $anonymousClass();
            }
        };

        return new $hydrator();
    }
}
