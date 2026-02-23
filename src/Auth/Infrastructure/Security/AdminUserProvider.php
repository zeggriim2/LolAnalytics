<?php

declare(strict_types=1);

namespace App\Auth\Infrastructure\Security;

use App\Auth\Domain\ValueObject\Email;
use App\Auth\Infrastructure\Persistence\Doctrine\Entity\AdminUserEntity;
use App\Auth\Infrastructure\Persistence\Doctrine\Repository\DoctrineAdminUserRepository;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * @implements UserProviderInterface<AdminUserEntity>
 */
final class AdminUserProvider implements UserProviderInterface
{
    public function __construct(
        private readonly DoctrineAdminUserRepository $repository,
    ) {
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $user = $this->repository->findByEmail(Email::fromString($identifier));

        if (null === $user) {
            throw new UserNotFoundException(sprintf('User "%s" not found.', $identifier));
        }

        return AdminUserEntity::fromDomain($user);
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        return $this->loadUserByIdentifier($user->getUserIdentifier());
    }

    public function supportsClass(string $class): bool
    {
        return AdminUserEntity::class === $class;
    }
}
