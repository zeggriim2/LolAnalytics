<?php

declare(strict_types=1);

namespace App\Auth\Application\CommandHandler;

use App\Auth\Application\Command\CreateAdminUserCommand;
use App\Auth\Domain\Model\AdminUser;
use App\Auth\Domain\Repository\AdminUserRepositoryInterface;
use App\Auth\Domain\ValueObject\AdminUserId;
use App\Auth\Domain\ValueObject\Email;
use App\Auth\Infrastructure\Persistence\Doctrine\Entity\AdminUserEntity;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsMessageHandler(bus: 'command.bus')]
final class CreateAdminUserHandler
{
    public function __construct(
        private readonly AdminUserRepositoryInterface $repository,
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function __invoke(CreateAdminUserCommand $command): void
    {
        $email = Email::fromString($command->email);
        $id = AdminUserId::generate();

        $hashedPassword = $this->passwordHasher->hashPassword(
            AdminUserEntity::createForPasswordHashing(),
            $command->plainPassword,
        );

        $user = AdminUser::create($id, $email, $hashedPassword);

        $this->repository->save($user);
    }
}
