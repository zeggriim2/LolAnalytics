<?php

declare(strict_types=1);

namespace App\Tests\Factory;

use App\Match\Infrastructure\Persistence\Doctrine\Entity\MatchEntity;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<MatchEntity>
 */
final class MatchEntityFactory extends PersistentProxyObjectFactory
{
    private static bool $skipDefaultParticipants = false;

    private const int QUIZE_MIN = 900; // 15 minutes
    private const int SOIXANTE_MIN = 3600; // 60 minutes

    public static function class(): string
    {
        return MatchEntity::class;
    }

    protected function defaults(): array
    {
        return [
            'matchId' => 'EUW1_' . self::faker()->numberBetween(1000000000, 9999999999),
            'gameId' => self::faker()->numberBetween(1000000000, 9999999999),
            'region' => self::faker()->randomElement(['EUW1', 'NA1', 'KR', 'BR1', 'JP1']),
            'platform' => self::faker()->randomElement(['euw1', 'na1', 'kr', 'br1', 'jp1']),
            'playedAt' => self::faker()->dateTimeBetween('-1 year', 'now'),
            'durationSeconds' => self::faker()->numberBetween(self::QUIZE_MIN, self::SOIXANTE_MIN),
            'gameMode' => GameModeEntityFactory::createOne(),
            'gameType' => GameTypeEntityFactory::createOne(),
            'map' => MapEntityFactory::createOne(),
            'queue' => QueueEntityFactory::createOne(),
        ];
    }

    protected function initialize(): static
    {
        return $this
            ->afterInstantiate(function (MatchEntity $match): void {
                // Skip if flag is set
                if (self::$skipDefaultParticipants) {
                    self::$skipDefaultParticipants = false; // Reset for next call

                    return;
                }

                // By default, create 10 participants (5 winners, 5 losers)
                if ($match->getParticipants()->isEmpty()) {
                    for ($i = 0; $i < 5; ++$i) {
                        ParticipantEntityFactory::new()
                            ->withMatch($match)
                            ->winner()
                            ->create()
                        ;
                    }

                    for ($i = 0; $i < 5; ++$i) {
                        ParticipantEntityFactory::new()
                            ->withMatch($match)
                            ->loser()
                            ->create()
                        ;
                    }

                }
            })
        ;
    }

    public function withRegion(string $region): self
    {
        return $this->with(['region' => $region]);
    }

    public function withMatchId(string $matchId): self
    {
        return $this->with(['matchId' => $matchId]);
    }

    public function withoutParticipants(): self
    {
        self::$skipDefaultParticipants = true;

        return $this;
    }

    public function withParticipants(int $count = 10): self
    {
        return $this->afterInstantiate(function (MatchEntity $match) use ($count): void {
            if ($match->getParticipants()->isEmpty()) {
                $winners = (int) ceil($count / 2);
                $losers = $count - $winners;

                for ($i = 0; $i < $winners; ++$i) {
                    ParticipantEntityFactory::new()
                        ->withMatch($match)
                        ->winner()
                        ->create()
                    ;
                }

                for ($i = 0; $i < $losers; ++$i) {
                    ParticipantEntityFactory::new()
                        ->withMatch($match)
                        ->loser()
                        ->create()
                    ;
                }
            }
        });
    }
}
