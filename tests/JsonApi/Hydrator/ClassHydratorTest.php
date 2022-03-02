<?php

declare(strict_types=1);

namespace BahaaAlhagar\Yang\Tests\JsonApi\Hydrator;

use stdClass;
use PHPUnit\Framework\TestCase;
use BahaaAlhagar\Yang\JsonApi\Schema\Document;
use BahaaAlhagar\Yang\JsonApi\Hydrator\ClassHydrator;

class ClassHydratorTest extends TestCase
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
        $hydrator = new ClassHydrator();
        $object = $hydrator->hydrate($document);

        $this->assertEquals(new stdClass(), $object);
    }

    /**
     * @test
     */
    public function hydrateObjectWithTypeAndId(): void
    {
        $document = [
            "data" => [
                "type" => "a",
                "id" => "1",
            ],
        ];

        $document = Document::fromArray($document);
        $hydrator = new ClassHydrator();
        $object = $hydrator->hydrate($document);

        $this->assertSame("a", $object->type);
        $this->assertSame("1", $object->id);
    }

    /**
     * @test
     */
    public function hydrateObjectWithAttributes(): void
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
        $hydrator = new ClassHydrator();
        $object = $hydrator->hydrate($document);

        $this->assertSame("A", $object->a);
        $this->assertSame("B", $object->b);
        $this->assertSame("C", $object->c);
    }

    /**
     * @test
     */
    public function hydrateObjectsWithAttributes(): void
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
        $hydrator = new ClassHydrator();
        $objects = $hydrator->hydrate($document);

        $this->assertCount(2, $objects);
        $this->assertSame("1", $objects[0]->id);
        $this->assertSame("A", $objects[0]->a);
        $this->assertSame("B", $objects[0]->b);
        $this->assertSame("C", $objects[0]->c);

        $this->assertSame("0", $objects[1]->id);
        $this->assertSame("D", $objects[1]->a);
        $this->assertSame("E", $objects[1]->b);
        $this->assertSame("F", $objects[1]->c);
    }

    /**
     * @test
     */
    public function hydrateObjectWithNotIncludedToOneRelationship(): void
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
        $hydrator = new ClassHydrator();
        $object = $hydrator->hydrate($document);

        $this->assertObjectNotHasAttribute("x", $object);
    }

    /**
     * @test
     */
    public function hydrateObjectWithIncludedToOneRelationship(): void
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
        $hydrator = new ClassHydrator();
        $object = $hydrator->hydrate($document);

        $this->assertObjectHasAttribute("x", $object);
        $this->assertSame("b", $object->x->type);
        $this->assertSame("0", $object->x->id);
        $this->assertSame("A", $object->x->a);
    }

    /**
     * @test
     */
    public function hydrateObjectWithToManyRelationship(): void
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
        $hydrator = new ClassHydrator();
        $object = $hydrator->hydrate($document);

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
    public function hydrateObjectWithNestedRelationships(): void
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
        $hydrator = new ClassHydrator();
        $object = $hydrator->hydrate($document);

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
    public function hydrateObjectWithRecursiveRelationships(): void
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
        $hydrator = new ClassHydrator();
        $object = $hydrator->hydrate($document);

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
    public function hydrateObjectWhenCollectionEmpty(): void
    {
        $document = [
            "data" => [],
        ];

        $document = Document::fromArray($document);
        $hydrator = new ClassHydrator();
        $object = $hydrator->hydrateObject($document);

        $this->assertEquals(new stdClass(), $object);
    }

    /**
     * @test
     */
    public function hydrateObjectWhenCollection(): void
    {
        $document = [
            "data" => [
                [
                    "type" => "a",
                    "id" => "1",
                ],
            ],
        ];

        $document = Document::fromArray($document);
        $hydrator = new ClassHydrator();
        $object = $hydrator->hydrateObject($document);

        $this->assertEquals(new stdClass(), $object);
    }

    /**
     * @test
     */
    public function hydrateObjectWhenSingleResourceEmpty(): void
    {
        $document = [
            "data" => null,
        ];

        $document = Document::fromArray($document);
        $hydrator = new ClassHydrator();
        $object = $hydrator->hydrateObject($document);

        $this->assertEquals(new stdClass(), $object);
    }

    /**
     * @test
     */
    public function hydrateObjectWhenSingleResource(): void
    {
        $document = [
            "data" => [
                "type" => "a",
                "id" => "1",
            ],
        ];

        $document = Document::fromArray($document);
        $hydrator = new ClassHydrator();
        $object = $hydrator->hydrateObject($document);

        $this->assertEquals("a", $object->type);
        $this->assertEquals("1", $object->id);
    }

    /**
     * @test
     */
    public function hydrateCollectionWhenEmpty(): void
    {
        $document = [
            "data" => [],
        ];

        $document = Document::fromArray($document);
        $hydrator = new ClassHydrator();
        $collection = $hydrator->hydrateCollection($document);

        $this->assertCount(0, $collection);
    }

    /**
     * @test
     */
    public function hydrateCollectionEmptySingleResource(): void
    {
        $document = [
            "data" => null,
        ];

        $document = Document::fromArray($document);
        $hydrator = new ClassHydrator();
        $collection = $hydrator->hydrateCollection($document);

        $this->assertCount(0, $collection);
    }

    /**
     * @test
     */
    public function hydrateCollectionSingleResource(): void
    {
        $document = [
            "data" => [
                "type" => "a",
                "id" => "1",
            ],
        ];

        $document = Document::fromArray($document);
        $hydrator = new ClassHydrator();
        $collection = $hydrator->hydrateCollection($document);

        $this->assertCount(1, $collection);
        $this->assertSame("a", $collection[0]->type);
        $this->assertSame("1", $collection[0]->id);
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
        $hydrator = new ClassHydrator();
        $collection = $hydrator->hydrateCollection($document);

        $this->assertCount(2, $collection);
        $this->assertSame("a", $collection[0]->type);
        $this->assertSame("1", $collection[0]->id);
        $this->assertSame("a", $collection[1]->type);
        $this->assertSame("2", $collection[1]->id);
    }
}
