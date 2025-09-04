<?php

declare(strict_types=1);

namespace App\Infrastructure\Bus;

use App\Application\SharedContext\Bus\CommandBusInterface;
use App\Application\SharedContext\Bus\QueryBusInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final class MessengerQueryBus implements QueryBusInterface
{
    public function __construct(
        private readonly MessageBusInterface $queryBus,
    ) {}

    public function handle(object $query): mixed
    {
        $envelope = $this->queryBus->dispatch($query);

        /** @var HandledStamp $handleStamp */
        $handleStamp = $envelope->last(HandledStamp::class);

        return $handleStamp->getResult();
    }
}
