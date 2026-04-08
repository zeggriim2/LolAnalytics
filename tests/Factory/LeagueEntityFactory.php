<?php

declare(strict_types=1);

namespace App\Tests\Factory;

use App\League\Domain\Enum\LeagueTier;
use App\League\Infrastructure\Persistence\Doctrine\Entity\LeagueEntity;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<LeagueEntity>
 */
final class LeagueEntityFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return LeagueEntity::class;
    }

    protected function defaults(): array
    {
        return [
            'tier' => self::faker()->randomElement(array_column(LeagueTier::cases(), 'value')),
            'queue' => 'RANKED_SOLO_5x5',
            'platform' => 'euw1',
            'totalLp' => self::faker()->numberBetween(0, 500000),
            'lastRefreshedAt' => \DateTimeImmutable::createFromMutable(
                self::faker()->dateTimeBetween('-1 day', 'now')
            ),
        ];
    }

    public function challenger(): self
    {
        return $this->with(['tier' => LeagueTier::CHALLENGER->value]);
    }

    public function grandmaster(): self
    {
        return $this->with(['tier' => LeagueTier::GRANDMASTER->value]);
    }

    public function master(): self
    {
        return $this->with(['tier' => LeagueTier::MASTER->value]);
    }

    public function forPlatform(string $platform): self
    {
        return $this->with(['platform' => $platform]);
    }

    public function forQueue(string $queue): self
    {
        return $this->with(['queue' => $queue]);
    }
}
