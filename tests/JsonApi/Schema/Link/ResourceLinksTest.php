<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\Tests\JsonApi\Schema\Link;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yang\JsonApi\Exception\LinkException;
use WoohooLabs\Yang\JsonApi\Schema\Link\ResourceLinks;
use WoohooLabs\Yang\JsonApi\Schema\Link\Link;

class ResourceLinksTest extends TestCase
{
    /**
     * @test
     */
    public function hasSelfIsTrue()
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
    public function hasSelfIsFalse()
    {
        $links = ResourceLinks::fromArray([]);

        $hasSelf = $links->hasSelf();

        $this->assertFalse($hasSelf);
    }

    /**
     * @test
     */
    public function selfReturnsObject()
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
    public function selfWhenMissing()
    {
        $links = ResourceLinks::fromArray([]);

        $this->expectException(LinkException::class);

        $links->self();
    }
}
