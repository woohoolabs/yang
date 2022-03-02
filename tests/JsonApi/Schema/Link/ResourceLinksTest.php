<?php

declare(strict_types=1);

namespace BahaaAlhagar\Yang\Tests\JsonApi\Schema\Link;

use PHPUnit\Framework\TestCase;
use BahaaAlhagar\Yang\JsonApi\Schema\Link\Link;
use BahaaAlhagar\Yang\JsonApi\Schema\Link\ResourceLinks;
use BahaaAlhagar\Yang\JsonApi\Exception\DocumentException;

class ResourceLinksTest extends TestCase
{
    /**
     * @test
     */
    public function hasSelfIsTrue(): void
    {
        $links = ResourceLinks::fromArray(
            [
                "self" => "",
            ]
        );

        $hasSelf = $links->hasSelf();

        $this->assertTrue($hasSelf);
    }

    /**
     * @test
     */
    public function hasSelfIsFalse(): void
    {
        $links = ResourceLinks::fromArray([]);

        $hasSelf = $links->hasSelf();

        $this->assertFalse($hasSelf);
    }

    /**
     * @test
     */
    public function selfReturnsObject(): void
    {
        $links = ResourceLinks::fromArray(
            [
                "self" => [],
            ]
        );

        $self = $links->self();

        $this->assertEquals(new Link(""), $self);
    }

    /**
     * @test
     */
    public function selfWhenMissing(): void
    {
        $links = ResourceLinks::fromArray([]);

        $this->expectException(DocumentException::class);

        $links->self();
    }
}
