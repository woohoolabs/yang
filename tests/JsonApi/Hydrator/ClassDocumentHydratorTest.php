<?php

declare(strict_types=1);

namespace WoohooLabs\Yang\Tests\JsonApi\Hydrator;

use PHPUnit\Framework\TestCase;
use stdClass;
use WoohooLabs\Yang\JsonApi\Exception\DocumentException;
use WoohooLabs\Yang\JsonApi\Hydrator\ClassDocumentHydrator;
use WoohooLabs\Yang\JsonApi\Schema\Document;

use function end;
use function reset;

class ClassDocumentHydratorTest extends TestCase
{
    /**
     * @test
     */
    public function doNotHydrateErrorDocument(): void
    {
        $document = [
            "errors" => [],
        ];

        $document = Document::fromArray($document);
        $hydrator = new ClassDocumentHydrator();
        $object = $hydrator->hydrate($document);

        $this->assertEquals([], $object);
    }

    /**
     * @test
     */
    public function hydrateWithSingleResourceTypeAndId(): void
    {
        $document = [
            "data" => [
                "type" => "a",
                "id" => "1",
            ],
        ];

        $document = Document::fromArray($document);
        $hydrator = new ClassDocumentHydrator();
        $objects = (array) $hydrator->hydrate($document);
        $object = reset($objects) ?: new stdClass();

        $this->assertCount(1, $objects);

        $this->assertSame("a", $object->type);
        $this->assertSame("1", $object->id);
    }

    /**
     * @test
     */
    public function hydrateWithSingleResourceAttributes(): void
    {
        $document = [
            "data" => [
                "attributes" => [
                    "a" => "A",
                    "b" => "B",
                    "c" => "C",
                ],
            ],
        ];

        $document = Document::fromArray($document);
        $hydrator = new ClassDocumentHydrator();
        $objects = (array) $hydrator->hydrate($document);
        $object = reset($objects) ?: new stdClass();

        $this->assertCount(1, $objects);

        $this->assertSame("A", $object->a);
        $this->assertSame("B", $object->b);
        $this->assertSame("C", $object->c);
    }

    /**
     * @test
     */
    public function hydrateWithCollectionAttributes(): void
    {
        $document = [
            "data" => [
                [
                    "type" => "a",
                    "id" => "1",
                    "attributes" => [
                        "a" => "A",
                        "b" => "B",
                        "c" => "C",
                    ],
                ],
                [
                    "type" => "a",
                    "id" => "0",
                    "attributes" => [
                        "a" => "D",
                        "b" => "E",
                        "c" => "F",
                    ],
                ],
            ],
        ];

        $document = Document::fromArray($document);
        $hydrator = new ClassDocumentHydrator();
        $objects = (array) $hydrator->hydrate($document);
        $object1 = reset($objects) ?: new stdClass();
        $object2 = end($objects) ?: new stdClass();

        $this->assertCount(2, $objects);

        $this->assertSame("1", $object1->id);
        $this->assertSame("A", $object1->a);
        $this->assertSame("B", $object1->b);
        $this->assertSame("C", $object1->c);

        $this->assertSame("0", $object2->id);
        $this->assertSame("D", $object2->a);
        $this->assertSame("E", $object2->b);
        $this->assertSame("F", $object2->c);
    }

    /**
     * @test
     */
    public function hydrateWithSingleResourceNotIncludedToOneRelationship(): void
    {
        $document = [
            "data" => [
                "relationships" => [
                    "x" => [
                        "data" => [
                            "type" => "a",
                            "id" => "1",
                        ],
                    ],
                ],
            ],
        ];

        $document = Document::fromArray($document);
        $hydrator = new ClassDocumentHydrator();
        $objects = (array) $hydrator->hydrate($document);
        $object = reset($objects) ?: new stdClass();

        $this->assertCount(1, $objects);
        $this->assertObjectNotHasAttribute("x", $object);
    }

