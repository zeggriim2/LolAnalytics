<?php

declare(strict_types=1);

namespace App\Summoner\Application\CommandHandler;

use App\SharedContext\Application\Bus\CommandBusInterface;
use App\Summoner\Application\Command\ImportSummonerCommand;
use App\Summoner\Application\Command\ImportTopLeagueSummonersCommand;
use App\Summoner\Application\Port\RiotLeagueProviderInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'command.bus')]
final readonly class ImportTopLeagueSummonersHandler
{
    public function __construct(
        private RiotLeagueProviderInterface $leagueProvider,
        private CommandBusInterface $commandBus,
    ) {
    }

    public function __invoke(ImportTopLeagueSummonersCommand $command): void
    {
        $puuids = $this->leagueProvider->getTopLeaguePuuids(
            $command->platform,
            $command->queue,
            $command->tier,
        );

        foreach ($puuids as $puuid) {
            $this->commandBus->dispatch(
                new ImportSummonerCommand($puuid, $command->platform, $command->force),
            );
        }
    }
}
