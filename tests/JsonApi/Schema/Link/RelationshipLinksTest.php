<?php

declare(strict_types=1);

namespace BahaaAlhagar\Yang\Tests\JsonApi\Schema\Link;

use PHPUnit\Framework\TestCase;
use BahaaAlhagar\Yang\JsonApi\Schema\Link\Link;
use BahaaAlhagar\Yang\JsonApi\Exception\DocumentException;
use BahaaAlhagar\Yang\JsonApi\Schema\Link\RelationshipLinks;

class RelationshipLinksTest extends TestCase
{
    /**
     * @test
     */
    public function hasSelfIsTrue(): void
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
    public function hasSelfIsFalse(): void
    {
        $links = RelationshipLinks::fromArray([]);

        $hasSelf = $links->hasSelf();

        $this->assertFalse($hasSelf);
    }

    /**
     * @test
     */
    public function selfReturnsObject(): void
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
    public function selfWhenMissing(): void
    {
        $links = RelationshipLinks::fromArray([]);

        $this->expectException(DocumentException::class);

        $links->self();
    }

    /**
     * @test
     */
    public function hasRelatedIsTrue(): void
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
    public function hasRelatedIsFalse(): void
    {
        $links = RelationshipLinks::fromArray([]);

        $hasRelated = $links->hasRelated();

        $this->assertFalse($hasRelated);
    }

    /**
     * @test
     */
    public function relatedReturnsObject(): void
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
    public function relatedWhenMissing(): void
    {
        $links = RelationshipLinks::fromArray([]);

        $this->expectException(DocumentException::class);

        $links->related();
    }
}