    /**
     * @test
     */
    public function hydrateWithSingleResourceIncludedToOneRelationship(): void
    {
        $document = [
            "data" => [
                "type" => "a",
                "id" => "1",
                "relationships" => [
                    "x" => [
                        "data" => [
                            "type" => "b",
                            "id" => "0",
                        ],
                    ],
                ],
            ],
            "included" => [
                [
                    "type" => "b",
                    "id" => "0",
                    "attributes" => [
                        "a" => "A",
                        "b" => "B",
                    ],
                ],
            ],
        ];

        $document = Document::fromArray($document);
        $hydrator = new ClassDocumentHydrator();
        $objects = (array) $hydrator->hydrate($document);
        $object = reset($objects) ?: new stdClass();

        $this->assertCount(1, $objects);
        $this->assertObjectHasAttribute("x", $object);
        $this->assertSame("b", $object->x->type);
        $this->assertSame("0", $object->x->id);
        $this->assertSame("A", $object->x->a);
    }

    /**
     * @test
     */
    public function hydrateWithToManyRelationship(): void
    {
        $document = [
            "data" => [
                "type" => "a",
                "id" => "1",
                "relationships" => [
                    "x" => [
                        "data" => [
                            [
                                "type" => "b",
                                "id" => "1",
                            ],
                            [
                                "type" => "b",
                                "id" => "2",
                            ],
                            [
                                "type" => "b",
                                "id" => "3",
                            ],
                        ],
                    ],
                ],
            ],
            "included" => [
                [
                    "type" => "b",
                    "id" => "1",
                    "attributes" => [
                        "a" => "A",
                        "b" => "B",
                    ],
                ],
                [
                    "type" => "b",
                    "id" => "2",
                    "attributes" => [
                        "a" => "C",
                        "b" => "D",
                    ],
                ],
            ],
        ];

        $document = Document::fromArray($document);
        $hydrator = new ClassDocumentHydrator();
        $objects = (array) $hydrator->hydrate($document);
        $object = reset($objects) ?: new stdClass();

        $this->assertCount(1, $objects);
        $this->assertCount(2, $object->x);

        $this->assertSame("b", $object->x[0]->type);
        $this->assertSame("1", $object->x[0]->id);
        $this->assertSame("A", $object->x[0]->a);
        $this->assertSame("B", $object->x[0]->b);

        $this->assertSame("b", $object->x[1]->type);
        $this->assertSame("2", $object->x[1]->id);
        $this->assertSame("C", $object->x[1]->a);
        $this->assertSame("D", $object->x[1]->b);
    }

    /**
     * @test
     */
    public function hydrateWithSingleResourceNestedRelationships(): void
    {
        $document = [
            "data" => [
                "type" => "a",
                "id" => "1",
                "relationships" => [
                    "x" => [
                        "data" => [
                            "type" => "b",
                            "id" => "1",
                        ],
                    ],
                ],
            ],
            "included" => [
                [
                    "type" => "b",
                    "id" => "1",
                    "relationships" => [
                        "y" => [
                            "data" => [
                                "type" => "b",
                                "id" => "2",
                            ],
                        ],
                    ],
                ],
                [
                    "type" => "b",
                    "id" => "2",
                    "attributes" => [
                        "a" => "C",
                        "b" => "D",
                    ],
                ],
            ],
        ];

        $document = Document::fromArray($document);
        $hydrator = new ClassDocumentHydrator();
        $objects = (array) $hydrator->hydrate($document);
        $object = reset($objects) ?: new stdClass();

        $this->assertCount(1, $objects);

        $this->assertObjectHasAttribute("x", $object);
        $this->assertObjectHasAttribute("y", $object->x);

        $this->assertSame("b", $object->x->y->type);
        $this->assertSame("2", $object->x->y->id);
        $this->assertSame("C", $object->x->y->a);
        $this->assertSame("D", $object->x->y->b);
    }

