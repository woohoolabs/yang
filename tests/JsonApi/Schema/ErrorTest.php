<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\Tests\JsonApi\Schema;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yang\JsonApi\Schema\Error;
use WoohooLabs\Yang\JsonApi\Schema\ErrorSource;
use WoohooLabs\Yang\JsonApi\Schema\Links;

class ErrorTest extends TestCase
{
    /**
     * @test
     */
    public function toArray()
    {
        $error = Error::createFromArray(
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
            $error->toArray()
        );
    }

    /**
     * @test
     */
    public function id()
    {
        $error = Error::createFromArray(
            [
                "id" => "a",
            ]
        );

        $this->assertSame("a", $error->id());
    }

    /**
     * @test
     */
    public function hasMetaIsTrue()
    {
        $error = Error::createFromArray(
            [
                "meta" => [
                    "a" => "b",
                ],
            ]
        );

        $this->assertTrue($error->hasMeta());
    }

    /**
     * @test
     */
    public function hasMetaIsFalse()
    {
        $error = Error::createFromArray([]);

        $this->assertFalse($error->hasMeta());
    }

    /**
     * @test
     */
    public function meta()
    {
        $error = Error::createFromArray(
            [
                "meta" => [
                    "a" => "b",
                ],
            ]
        );

        $this->assertSame(["a" => "b"], $error->meta());
    }

    /**
     * @test
     */
    public function hasLinksIsTrue()
    {
        $error = Error::createFromArray(
            [
                "links" => [
                    "a" => "b",
                ],
            ]
        );

        $this->assertTrue($error->hasLinks());
    }

    /**
     * @test
     */
    public function hasLinksIsFalse()
    {
        $error = Error::createFromArray([]);

        $this->assertFalse($error->hasLinks());
    }

    /**
     * @test
     */
    public function linksReturnsObject()
    {
        $error = Error::createFromArray([]);

        $this->assertInstanceOf(Links::class, $error->links());
    }

    /**
     * @test
     */
    public function status()
    {
        $error = Error::createFromArray(
            [
                "status" => "400"
            ]
        );

        $this->assertSame("400", $error->status());
    }

    /**
     * @test
     */
    public function code()
    {
        $error = Error::createFromArray(
            [
                "code" => "a",
            ]
        );

        $this->assertSame("a", $error->code());
    }

    /**
     * @test
     */
    public function title()
    {
        $error = Error::createFromArray(
            [
                "title" => "a",
            ]
        );

        $this->assertSame("a", $error->title());
    }

    /**
     * @test
     */
    public function detail()
    {
        $error = Error::createFromArray(
            [
                "detail" => "a",
            ]
        );

        $this->assertSame("a", $error->detail());
    }

    /**
     * @test
     */
    public function sourceReturnsObject()
    {
        $error = Error::createFromArray([]);

        $this->assertInstanceOf(ErrorSource::class, $error->source());
    }
}
