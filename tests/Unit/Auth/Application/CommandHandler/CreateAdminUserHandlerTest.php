<?php

declare(strict_types=1);

namespace App\Tests\Unit\Auth\Application\CommandHandler;

use App\Auth\Application\Command\CreateAdminUserCommand;
use App\Auth\Application\CommandHandler\CreateAdminUserHandler;
use App\Auth\Domain\Model\AdminUser;
use App\Auth\Domain\Repository\AdminUserRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class CreateAdminUserHandlerTest extends TestCase
{
    private AdminUserRepositoryInterface $repository;
    private UserPasswordHasherInterface $passwordHasher;
    private CreateAdminUserHandler $handler;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(AdminUserRepositoryInterface::class);
        $this->passwordHasher = $this->createMock(UserPasswordHasherInterface::class);

        $this->handler = new CreateAdminUserHandler(
            $this->repository,
            $this->passwordHasher,
        );
    }

    public function testCreateAdminUserSuccessfully(): void
    {
        $command = new CreateAdminUserCommand('admin@lol.local', 'plainpassword');

        $this->passwordHasher
            ->expects($this->once())
            ->method('hashPassword')
            ->willReturn('$2y$13$hashedpassword');

        $this->repository
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(function (AdminUser $user) {
                return 'admin@lol.local' === $user->email()->value()
                    && '$2y$13$hashedpassword' === $user->hashedPassword();
            }));

        ($this->handler)($command);
    }

    public function testPasswordIsHashedBeforeSaving(): void
    {
        $command = new CreateAdminUserCommand('admin@lol.local', 'myplainpassword');

        $this->passwordHasher
            ->expects($this->once())
            ->method('hashPassword')
            ->with($this->anything(), 'myplainpassword')
            ->willReturn('hashed_value');

        $this->repository
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(fn (AdminUser $user) => 'hashed_value' === $user->hashedPassword()));

        ($this->handler)($command);
    }

    public function testInvalidEmailThrowsException(): void
    {
        $command = new CreateAdminUserCommand('not-an-email', 'plainpassword');

        $this->passwordHasher
            ->expects($this->never())
            ->method('hashPassword');

        $this->repository
            ->expects($this->never())
            ->method('save');

        $this->expectException(\InvalidArgumentException::class);

        ($this->handler)($command);
    }

    public function testSavedUserHasGeneratedId(): void
    {
        $command = new CreateAdminUserCommand('admin@lol.local', 'plainpassword');

        $this->passwordHasher
            ->method('hashPassword')
            ->willReturn('hashed');

        $this->repository
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(function (AdminUser $user) {
                return '' !== $user->id()->value();
            }));

        ($this->handler)($command);
    }
}
