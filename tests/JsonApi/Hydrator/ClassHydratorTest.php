<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\Tests\JsonApi\Hydrator;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use WoohooLabs\Yang\JsonApi\Hydrator\ClassHydrator;
use WoohooLabs\Yang\JsonApi\Schema\Document;
use WoohooLabs\Yang\JsonApi\Schema\JsonApi;
use WoohooLabs\Yang\JsonApi\Serializer\JsonDeserializer;

class ClassHydratorTest extends TestCase
{
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

        $this->assertAttributeEquals("a", "type", $object);
        $this->assertAttributeEquals("1", "id", $object);
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

        $this->assertAttributeEquals("A", "a", $object);
        $this->assertAttributeEquals("B", "b", $object);
        $this->assertAttributeEquals("C", "c", $object);
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
                            "id" => "1",
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
            ],
        ];

        $document = Document::createFromArray($document);
        $hydrator = new ClassHydrator();
        $object = $hydrator->hydrate($document);

        $this->assertObjectHasAttribute("x", $object);
        $this->assertAttributeEquals("b", "type", $object->x);
        $this->assertAttributeEquals("1", "id", $object->x);
        $this->assertAttributeEquals("A", "a", $object->x);
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

        $this->assertAttributeEquals("b", "type", $object->x[0]);
        $this->assertAttributeEquals("1", "id", $object->x[0]);
        $this->assertAttributeEquals("A", "a", $object->x[0]);
        $this->assertAttributeEquals("B", "b", $object->x[0]);

        $this->assertAttributeEquals("b", "type", $object->x[1]);
        $this->assertAttributeEquals("2", "id", $object->x[1]);
        $this->assertAttributeEquals("C", "a", $object->x[1]);
        $this->assertAttributeEquals("D", "b", $object->x[1]);
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

        $this->assertAttributeEquals("b", "type", $object->x->y);
        $this->assertAttributeEquals("2", "id", $object->x->y);
        $this->assertAttributeEquals("C", "a", $object->x->y);
        $this->assertAttributeEquals("D", "b", $object->x->y);
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

        $this->assertAttributeEquals("a", "type", $object->x->y->z);
        $this->assertAttributeEquals("1", "id", $object->x->y->z);
        $this->assertAttributeEquals("A", "a", $object->x->y->z);
        $this->assertAttributeEquals("B", "b", $object->x->y->z);
    }
}
