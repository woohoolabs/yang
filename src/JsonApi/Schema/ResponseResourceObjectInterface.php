<?php
declare(strict_types=1);

namespace WoohooLabs\Yang\JsonApi\Schema;

interface ResponseResourceObjectInterface
{
    public function toArray(): array;

    public function type(): string;

    public function id(): string;

    public function hasMeta(): bool;

    public function meta(): array;

    public function hasLinks(): bool;

    public function links(): Links;

    public function attributes(): array;

    public function idAndAttributes(): array;

    public function hasAttribute(string $name): bool;

    /**
     * @return mixed|null
     */
    public function attribute(string $name);

    public function hasRelationship(string $name): bool;

    /**
     * @return Relationship|null
     */
    public function relationship(string $name);
}
