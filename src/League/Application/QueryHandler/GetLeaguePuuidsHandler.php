<?php

declare(strict_types=1);

namespace App\League\Application\QueryHandler;

use App\League\Application\Query\GetLeaguePuuidsQuery;
use App\League\Domain\Repository\LeagueRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'query.bus')]
final readonly class GetLeaguePuuidsHandler
{
    public function __construct(
        private LeagueRepositoryInterface $leagueRepository,
    ) {
    }

    /**
     * @return string[]
     */
    public function __invoke(GetLeaguePuuidsQuery $query): array
    {
        $league = $this->leagueRepository->findByTierQueuePlatform(
            $query->tier,
            $query->queue,
            $query->platform,
        );

        if (null === $league) {
            return [];
        }

        return $league->puuids();
    }
}
