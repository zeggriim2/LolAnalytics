<?php

declare(strict_types=1);

namespace App\Tests\Factory;

use App\Auth\Domain\Model\AdminUser;
use App\Auth\Infrastructure\Persistence\Doctrine\Entity\AdminUserEntity;
use Zenstruck\Foundry\Object\Instantiator;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<AdminUserEntity>
 */
final class AdminUserEntityFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return AdminUserEntity::class;
    }

    protected function initialize(): static
    {
        return $this->instantiateWith(Instantiator::withoutConstructor()->alwaysForce());
    }

    protected function defaults(): array
    {
        return [
            'id' => self::faker()->uuid(),
            'email' => self::faker()->unique()->safeEmail(),
            'password' => '$2y$13$' . self::faker()->regexify('[A-Za-z0-9./]{53}'),
            'createdAt' => \DateTimeImmutable::createFromMutable(
                self::faker()->dateTimeBetween('-1 year', 'now')
            ),
        ];
    }

    public function withEmail(string $email): self
    {
        return $this->with(['email' => strtolower(trim($email))]);
    }

    public function withPassword(string $hashedPassword): self
    {
        return $this->with(['password' => $hashedPassword]);
    }

    public static function createFromDomain(AdminUser $user): self
    {
        return self::new([
            'id' => $user->id()->value(),
            'email' => $user->email()->value(),
            'password' => $user->hashedPassword(),
            'createdAt' => $user->createdAt(),
        ]);
    }
}
