<?php

declare(strict_types=1);

namespace App\Auth\Infrastructure\Persistence\Doctrine\Repository;

use App\Auth\Domain\Model\AdminUser;
use App\Auth\Domain\Repository\AdminUserRepositoryInterface;
use App\Auth\Domain\ValueObject\Email;
use App\Auth\Infrastructure\Persistence\Doctrine\Entity\AdminUserEntity;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineAdminUserRepository implements AdminUserRepositoryInterface
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function findByEmail(Email $email): ?AdminUser
    {
        $entity = $this->entityManager
            ->getRepository(AdminUserEntity::class)
            ->findOneBy(['email' => $email->value()]);

        if (null === $entity) {
            return null;
        }

        return $entity->toDomain();
    }

    public function save(AdminUser $user): void
    {
        $entity = AdminUserEntity::fromDomain($user);
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }
}
