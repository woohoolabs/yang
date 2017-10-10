<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\Tests\JsonApi\Schema;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yang\JsonApi\Schema\ErrorSource;

class ErrorSourceTest extends TestCase
{
    /**
     * @test
     */
    public function toArray()
    {
        $source = ErrorSource::createFromArray(
            [
                "pointer" => "a",
                "parameter" => "b",
            ]
        );

        $this->assertSame(
            [
                "pointer" => "a",
                "parameter" => "b",
            ],
            $source->toArray()
        );
    }

    /**
     * @test
     */
    public function hasSourceIsTrueForPointer()
    {
        $source = ErrorSource::createFromArray(
            [
                "pointer" => "a",
            ]
        );

        $this->assertTrue($source->hasSource());
    }

    /**
     * @test
     */
    public function hasSourceIsTrueForParameter()
    {
        $source = ErrorSource::createFromArray(
            [
                "parameter" => "a",
            ]
        );

        $this->assertTrue($source->hasSource());
    }

    /**
     * @test
     */
    public function hasSourceIsFalse()
    {
        $source = ErrorSource::createFromArray([]);

        $this->assertFalse($source->hasSource());
    }

    /**
     * @test
     */
    public function hasPointerIsTrue()
    {
        $source = ErrorSource::createFromArray(
            [
                "pointer" => "a",
            ]
        );

        $this->assertTrue($source->hasPointer());
    }

    /**
     * @test
     */
    public function hasPointerIsFalse()
    {
        $source = ErrorSource::createFromArray([]);

        $this->assertFalse($source->hasPointer());
    }

    /**
     * @test
     */
    public function pointer()
    {
        $source = ErrorSource::createFromArray(
            [
                "pointer" => "a",
            ]
        );

        $this->assertSame("a", $source->pointer());
    }

    /**
     * @test
     */
    public function pointerWhenEmpty()
    {
        $source = ErrorSource::createFromArray([]);

        $this->assertSame("", $source->pointer());
    }

    /**
     * @test
     */
    public function hasParameterIsTrue()
    {
        $source = ErrorSource::createFromArray(
            [
                "parameter" => "a",
            ]
        );

        $this->assertTrue($source->hasParameter());
    }

    /**
     * @test
     */
    public function hasParameterIsFalse()
    {
        $source = ErrorSource::createFromArray([]);

        $this->assertFalse($source->hasParameter());
    }

    /**
     * @test
     */
    public function parameter()
    {
        $source = ErrorSource::createFromArray(
            [
                "parameter" => "a",
            ]
        );

        $this->assertSame("a", $source->parameter());
    }

    /**
     * @test
     */
    public function parameterWhenEmpty()
    {
        $source = ErrorSource::createFromArray([]);

        $this->assertSame("", $source->parameter());
    }
}
