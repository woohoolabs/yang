<?php

declare(strict_types=1);

namespace WoohooLabs\Yang\Tests\JsonApi\Schema\Link;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yang\JsonApi\Exception\DocumentException;
use WoohooLabs\Yang\JsonApi\Schema\Link\Link;
use WoohooLabs\Yang\JsonApi\Schema\Link\ResourceLinks;

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
