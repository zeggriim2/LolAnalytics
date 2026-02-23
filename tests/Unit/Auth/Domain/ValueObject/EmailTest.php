<?php

declare(strict_types=1);

namespace App\Tests\Unit\Auth\Domain\ValueObject;

use App\Auth\Domain\ValueObject\Email;
use PHPUnit\Framework\TestCase;

final class EmailTest extends TestCase
{
    public function testCreateValidEmail(): void
    {
        $email = Email::fromString('user@example.com');

        $this->assertSame('user@example.com', $email->value());
    }

    public function testEmailIsLowercased(): void
    {
        $email = Email::fromString('User@Example.COM');

        $this->assertSame('user@example.com', $email->value());
    }

    public function testEmailIsTrimmed(): void
    {
        $email = Email::fromString('  user@example.com  ');

        $this->assertSame('user@example.com', $email->value());
    }

    public function testEmailIsTrimmedAndLowercased(): void
    {
        $email = Email::fromString('  ADMIN@LOL.LOCAL  ');

        $this->assertSame('admin@lol.local', $email->value());
    }

    public function testToStringReturnsValue(): void
    {
        $email = Email::fromString('user@example.com');

        $this->assertSame('user@example.com', (string) $email);
    }

    public function testInvalidEmailThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid email address: "not-an-email"');

        Email::fromString('not-an-email');
    }

    public function testEmptyEmailThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Email::fromString('');
    }

    public function testEmailWithoutDomainThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Email::fromString('user@');
    }
}
