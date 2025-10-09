<?php

declare(strict_types=1);

namespace App\Match\Application\CommandHandler;

use App\Match\Application\Command\IngestMatchCommand;
use App\Match\Application\Command\IngestMatchesByPuuidCommand;
use App\Match\Infrastructure\Client\Enum\Type;
use App\Match\Infrastructure\Client\RiotApiClientInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler('command.bus')]
final class IngestMatchesByPuuidHandler
{
    public function __construct(
        private readonly RiotApiClientInterface $riotClient,
        private readonly MessageBusInterface $commandBus,
    ) {
    }

    public function __invoke(IngestMatchesByPuuidCommand $command): void
    {
        $matchIds = $this->riotClient->fetchMatchesByPuuid($command->puuid, $command->region, Type::RANKED);

        foreach ($matchIds as $matchId) {
            $this->commandBus->dispatch(new IngestMatchCommand($matchId, $command->region));
        }
    }
}
