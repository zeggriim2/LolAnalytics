<?php

declare(strict_types=1);

namespace App\Tests\Unit\Auth\Domain\Model;

use App\Auth\Domain\Model\AdminUser;
use App\Auth\Domain\ValueObject\AdminUserId;
use App\Auth\Domain\ValueObject\Email;
use PHPUnit\Framework\TestCase;

final class AdminUserTest extends TestCase
{
    public function testCreateReturnsAdminUserWithCorrectValues(): void
    {
        $id = AdminUserId::generate();
        $email = Email::fromString('admin@lol.local');
        $hashedPassword = '$2y$13$hashedpassword';

        $user = AdminUser::create($id, $email, $hashedPassword);

        $this->assertSame($id, $user->id());
        $this->assertSame($email, $user->email());
        $this->assertSame($hashedPassword, $user->hashedPassword());
    }

    public function testCreateSetsCreatedAtToNow(): void
    {
        $before = new \DateTimeImmutable();
        $user = AdminUser::create(
            AdminUserId::generate(),
            Email::fromString('admin@lol.local'),
            'hashed'
        );
        $after = new \DateTimeImmutable();

        $this->assertGreaterThanOrEqual($before->getTimestamp(), $user->createdAt()->getTimestamp());
        $this->assertLessThanOrEqual($after->getTimestamp(), $user->createdAt()->getTimestamp());
    }

    public function testIdReturnsAdminUserId(): void
    {
        $id = AdminUserId::fromString('550e8400-e29b-41d4-a716-446655440000');
        $user = AdminUser::create($id, Email::fromString('admin@lol.local'), 'hashed');

        $this->assertSame('550e8400-e29b-41d4-a716-446655440000', $user->id()->value());
    }

    public function testEmailReturnsEmailValueObject(): void
    {
        $email = Email::fromString('ADMIN@LOL.LOCAL');
        $user = AdminUser::create(AdminUserId::generate(), $email, 'hashed');

        $this->assertSame('admin@lol.local', $user->email()->value());
    }
}
