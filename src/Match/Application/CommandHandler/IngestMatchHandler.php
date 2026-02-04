<?php

declare(strict_types=1);

namespace App\Match\Application\CommandHandler;

use App\Match\Application\Command\IngestMatchCommand;
use App\Match\Domain\Event\MatchesSavedNotification;
use App\Match\Domain\Repository\MatchRepositoryInterface;
use App\Match\Domain\ValueObjet\MatchId;
use App\Match\Factory\MatcheFactory;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;
use Zeggriim\RiotApiDataDragon\DataLeague\Endpoint\MatchApiInterface;
use Zeggriim\RiotApiDataDragon\Enum\Region as RiotRegion;

#[AsMessageHandler('command.bus')]
final class IngestMatchHandler
{
    public function __construct(
        private readonly MatchApiInterface $matchApi,
        private readonly MatchRepositoryInterface $matchRepository,
        private readonly MessageBusInterface $eventBus
    ) {
    }

    public function __invoke(IngestMatchCommand $command): void
    {
        $matchId = $command->matchId;

        if ($this->matchRepository->exists(MatchId::fromString($matchId))) {
            return;
        }

        $riotRegion = RiotRegion::from($command->region->value);
        $payload = $this->matchApi->getMatch($matchId, $riotRegion);

        $match = MatcheFactory::fromRiotPayload($payload);

        $this->matchRepository->save($match, $command->region);

        $event = new MatchesSavedNotification([$match->id()], $command->region->value, new \DateTimeImmutable());
        $this->eventBus->dispatch($event);
    }
}
