<?php

declare(strict_types=1);

namespace App\League\Application\QueryHandler;

use App\League\Application\Dto\LeagueEntryListDto;
use App\League\Application\Query\GetLeagueEntriesQuery;
use App\League\Domain\Repository\LeagueRepositoryInterface;
use App\SharedContext\Domain\Pagination\PaginatedResult;
use App\Summoner\Domain\Repository\SummonerRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'query.bus')]
final readonly class GetLeagueEntriesHandler
{
    public function __construct(
        private LeagueRepositoryInterface $leagueRepository,
        private SummonerRepositoryInterface $summonerRepository,
    ) {
    }

    /**
     * @return PaginatedResult<LeagueEntryListDto>
     */
    public function __invoke(GetLeagueEntriesQuery $query): PaginatedResult
    {
        $league = $this->leagueRepository->findByTierQueuePlatform(
            $query->tier,
            $query->queue,
            $query->platform,
        );

        if (null === $league) {
            return new PaginatedResult(items: [], total: 0, page: $query->page, limit: $query->limit);
        }

        $entries = $league->entries();

        usort($entries, static fn ($a, $b): int => $b->leaguePoints() <=> $a->leaguePoints());

        $total = count($entries);
        $offset = ($query->page - 1) * $query->limit;
        $page = array_slice($entries, $offset, $query->limit);

        $puuids = array_map(static fn ($entry): string => $entry->puuid(), $page);
        $summoners = $this->summonerRepository->findByPuuids($puuids);

        $dtos = array_map(
            static fn ($entry, $i): LeagueEntryListDto => LeagueEntryListDto::fromDomain(
                $entry,
                $offset + $i + 1,
                $summoners[$entry->puuid()] ?? null,
            ),
            $page,
            array_keys($page),
        );

        return new PaginatedResult(items: $dtos, total: $total, page: $query->page, limit: $query->limit);
    }
}
