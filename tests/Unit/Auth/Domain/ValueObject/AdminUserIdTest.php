<?php

declare(strict_types=1);

namespace App\Tests\Unit\Auth\Domain\ValueObject;

use App\Auth\Domain\ValueObject\AdminUserId;
use PHPUnit\Framework\TestCase;

final class AdminUserIdTest extends TestCase
{
    public function testGenerateCreatesValidUuid(): void
    {
        $id = AdminUserId::generate();

        $this->assertMatchesRegularExpression(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i',
            $id->value()
        );
    }

    public function testGenerateCreatesUniqueIds(): void
    {
        $id1 = AdminUserId::generate();
        $id2 = AdminUserId::generate();

        $this->assertNotSame($id1->value(), $id2->value());
    }

    public function testFromStringCreatesFromValidUuid(): void
    {
        $uuid = '550e8400-e29b-41d4-a716-446655440000';
        $id = AdminUserId::fromString($uuid);

        $this->assertSame($uuid, $id->value());
    }

    public function testValueReturnsStringRepresentation(): void
    {
        $uuid = '550e8400-e29b-41d4-a716-446655440000';
        $id = AdminUserId::fromString($uuid);

        $this->assertIsString($id->value());
        $this->assertSame($uuid, $id->value());
    }

    public function testToStringReturnsValue(): void
    {
        $uuid = '550e8400-e29b-41d4-a716-446655440000';
        $id = AdminUserId::fromString($uuid);

        $this->assertSame($uuid, (string) $id);
    }
}
