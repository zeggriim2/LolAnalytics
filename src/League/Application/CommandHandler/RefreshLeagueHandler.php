<?php

declare(strict_types=1);

namespace App\League\Application\CommandHandler;

use App\League\Application\Command\RefreshLeagueCommand;
use App\League\Application\Port\RiotLeagueProviderInterface;
use App\League\Domain\Model\League;
use App\League\Domain\Model\LeagueEntry;
use App\League\Domain\Repository\LeagueRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'command.bus')]
final readonly class RefreshLeagueHandler
{
    public function __construct(
        private RiotLeagueProviderInterface $leagueProvider,
        private LeagueRepositoryInterface $leagueRepository,
    ) {
    }

    public function __invoke(RefreshLeagueCommand $command): void
    {
        $dtos = $this->leagueProvider->getLeagueEntries($command->platform, $command->queue, $command->tier);

        $entries = array_map(
            static fn ($dto): LeagueEntry => LeagueEntry::create(
                $dto->puuid,
                $dto->summonerId,
                $dto->leaguePoints,
                $dto->wins,
                $dto->losses,
                $dto->rank,
                $dto->hotStreak,
                $dto->veteran,
                $dto->freshBlood,
            ),
            $dtos,
        );

        $totalLp = array_sum(array_map(static fn (LeagueEntry $e): int => $e->leaguePoints(), $entries));

        $league = League::create(
            $command->tier,
            $command->queue,
            $command->platform,
            $totalLp,
            $entries,
            new \DateTimeImmutable(),
        );

        $this->leagueRepository->save($league);
    }
}
