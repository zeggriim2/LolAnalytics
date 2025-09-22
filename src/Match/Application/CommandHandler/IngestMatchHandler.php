<?php

declare(strict_types=1);

namespace App\Match\Application\CommandHandler;


use App\Match\Application\Command\IngestMatchCommand;
use App\Match\Domain\Event\MatchesSavedNotification;
use App\Match\Domain\Repository\MatchRepositoryInterface;
use App\Match\Domain\ValueObjet\MatchId;
use App\Match\Factory\MatcheFactory;
use App\Match\Infrastructure\Client\RiotApiClientInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler('command.bus')]
final class IngestMatchHandler
{
    public function __construct(
        private readonly RiotApiClientInterface $riotClient,
        private readonly MatchRepositoryInterface $matchRepository,
        private readonly MessageBusInterface $eventBus
    ) {}

    public function __invoke(IngestMatchCommand $command): void
    {
        $matchId = $command->matchId;

        if ($this->matchRepository->exists(MatchId::fromString($matchId))) {
            return;
        }

        $payload = $this->riotClient->fetchMatch($matchId, $command->region);

        $match = MatcheFactory::fromRiotPayload($payload);

        $this->matchRepository->save($match, 'europe');

        $event = new MatchesSavedNotification([$match->id()], $command->region, new \DateTimeImmutable());
        $this->eventBus->dispatch($event);
    }
}
