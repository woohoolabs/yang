<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\Tests\JsonApi\Hydrator;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yang\JsonApi\Hydrator\ClassHydrator;
use WoohooLabs\Yang\JsonApi\Schema\Document;

class ClassHydratorTest extends TestCase
{
    /**
     * @test
     */
    public function doNotHydrateErrorDocument()
    {
        $document = [
            "errors" => [],
        ];

        $document = Document::createFromArray($document);
        $hydrator = new ClassHydrator();
        $object = $hydrator->hydrate($document);

        $this->assertEquals(new \stdClass(), $object);
    }

    /**
     * @test
     */
    public function hydrateObjectWithTypeAndId()
    {
        $document = [
            "data" => [
                "type" => "a",
                "id" => "1",
            ],
        ];

        $document = Document::createFromArray($document);
        $hydrator = new ClassHydrator();
        $object = $hydrator->hydrate($document);

        $this->assertAttributeSame("a", "type", $object);
        $this->assertAttributeSame("1", "id", $object);
    }

    /**
     * @test
     */
    public function hydrateObjectWithAttributes()
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

        $document = Document::createFromArray($document);
        $hydrator = new ClassHydrator();
        $object = $hydrator->hydrate($document);

        $this->assertAttributeSame("A", "a", $object);
        $this->assertAttributeSame("B", "b", $object);
        $this->assertAttributeSame("C", "c", $object);
    }

    /**
     * @test
     */
    public function hydrateObjectsWithAttributes()
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

        $document = Document::createFromArray($document);
        $hydrator = new ClassHydrator();
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
    public function hydrateObjectWithNotIncludedToOneRelationship()
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

        $document = Document::createFromArray($document);
        $hydrator = new ClassHydrator();
        $object = $hydrator->hydrate($document);

        $this->assertObjectNotHasAttribute("x", $object);
    }

    /**
     * @test
     */
    public function hydrateObjectWithIncludedToOneRelationship()
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

        $document = Document::createFromArray($document);
        $hydrator = new ClassHydrator();
        $object = $hydrator->hydrate($document);

        $this->assertObjectHasAttribute("x", $object);
        $this->assertAttributeSame("b", "type", $object->x);
        $this->assertAttributeSame("0", "id", $object->x);
        $this->assertAttributeSame("A", "a", $object->x);
    }

    /**
     * @test
     */
    public function hydrateObjectWithToManyRelationship()
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

        $document = Document::createFromArray($document);
        $hydrator = new ClassHydrator();
        $object = $hydrator->hydrate($document);

        $this->assertCount(2, $object->x);

        $this->assertAttributeSame("b", "type", $object->x[0]);
        $this->assertAttributeSame("1", "id", $object->x[0]);
        $this->assertAttributeSame("A", "a", $object->x[0]);
        $this->assertAttributeSame("B", "b", $object->x[0]);

        $this->assertAttributeSame("b", "type", $object->x[1]);
        $this->assertAttributeSame("2", "id", $object->x[1]);
        $this->assertAttributeSame("C", "a", $object->x[1]);
        $this->assertAttributeSame("D", "b", $object->x[1]);
    }

    /**
     * @test
     */
    public function hydrateObjectWithNestedRelationships()
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

        $document = Document::createFromArray($document);
        $hydrator = new ClassHydrator();
        $object = $hydrator->hydrate($document);

        $this->assertObjectHasAttribute("x", $object);
        $this->assertObjectHasAttribute("y", $object->x);

        $this->assertAttributeSame("b", "type", $object->x->y);
        $this->assertAttributeSame("2", "id", $object->x->y);
        $this->assertAttributeSame("C", "a", $object->x->y);
        $this->assertAttributeSame("D", "b", $object->x->y);
    }

    /**
     * @test
     */
    public function hydrateObjectWithRecursiveRelationships()
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

        $document = Document::createFromArray($document);
        $hydrator = new ClassHydrator();
        $object = $hydrator->hydrate($document);

        $this->assertObjectHasAttribute("x", $object);
        $this->assertObjectHasAttribute("y", $object->x);
        $this->assertObjectHasAttribute("z", $object->x->y);
        $this->assertSame($object, $object->x->y->z);

        $this->assertAttributeSame("a", "type", $object->x->y->z);
        $this->assertAttributeSame("1", "id", $object->x->y->z);
        $this->assertAttributeSame("A", "a", $object->x->y->z);
        $this->assertAttributeSame("B", "b", $object->x->y->z);
    }
}
