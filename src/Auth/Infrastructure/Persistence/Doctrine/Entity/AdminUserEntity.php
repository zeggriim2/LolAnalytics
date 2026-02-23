<?php

declare(strict_types=1);

namespace App\Auth\Infrastructure\Persistence\Doctrine\Entity;

use App\Auth\Domain\Model\AdminUser;
use App\Auth\Domain\ValueObject\AdminUserId;
use App\Auth\Domain\ValueObject\Email;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity]
#[ORM\Table(name: 'admin_users')]
class AdminUserEntity implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\Column(type: Types::GUID)]
    private string $id;

    #[ORM\Column(type: Types::STRING, unique: true)]
    private string $email;

    #[ORM\Column(type: Types::STRING)]
    private string $password;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeImmutable $createdAt;

    public function getId(): string
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getRoles(): array
    {
        return ['ROLE_ADMIN'];
    }

    public function getUserIdentifier(): string
    {
        if ('' === $this->email) {
            throw new \LogicException('AdminUserEntity email cannot be empty.');
        }

        return $this->email;
    }

    public function eraseCredentials(): void
    {
    }

    public static function createForPasswordHashing(): self
    {
        return new self();
    }

    public static function fromDomain(AdminUser $user): self
    {
        $entity = new self();
        $entity->id = $user->id()->value();
        $entity->email = $user->email()->value();
        $entity->password = $user->hashedPassword();
        $entity->createdAt = $user->createdAt();

        return $entity;
    }

    public function toDomain(): AdminUser
    {
        return AdminUser::create(
            AdminUserId::fromString($this->id),
            Email::fromString($this->email),
            $this->password,
        );
    }
}
