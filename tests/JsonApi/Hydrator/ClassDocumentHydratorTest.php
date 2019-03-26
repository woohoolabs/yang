<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\Tests\JsonApi\Hydrator;

use PHPUnit\Framework\TestCase;
use stdClass;
use WoohooLabs\Yang\JsonApi\Hydrator\ClassDocumentHydrator;
use WoohooLabs\Yang\JsonApi\Schema\Document;

class ClassDocumentHydratorTest extends TestCase
{
    /**
     * @test
     */
    public function doNotHydrateErrorDocument()
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
    public function hydrateWithSingleResourceTypeAndId()
    {
        $document = [
            "data" => [
                "type" => "a",
                "id" => "1",
            ],
        ];

        $document = Document::fromArray($document);
        $hydrator = new ClassDocumentHydrator();
        $objects = $hydrator->hydrate($document);

        $this->assertCount(1, $objects);

        $this->assertAttributeSame("a", "type", $objects[0]);
        $this->assertAttributeSame("1", "id", $objects[0]);
    }

    /**
     * @test
     */
    public function hydrateWithSingleResourceAttributes()
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
        $objects = $hydrator->hydrate($document);

        $this->assertCount(1, $objects);

        $this->assertAttributeSame("A", "a", $objects[0]);
        $this->assertAttributeSame("B", "b", $objects[0]);
        $this->assertAttributeSame("C", "c", $objects[0]);
    }

    /**
     * @test
     */
    public function hydrateWithCollectionAttributes()
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
        $objects = $hydrator->hydrate($document);

        $this->assertCount(2, $objects);

        $this->assertAttributeSame("1", "id", $objects[0]);
        $this->assertAttributeSame("A", "a", $objects[0]);
        $this->assertAttributeSame("B", "b", $objects[0]);
        $this->assertAttributeSame("C", "c", $objects[0]);

        $this->assertAttributeSame("0", "id", $objects[1]);
        $this->assertAttributeSame("D", "a", $objects[1]);
        $this->assertAttributeSame("E", "b", $objects[1]);
        $this->assertAttributeSame("F", "c", $objects[1]);
    }

    /**
     * @test
     */
    public function hydrateWithSingleResourceNotIncludedToOneRelationship()
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
        $objects = $hydrator->hydrate($document);

        $this->assertCount(1, $objects);
        $this->assertObjectNotHasAttribute("x", $objects[0]);
    }

    /**
     * @test
     */
    public function hydrateWithSingleResourceIncludedToOneRelationship()
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
        $objects = $hydrator->hydrate($document);

        $this->assertCount(1, $objects);
        $this->assertObjectHasAttribute("x", $objects[0]);
        $this->assertAttributeSame("b", "type", $objects[0]->x);
        $this->assertAttributeSame("0", "id", $objects[0]->x);
        $this->assertAttributeSame("A", "a", $objects[0]->x);
    }

    /**
     * @test
     */
    public function hydrateWithToManyRelationship()
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
        $objects = $hydrator->hydrate($document);

        $this->assertCount(1, $objects);
        $this->assertCount(2, $objects[0]->x);

        $this->assertAttributeSame("b", "type", $objects[0]->x[0]);
        $this->assertAttributeSame("1", "id", $objects[0]->x[0]);
        $this->assertAttributeSame("A", "a", $objects[0]->x[0]);
        $this->assertAttributeSame("B", "b", $objects[0]->x[0]);

        $this->assertAttributeSame("b", "type", $objects[0]->x[1]);
        $this->assertAttributeSame("2", "id", $objects[0]->x[1]);
        $this->assertAttributeSame("C", "a", $objects[0]->x[1]);
        $this->assertAttributeSame("D", "b", $objects[0]->x[1]);
    }

    /**
     * @test
     */
    public function hydrateWithSingleResourceNestedRelationships()
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
        $objects = $hydrator->hydrate($document);

        $this->assertCount(1, $objects);

        $this->assertObjectHasAttribute("x", $objects[0]);
        $this->assertObjectHasAttribute("y", $objects[0]->x);

        $this->assertAttributeSame("b", "type", $objects[0]->x->y);
        $this->assertAttributeSame("2", "id", $objects[0]->x->y);
        $this->assertAttributeSame("C", "a", $objects[0]->x->y);
        $this->assertAttributeSame("D", "b", $objects[0]->x->y);
    }

    /**
     * @test
     */
    public function hydrateWithSingleResourceRecursiveRelationships()
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
        $objects = $hydrator->hydrate($document);

        $this->assertCount(1, $objects);

        $this->assertObjectHasAttribute("x", $objects[0]);
        $this->assertObjectHasAttribute("y", $objects[0]->x);
        $this->assertObjectHasAttribute("z", $objects[0]->x->y);
        $this->assertSame($objects[0], $objects[0]->x->y->z);

        $this->assertAttributeSame("a", "type", $objects[0]->x->y->z);
        $this->assertAttributeSame("1", "id", $objects[0]->x->y->z);
        $this->assertAttributeSame("A", "a", $objects[0]->x->y->z);
        $this->assertAttributeSame("B", "b", $objects[0]->x->y->z);
    }

    /**
     * @test
     */
    public function hydrateSingleResourceWhenCollectionEmpty()
    {
        $document = [
            "data" => [],
        ];

        $document = Document::fromArray($document);
        $hydrator = new ClassDocumentHydrator();
        $object = $hydrator->hydrateSingleResource($document);

        $this->assertEquals(new stdClass(), $object);
    }

    /**
     * @test
     */
    public function hydrateSingleResourceWhenCollection()
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
        $hydrator = new ClassDocumentHydrator();
        $object = $hydrator->hydrateSingleResource($document);

        $this->assertEquals(new stdClass(), $object);
    }

    /**
     * @test
     */
    public function hydrateSingleResourceWhenSingleResourceEmpty()
    {
        $document = [
            "data" => null,
        ];

        $document = Document::fromArray($document);
        $hydrator = new ClassDocumentHydrator();
        $object = $hydrator->hydrateSingleResource($document);

        $this->assertEquals(new stdClass(), $object);
    }

    /**
     * @test
     */
    public function hydrateSingleResourceWhenSingleResource()
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
    public function hydrateCollectionWhenEmpty()
    {
        $document = [
            "data" => [],
        ];

        $document = Document::fromArray($document);
        $hydrator = new ClassDocumentHydrator();
        $collection = $hydrator->hydrateCollection($document);

        $this->assertCount(0, $collection);
    }

    /**
     * @test
     */
    public function hydrateCollectionWhenEmptySingleResource()
    {
        $document = [
            "data" => null,
        ];

        $document = Document::fromArray($document);
        $hydrator = new ClassDocumentHydrator();
        $collection = $hydrator->hydrateCollection($document);

        $this->assertCount(0, $collection);
    }

    /**
     * @test
     */
    public function hydrateCollectionWhenSingleResource()
    {
        $document = [
            "data" => [
                "type" => "a",
                "id" => "1",
            ],
        ];

        $document = Document::fromArray($document);
        $hydrator = new ClassDocumentHydrator();
        $collection = $hydrator->hydrateCollection($document);

        $this->assertSame([], $collection);
    }

    /**
     * @test
     */
    public function hydrateCollectionMultipleResources()
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
        $collection = $hydrator->hydrateCollection($document);

        $this->assertCount(2, $collection);
        $this->assertAttributeSame("a", "type", $collection[0]);
        $this->assertAttributeSame("1", "id", $collection[0]);
        $this->assertAttributeSame("a", "type", $collection[1]);
        $this->assertAttributeSame("2", "id", $collection[1]);
    }
}
