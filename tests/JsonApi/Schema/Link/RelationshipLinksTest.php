<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\Tests\JsonApi\Schema\Link;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yang\JsonApi\Exception\LinkException;
use WoohooLabs\Yang\JsonApi\Schema\Link\Link;
use WoohooLabs\Yang\JsonApi\Schema\Link\RelationshipLinks;

class RelationshipLinksTest extends TestCase
{
    /**
     * @test
     */
    public function hasSelfIsTrue()
    {
        $links = RelationshipLinks::fromArray(
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
        $links = RelationshipLinks::fromArray([]);

        $hasSelf = $links->hasSelf();

        $this->assertFalse($hasSelf);
    }

    /**
     * @test
     */
    public function selfReturnsObject()
    {
        $links = RelationshipLinks::fromArray(
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
        $links = RelationshipLinks::fromArray([]);

        $this->expectException(LinkException::class);

        $links->self();
    }

    /**
     * @test
     */
    public function hasRelatedIsTrue()
    {
        $links = RelationshipLinks::fromArray(
            [
                "related" => "",
            ]
        );

        $hasRelated = $links->hasRelated();

        $this->assertTrue($hasRelated);
    }

    /**
     * @test
     */
    public function hasRelatedIsFalse()
    {
        $links = RelationshipLinks::fromArray([]);

        $hasRelated = $links->hasRelated();

        $this->assertFalse($hasRelated);
    }

    /**
     * @test
     */
    public function relatedReturnsObject()
    {
        $links = RelationshipLinks::fromArray(
            [
                "related" => [],
            ]
        );

        $related = $links->related();

        $this->assertEquals(new Link(""), $related);
    }

    /**
     * @test
     */
    public function relatedWhenMissing()
    {
        $links = RelationshipLinks::fromArray([]);

        $this->expectException(LinkException::class);

        $links->related();
    }
}
