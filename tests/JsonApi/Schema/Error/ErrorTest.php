<?php

declare(strict_types=1);

namespace BahaaAlhagar\Yang\Tests\JsonApi\Schema\Error;

use PHPUnit\Framework\TestCase;
use BahaaAlhagar\Yang\JsonApi\Schema\Error\Error;
use BahaaAlhagar\Yang\JsonApi\Schema\Link\ErrorLinks;
use BahaaAlhagar\Yang\JsonApi\Schema\Error\ErrorSource;

class ErrorTest extends TestCase
{
    /**
     * @test
     */
    public function id(): void
    {
        $error = Error::fromArray(
            [
                "id" => "a",
            ]
        );

        $id = $error->id();

        $this->assertSame("a", $id);
    }

    /**
     * @test
     */
    public function hasMetaIsTrue(): void
    {
        $error = Error::fromArray(
            [
                "meta" => [
                    "a" => "b",
                ],
            ]
        );

        $hasMeta = $error->hasMeta();

        $this->assertTrue($hasMeta);
    }

    /**
     * @test
     */
    public function hasMetaIsFalse(): void
    {
        $error = Error::fromArray([]);

        $hasMeta = $error->hasMeta();

        $this->assertFalse($hasMeta);
    }

    /**
     * @test
     */
    public function meta(): void
    {
        $error = Error::fromArray(
            [
                "meta" => [
                    "a" => "b",
                ],
            ]
        );

        $meta = $error->meta();

        $this->assertSame(["a" => "b"], $meta);
    }

    /**
     * @test
     */
    public function hasLinksIsTrue(): void
    {
        $error = Error::fromArray(
            [
                "links" => [
                    "a" => "b",
                ],
            ]
        );

        $hasLinks = $error->hasLinks();

        $this->assertTrue($hasLinks);
    }

    /**
     * @test
     */
    public function hasLinksIsFalse(): void
    {
        $error = Error::fromArray([]);

        $hasLinks = $error->hasLinks();

        $this->assertFalse($hasLinks);
    }

    /**
     * @test
     */
    public function linksReturnsObject(): void
    {
        $error = Error::fromArray([]);

        $links = $error->links();

        $this->assertEquals(new ErrorLinks([], []), $links);
    }

    /**
     * @test
     */
    public function status(): void
    {
        $error = Error::fromArray(
            [
                "status" => "400",
            ]
        );

        $status = $error->status();

        $this->assertSame("400", $status);
    }

    /**
     * @test
     */
    public function code(): void
    {
        $error = Error::fromArray(
            [
                "code" => "a",
            ]
        );

        $code = $error->code();

        $this->assertSame("a", $code);
    }

    /**
     * @test
     */
    public function title(): void
    {
        $error = Error::fromArray(
            [
                "title" => "a",
            ]
        );

        $title = $error->title();

        $this->assertSame("a", $title);
    }

    /**
     * @test
     */
    public function detail(): void
    {
        $error = Error::fromArray(
            [
                "detail" => "a",
            ]
        );

        $detail = $error->detail();

        $this->assertSame("a", $detail);
    }

    /**
     * @test
     */
    public function sourceReturnsObject(): void
    {
        $error = Error::fromArray([]);

        $source = $error->source();

        $this->assertEquals(new ErrorSource("", ""), $source);
    }

    /**
     * @test
     */
    public function toArray(): void
    {
        $error = Error::fromArray(
            [
                "id" => "a",
                "meta" => [
                    "a" => "b",
                ],
                "links" => [
                    "a" => "b",
                ],
                "status" => "1",
                "code" => "a",
                "title" => "A",
                "detail" => "B",
                "source" => [
                    "pointer" => "a",
                    "parameter" => "b",
                ],
            ]
        );

        $array = $error->toArray();

        $this->assertSame(
            [
                "id" => "a",
                "meta" => [
                    "a" => "b",
                ],
                "links" => [
                    "a" => [
                        "href" => "b",
                    ],
                ],
                "status" => "1",
                "code" => "a",
                "title" => "A",
                "detail" => "B",
                "source" => [
                    "pointer" => "a",
                    "parameter" => "b",
                ],
            ],
            $array
        );
    }

    /**
     * @test
     */
    public function toArrayWhenEmpty(): void
    {
        $error = Error::fromArray(
            [
                "id" => "",
                "status" => "",
                "code" => "",
                "title" => "",
                "detail" => "",
            ]
        );

        $array = $error->toArray();

        $this->assertSame([], $array);
    }

    /**
     * @test
     */
    public function toArrayWhenZero(): void
    {
        $error = Error::fromArray(
            [
                "id" => "0",
                "status" => "0",
                "code" => "0",
                "title" => "0",
                "detail" => "0",
            ]
        );

        $array = $error->toArray();

        $this->assertSame(
            [
                "id" => "0",
                "status" => "0",
                "code" => "0",
                "title" => "0",
                "detail" => "0",
            ],
            $array
        );
    }
}
