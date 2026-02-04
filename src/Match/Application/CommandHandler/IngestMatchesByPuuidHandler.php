<?php

declare(strict_types=1);

namespace App\Match\Application\CommandHandler;

use App\Match\Application\Command\IngestMatchCommand;
use App\Match\Application\Command\IngestMatchesByPuuidCommand;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;
use Zeggriim\RiotApiDataDragon\DataLeague\Endpoint\MatchApiInterface;
use Zeggriim\RiotApiDataDragon\DataLeague\Filter\MatchFilter;
use Zeggriim\RiotApiDataDragon\Enum\MatchType;
use Zeggriim\RiotApiDataDragon\Enum\Region as RiotRegion;

#[AsMessageHandler('command.bus')]
final class IngestMatchesByPuuidHandler
{
    public function __construct(
        private readonly MatchApiInterface $matchApi,
        private readonly MessageBusInterface $commandBus,
    ) {
    }

    public function __invoke(IngestMatchesByPuuidCommand $command): void
    {
        $riotRegion = RiotRegion::from($command->region->value);
        $filter = $this->buildMatchFilter($command);

        $matchIds = $this->matchApi->getMatches($command->puuid, $riotRegion, $filter);

        foreach ($matchIds as $matchId) {
            $this->commandBus->dispatch(new IngestMatchCommand($matchId, $command->region));
        }
    }

    private function buildMatchFilter(IngestMatchesByPuuidCommand $command): MatchFilter
    {
        $filter = new MatchFilter();
        $filter->setCount($command->count);
        $filter->setStart($command->start);

        if (null !== $command->startTime) {
            $filter->setStartTime($command->startTime);
        }

        if (null !== $command->endTime) {
            $filter->setEndTime($command->endTime);
        }

        if (null !== $command->queue) {
            $filter->setQueueId($command->queue);
        }

        if (null !== $command->type) {
            $filter->setType(MatchType::from($command->type));
        }

        return $filter;
    }
}
