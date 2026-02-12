<?php

declare(strict_types=1);

namespace App\Tests\Factory;

use App\Match\Infrastructure\Persistence\Doctrine\Entity\MatchEntity;
use App\Match\Infrastructure\Persistence\Doctrine\Entity\ParticipantEntity;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<ParticipantEntity>
 */
final class ParticipantEntityFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return ParticipantEntity::class;
    }

    protected function defaults(): array
    {
        return [
            'puuid' => self::faker()->uuid(),
            'summonerId' => self::faker()->regexify('[a-zA-Z0-9_-]{20,30}'),
            'championId' => self::faker()->numberBetween(1, 200),
            'kills' => self::faker()->numberBetween(0, 30),
            'deaths' => self::faker()->numberBetween(0, 20),
            'assists' => self::faker()->numberBetween(0, 40),
            'win' => self::faker()->boolean(),
        ];
    }

    public function withMatch(MatchEntity $match): self
    {
        return $this->with(['match' => $match]);
    }

    public function winner(): self
    {
        return $this->with(['win' => true]);
    }

    public function loser(): self
    {
        return $this->with(['win' => false]);
    }

    public function withKDA(int $kills, int $deaths, int $assists): self
    {
        return $this->with([
            'kills' => $kills,
            'deaths' => $deaths,
            'assists' => $assists,
        ]);
    }
}
