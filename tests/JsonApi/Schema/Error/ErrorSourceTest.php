<?php

declare(strict_types=1);

namespace BahaaAlhagar\Yang\Tests\JsonApi\Schema\Error;

use PHPUnit\Framework\TestCase;
use BahaaAlhagar\Yang\JsonApi\Schema\Error\ErrorSource;

class ErrorSourceTest extends TestCase
{
    /**
     * @test
     */
    public function hasSourceIsTrueForPointer(): void
    {
        $source = ErrorSource::fromArray(
            [
                "pointer" => "a",
            ]
        );

        $this->assertTrue($source->hasSource());
    }

    /**
     * @test
     */
    public function hasSourceIsTrueForParameter(): void
    {
        $source = ErrorSource::fromArray(
            [
                "parameter" => "a",
            ]
        );

        $this->assertTrue($source->hasSource());
    }

    /**
     * @test
     */
    public function hasSourceIsFalse(): void
    {
        $source = ErrorSource::fromArray([]);

        $this->assertFalse($source->hasSource());
    }

    /**
     * @test
     */
    public function hasPointerIsTrue(): void
    {
        $source = ErrorSource::fromArray(
            [
                "pointer" => "a",
            ]
        );

        $this->assertTrue($source->hasPointer());
    }

    /**
     * @test
     */
    public function hasPointerIsFalse(): void
    {
        $source = ErrorSource::fromArray([]);

        $this->assertFalse($source->hasPointer());
    }

    /**
     * @test
     */
    public function pointer(): void
    {
        $source = ErrorSource::fromArray(
            [
                "pointer" => "a",
            ]
        );

        $this->assertSame("a", $source->pointer());
    }

    /**
     * @test
     */
    public function pointerWhenEmpty(): void
    {
        $source = ErrorSource::fromArray([]);

        $this->assertSame("", $source->pointer());
    }

    /**
     * @test
     */
    public function hasParameterIsTrue(): void
    {
        $source = ErrorSource::fromArray(
            [
                "parameter" => "a",
            ]
        );

        $this->assertTrue($source->hasParameter());
    }

    /**
     * @test
     */
    public function hasParameterIsFalse(): void
    {
        $source = ErrorSource::fromArray([]);

        $this->assertFalse($source->hasParameter());
    }

    /**
     * @test
     */
    public function parameter(): void
    {
        $source = ErrorSource::fromArray(
            [
                "parameter" => "a",
            ]
        );

        $this->assertSame("a", $source->parameter());
    }

    /**
     * @test
     */
    public function parameterWhenEmpty(): void
    {
        $source = ErrorSource::fromArray([]);

        $this->assertSame("", $source->parameter());
    }

    /**
     * @test
     */
    public function toArray(): void
    {
        $source = ErrorSource::fromArray(
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
    public function toArrayWhenMissing(): void
    {
        $source = ErrorSource::fromArray(
            []
        );

        $this->assertSame(
            [],
            $source->toArray()
        );
    }

    /**
     * @test
     */
    public function toArrayWhenEmpty(): void
    {
        $source = ErrorSource::fromArray(
            [
                "pointer" => "",
                "parameter" => "",
            ]
        );

        $this->assertSame(
            [],
            $source->toArray()
        );
    }
}