    /**
     * @test
     */
    public function hydrateWithSingleResourceRecursiveRelationships(): void
    {
        $document = [
            "data" => [
                "type" => "a",
                "id" => "1",
                "attributes" => [
                    "a" => "A",
                    "b" => "B",
                ],
                "relationships" => [
                    "x" => [
                        "data" => [
                            "type" => "b",
                            "id" => "1",
                        ],
                    ],
                ],
            ],
            "included" => [
                [
                    "type" => "b",
                    "id" => "1",
                    "relationships" => [
                        "y" => [
                            "data" => [
                                "type" => "b",
                                "id" => "2",
                            ],
                        ],
                    ],
                ],
                [
                    "type" => "b",
                    "id" => "2",
                    "relationships" => [
                        "z" => [
                            "data" => [
                                "type" => "a",
                                "id" => "1",
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $document = Document::fromArray($document);
        $hydrator = new ClassDocumentHydrator();
        $objects = (array) $hydrator->hydrate($document);
        $object = reset($objects) ?: new stdClass();

        $this->assertCount(1, $objects);

        $this->assertObjectHasAttribute("x", $object);
        $this->assertObjectHasAttribute("y", $object->x);
        $this->assertObjectHasAttribute("z", $object->x->y);
        $this->assertSame($object, $object->x->y->z);

        $this->assertSame("a", $object->x->y->z->type);
        $this->assertSame("1", $object->x->y->z->id);
        $this->assertSame("A", $object->x->y->z->a);
        $this->assertSame("B", $object->x->y->z->b);
    }

    /**
     * @test
     */
    public function hydrateSingleResourceWhenCollectionEmpty(): void
    {
        $document = Document::fromArray([
            "data" => [],
        ]);

        $this->expectException(DocumentException::class);

        $hydrator = new ClassDocumentHydrator();
        $hydrator->hydrateSingleResource($document);
    }

    /**
     * @test
     */
    public function hydrateSingleResourceWhenCollection(): void
    {
        $document = Document::fromArray([
            "data" => [
                [
                    "type" => "a",
                    "id" => "1",
                ],
            ],
        ]);

        $this->expectException(DocumentException::class);

        $hydrator = new ClassDocumentHydrator();
        $hydrator->hydrateSingleResource($document);
    }

    /**
     * @test
     */
    public function hydrateSingleResourceWhenSingleResourceEmpty(): void
    {
        $document = Document::fromArray([
            "data" => null,
        ]);

        $this->expectException(DocumentException::class);

        $hydrator = new ClassDocumentHydrator();
        $hydrator->hydrateSingleResource($document);
    }

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
        $hydrator = new ClassDocumentHydrator();
        $object = $hydrator->hydrateSingleResource($document);

        $this->assertEquals("a", $object->type);
        $this->assertEquals("1", $object->id);
    }

    /**
     * @test
     */
    public function hydrateCollectionWhenEmpty(): void
    {
        $document = Document::fromArray([
            "data" => [],
        ]);

        $hydrator = new ClassDocumentHydrator();
        $collection = $hydrator->hydrateCollection($document);

        $this->assertCount(0, $collection);
    }

    /**
     * @test
     */
    public function hydrateCollectionWhenEmptySingleResource(): void
    {
        $document = Document::fromArray([
            "data" => null,
        ]);

        $this->expectException(DocumentException::class);

        $hydrator = new ClassDocumentHydrator();
        $hydrator->hydrateCollection($document);
    }

    /**
     * @test
     */
    public function hydrateCollectionWhenSingleResource(): void
    {
        $document = Document::fromArray([
            "data" => [
                "type" => "a",
                "id" => "1",
            ],
        ]);

        $this->expectException(DocumentException::class);

        $hydrator = new ClassDocumentHydrator();
        $hydrator->hydrateCollection($document);
    }

    /**
     * @test
     */
    public function hydrateCollectionMultipleResources(): void
    {
        $document = [
            "data" => [
                [
                    "type" => "a",
                    "id" => "1",
                ],
                [
                    "type" => "a",
                    "id" => "2",
                ],
            ],
        ];

        $document = Document::fromArray($document);
        $hydrator = new ClassDocumentHydrator();
        $collection = (array) $hydrator->hydrateCollection($document);
        $object1 = reset($collection) ?: new stdClass();
        $object2 = end($collection) ?: new stdClass();

        $this->assertCount(2, $collection);
        $this->assertSame("a", $object1->type);
        $this->assertSame("1", $object1->id);
        $this->assertSame("a", $object2->type);
        $this->assertSame("2", $object2->id);
    }
}
