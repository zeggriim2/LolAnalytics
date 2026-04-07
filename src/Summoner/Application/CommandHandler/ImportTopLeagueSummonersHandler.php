<?php

declare(strict_types=1);

namespace App\Summoner\Application\CommandHandler;

use App\League\Application\Query\GetLeaguePuuidsQuery;
use App\SharedContext\Application\Bus\CommandBusInterface;
use App\SharedContext\Application\Bus\QueryBusInterface;
use App\Summoner\Application\Command\ImportSummonerCommand;
use App\Summoner\Application\Command\ImportTopLeagueSummonersCommand;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'command.bus')]
final readonly class ImportTopLeagueSummonersHandler
{
    public function __construct(
        private QueryBusInterface $queryBus,
        private CommandBusInterface $commandBus,
    ) {
    }

    public function __invoke(ImportTopLeagueSummonersCommand $command): void
    {
        /** @var string[] $puuids */
        $puuids = $this->queryBus->handle(
            new GetLeaguePuuidsQuery($command->platform, $command->tier, $command->queue),
        );

        foreach ($puuids as $puuid) {
            $this->commandBus->dispatch(
                new ImportSummonerCommand($puuid, $command->platform, $command->force),
            );
        }
    }
}
