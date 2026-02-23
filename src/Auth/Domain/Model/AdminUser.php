<?php

declare(strict_types=1);

namespace App\Auth\Domain\Model;

use App\Auth\Domain\ValueObject\AdminUserId;
use App\Auth\Domain\ValueObject\Email;

final class AdminUser
{
    private function __construct(
        private readonly AdminUserId $id,
        private readonly Email $email,
        private string $hashedPassword,
        private readonly \DateTimeImmutable $createdAt,
    ) {
    }

    public static function create(AdminUserId $id, Email $email, string $hashedPassword): self
    {
        return new self($id, $email, $hashedPassword, new \DateTimeImmutable());
    }

    public function id(): AdminUserId
    {
        return $this->id;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function hashedPassword(): string
    {
        return $this->hashedPassword;
    }

    public function createdAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}
