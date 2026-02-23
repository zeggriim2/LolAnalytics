<?php

declare(strict_types=1);

namespace App\Tests\Functional\Auth\Infrastructure\Repository;

use App\Auth\Domain\Model\AdminUser;
use App\Auth\Domain\Repository\AdminUserRepositoryInterface;
use App\Auth\Domain\ValueObject\AdminUserId;
use App\Auth\Domain\ValueObject\Email;
use App\Tests\Factory\AdminUserEntityFactory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Foundry\Test\ResetDatabase;

final class DoctrineAdminUserRepositoryTest extends KernelTestCase
{
    use ResetDatabase;

    private AdminUserRepositoryInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();

        $this->repository = static::getContainer()->get(AdminUserRepositoryInterface::class);
    }

    public function testFindByEmailReturnsAdminUserWhenExists(): void
    {
        // Given
        AdminUserEntityFactory::createOne(['email' => 'admin@lol.local']);

        // When
        $user = $this->repository->findByEmail(Email::fromString('admin@lol.local'));

        // Then
        $this->assertNotNull($user);
        $this->assertSame('admin@lol.local', $user->email()->value());
    }

    public function testFindByEmailReturnsNullWhenNotFound(): void
    {
        // When
        $user = $this->repository->findByEmail(Email::fromString('unknown@lol.local'));

        // Then
        $this->assertNull($user);
    }

    public function testSavePersistsAdminUser(): void
    {
        // Given
        $id = AdminUserId::generate();
        $email = Email::fromString('new@lol.local');
        $user = AdminUser::create($id, $email, '$2y$13$hashedpassword');

        // When
        $this->repository->save($user);

        // Then
        $found = $this->repository->findByEmail($email);

        $this->assertNotNull($found);
        $this->assertSame($id->value(), $found->id()->value());
        $this->assertSame('new@lol.local', $found->email()->value());
        $this->assertSame('$2y$13$hashedpassword', $found->hashedPassword());
    }

    public function testFindByEmailIsCaseInsensitive(): void
    {
        // Given
        AdminUserEntityFactory::createOne(['email' => 'admin@lol.local']);

        // When: Email VO lowercases automatically
        $user = $this->repository->findByEmail(Email::fromString('ADMIN@LOL.LOCAL'));

        // Then
        $this->assertNotNull($user);
        $this->assertSame('admin@lol.local', $user->email()->value());
    }

    public function testSavedUserReturnsDomainModel(): void
    {
        // Given
        $user = AdminUser::create(
            AdminUserId::generate(),
            Email::fromString('domain@lol.local'),
            'hashed'
        );
        $this->repository->save($user);

        // When
        $found = $this->repository->findByEmail(Email::fromString('domain@lol.local'));

        // Then
        $this->assertInstanceOf(AdminUser::class, $found);
    }
}
