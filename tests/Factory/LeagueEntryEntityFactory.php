<?php

declare(strict_types=1);

namespace App\Tests\Factory;

use App\League\Infrastructure\Persistence\Doctrine\Entity\LeagueEntryEntity;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<LeagueEntryEntity>
 */
final class LeagueEntryEntityFactory extends PersistentProxyObjectFactory
{
    private static int $counter = 0;

    public static function class(): string
    {
        return LeagueEntryEntity::class;
    }

    protected function defaults(): array
    {
        ++self::$counter;

        return [
            'puuid' => self::faker()->uuid() . '-' . self::$counter,
            'leaguePoints' => self::faker()->numberBetween(0, 2000),
            'wins' => self::faker()->numberBetween(50, 300),
            'losses' => self::faker()->numberBetween(30, 200),
            'rank' => null,
            'hotStreak' => self::faker()->boolean(20),
            'veteran' => self::faker()->boolean(30),
            'freshBlood' => self::faker()->boolean(10),
            'league' => LeagueEntityFactory::new(),
        ];
    }

    public static function resetCounter(): void
    {
        self::$counter = 0;
    }

    public function withPuuid(string $puuid): self
    {
        return $this->with(['puuid' => $puuid]);
    }

    public function withLeaguePoints(int $lp): self
    {
        return $this->with(['leaguePoints' => $lp]);
    }

    public function forLeague(object $league): self
    {
        return $this->with(['league' => $league]);
    }

    public function hotStreak(): self
    {
        return $this->with(['hotStreak' => true]);
    }
}
