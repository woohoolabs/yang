<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\Tests\JsonApi\Schema\Link;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yang\JsonApi\Exception\DocumentException;
use WoohooLabs\Yang\JsonApi\Schema\Link\DocumentLinks;
use WoohooLabs\Yang\JsonApi\Schema\Link\Link;
use WoohooLabs\Yang\JsonApi\Schema\Link\ProfileLink;

class DocumentLinksTest extends TestCase
{
    /**
     * @test
     */
    public function hasSelfIsTrue()
    {
        $links = DocumentLinks::fromArray(
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
        $links = DocumentLinks::fromArray([]);

        $hasSelf = $links->hasSelf();

        $this->assertFalse($hasSelf);
    }

    /**
     * @test
     */
    public function selfReturnsObject()
    {
        $links = DocumentLinks::fromArray(
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
        $links = DocumentLinks::fromArray([]);

        $this->expectException(DocumentException::class);

        $links->self();
    }

    /**
     * @test
     */
    public function hasRelatedIsTrue()
    {
        $links = DocumentLinks::fromArray(
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
        $links = DocumentLinks::fromArray([]);

        $hasRelated = $links->hasRelated();

        $this->assertFalse($hasRelated);
    }

    /**
     * @test
     */
    public function relatedReturnsObject()
    {
        $links = DocumentLinks::fromArray(
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
        $links = DocumentLinks::fromArray([]);

        $this->expectException(DocumentException::class);

        $links->related();
    }

    /**
     * @test
     */
    public function hasFirstIsTrue()
    {
        $links = DocumentLinks::fromArray(
            [
                "first" => "",
            ]
        );

        $hasFirst = $links->hasFirst();

        $this->assertTrue($hasFirst);
    }

    /**
     * @test
     */
    public function hasFirstIsFalse()
    {
        $links = DocumentLinks::fromArray([]);

        $hasFirst = $links->hasFirst();

        $this->assertFalse($hasFirst);
    }

    /**
     * @test
     */
    public function firstReturnsObject()
    {
        $links = DocumentLinks::fromArray(
            [
                "first" => [],
            ]
        );

        $first = $links->first();

        $this->assertEquals(new Link(""), $first);
    }

    /**
     * @test
     */
    public function firstWhenMissing()
    {
        $links = DocumentLinks::fromArray([]);

        $this->expectException(DocumentException::class);

        $links->first();
    }

    /**
     * @test
     */
    public function hasLastIsTrue()
    {
        $links = DocumentLinks::fromArray(
            [
                "last" => "",
            ]
        );

        $hasLast = $links->hasLast();

        $this->assertTrue($hasLast);
    }

    /**
     * @test
     */
    public function hasLastIsFalse()
    {
        $links = DocumentLinks::fromArray([]);

        $hasLast = $links->hasLast();

        $this->assertFalse($hasLast);
    }

    /**
     * @test
     */
    public function lastReturnsObject()
    {
        $links = DocumentLinks::fromArray(
            [
                "last" => [],
            ]
        );

        $last = $links->last();

        $this->assertEquals(new Link(""), $last);
    }

    /**
     * @test
     */
    public function lastWhenMissing()
    {
        $links = DocumentLinks::fromArray([]);

        $this->expectException(DocumentException::class);

        $links->last();
    }

    /**
     * @test
     */
    public function hasPrevIsTrue()
    {
        $links = DocumentLinks::fromArray(
            [
                "prev" => "",
            ]
        );

        $hasPrev = $links->hasPrev();

        $this->assertTrue($hasPrev);
    }

    /**
     * @test
     */
    public function hasPrevIsFalse()
    {
        $links = DocumentLinks::fromArray([]);

        $hasPrev = $links->hasPrev();

        $this->assertFalse($hasPrev);
    }

    /**
     * @test
     */
    public function prevReturnsObject()
    {
        $links = DocumentLinks::fromArray(
            [
                "prev" => [],
            ]
        );

        $prev = $links->prev();

        $this->assertEquals(new Link(""), $prev);
    }

    /**
     * @test
     */
    public function prevWhenMissing()
    {
        $links = DocumentLinks::fromArray([]);

        $this->expectException(DocumentException::class);

        $links->prev();
    }

    /**
     * @test
     */
    public function hasNextIsTrue()
    {
        $links = DocumentLinks::fromArray(
            [
                "next" => "",
            ]
        );

        $this->assertTrue($links->hasNext());
    }

    /**
     * @test
     */
    public function hasNextIsFalse()
    {
        $links = DocumentLinks::fromArray([]);

        $hasNext = $links->hasNext();

        $this->assertFalse($hasNext);
    }

    /**
     * @test
     */
    public function nextReturnsObject()
    {
        $links = DocumentLinks::fromArray(
            [
                "next" => [],
            ]
        );

        $next = $links->next();

        $this->assertEquals(new Link(""), $next);
    }

    /**
     * @test
     */
    public function nextWhenMissing()
    {
        $links = DocumentLinks::fromArray([]);

        $this->expectException(DocumentException::class);

        $links->next();
    }

    /**
     * @test
     */
    public function hasProfileWhenTrue()
    {
        $links = DocumentLinks::fromArray(
            [
                "profile" => [
                    "",
                ],
            ]
        );

        $profile = $links->hasProfile("");

        $this->assertTrue($profile);
    }

    /**
     * @test
     */
    public function hasProfileWhenFalse()
    {
        $links = DocumentLinks::fromArray([]);

        $profile = $links->hasProfile("");

        $this->assertFalse($profile);
    }

    /**
     * @test
     */
    public function getProfileWhenPresent()
    {
        $links = DocumentLinks::fromArray(
            [
                "profile" => [
                    "",
                ],
            ]
        );

        $profile = $links->profile("");

        $this->assertEquals(new ProfileLink(""), $profile);
    }

    /**
     * @test
     */
    public function getProfileWhenEmpty()
    {
        $links = DocumentLinks::fromArray([]);

        $this->expectException(DocumentException::class);

        $links->profile("");
    }

    /**
     * @test
     */
    public function getProfileWhenMissing()
    {
        $links = DocumentLinks::fromArray(
            [
                "profile" => [
                    "",
                ],
            ]
        );

        $this->expectException(DocumentException::class);

        $links->profile("abc");
    }

    /**
     * @test
     */
    public function hasAnyProfilesWhenTrue()
    {
        $links = DocumentLinks::fromArray(
            [
                "profile" => [
                    "",
                ],
            ]
        );

        $hasAnyProfiles = $links->hasAnyProfiles();

        $this->assertTrue($hasAnyProfiles);
    }

    /**
     * @test
     */
    public function hasAnyProfilesWhenFalse()
    {
        $links = DocumentLinks::fromArray([]);

        $hasAnyProfiles = $links->hasAnyProfiles();

        $this->assertFalse($hasAnyProfiles);
    }

    /**
     * @test
     */
    public function getProfiles()
    {
        $links = DocumentLinks::fromArray(
            [
                "profile" => [
                    "a",
                    [
                        "href" => "b",
                    ],
                ],
            ]
        );

        $profiles = $links->profiles();

        $this->assertEquals(
            [
                new ProfileLink("a"),
                new ProfileLink("b"),
            ],
            $profiles
        );
    }

    /**
     * @test
     */
    public function fromArray()
    {
        $links = DocumentLinks::fromArray(
            [
                "self" => "",
                "related" => [],
                "profile" => [],
            ]
        );

        $linksArray = $links->links();

        $this->assertArrayHasKey("self", $linksArray);
        $this->assertArrayHasKey("related", $linksArray);
        $this->assertArrayNotHasKey("profile", $linksArray);
    }

    /**
     * @test
     */
    public function toArray()
    {
        $links = DocumentLinks::fromArray(
            [
                "self" => "",
                "related" => [],
                "profile" => [
                    "",
                ],
            ]
        );

        $linksArray = $links->toArray();

        $this->assertArrayHasKey("self", $linksArray);
        $this->assertArrayHasKey("related", $linksArray);
        $this->assertArrayHasKey("profile", $linksArray);
    }
}
