<?php

declare(strict_types=1);

namespace App\Tests\Factory;

use App\Summoner\Infrastructure\Persistence\Doctrine\Entity\SummonerEntity;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<SummonerEntity>
 */
final class SummonerEntityFactory extends PersistentProxyObjectFactory
{
    private static int $counter = 0;

    public static function class(): string
    {
        return SummonerEntity::class;
    }

    protected function defaults(): array
    {
        ++self::$counter;

        return [
            'puuid' => self::faker()->uuid() . '-' . self::$counter,
            'gameName' => self::faker()->userName(),
            'tagLine' => self::faker()->randomElement(['EUW', 'NA1', 'KR', 'BR1']),
            'profileIconId' => self::faker()->numberBetween(1, 5000),
            'summonerLevel' => self::faker()->numberBetween(1, 500),
            'platform' => self::faker()->randomElement(['euw1', 'na1', 'kr', 'br1']),
            'lastUpdatedAt' => \DateTimeImmutable::createFromMutable(
                self::faker()->dateTimeBetween('-1 year', 'now')
            ),
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

    public function withRiotId(string $gameName, string $tagLine): self
    {
        return $this->with([
            'gameName' => $gameName,
            'tagLine' => $tagLine,
        ]);
    }

    public function withPlatform(string $platform): self
    {
        return $this->with(['platform' => $platform]);
    }

    public function withLevel(int $level): self
    {
        return $this->with(['summonerLevel' => $level]);
    }

    public function euw(): self
    {
        return $this->with(['platform' => 'euw1', 'tagLine' => 'EUW']);
    }

    public function na(): self
    {
        return $this->with(['platform' => 'na1', 'tagLine' => 'NA1']);
    }

    public function kr(): self
    {
        return $this->with(['platform' => 'kr', 'tagLine' => 'KR']);
    }
}
