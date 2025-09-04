<?php

declare(strict_types=1);

namespace App\Domain\Version\Model;

use DateTimeInterface;

final class Version
{
    public function __construct(
        private readonly string $value,
        private readonly DateTimeInterface $createdAt,
        private readonly bool $active = false
    ) {
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function activate(): self
    {
        return new self($this->value, $this->createdAt, true);
    }

    public function deactivate(): self
    {
        return new self($this->value, $this->createdAt, false);
    }

    public function isNewerThan(self $other): bool
    {
        return version_compare($this->value, $other->value, '>');
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

}
