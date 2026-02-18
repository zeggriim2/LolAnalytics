<?php

declare(strict_types=1);

namespace App\Summoner\Application\QueryHandler;

use App\SharedContext\Domain\Pagination\PaginatedResult;
use App\Summoner\Application\Dto\SummonerDto;
use App\Summoner\Application\Query\ListSummonersQuery;
use App\Summoner\Domain\Repository\SummonerRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'query.bus')]
final readonly class ListSummonersHandler
{
    public function __construct(
        private SummonerRepositoryInterface $summonerRepository,
    ) {
    }

    /**
     * @return PaginatedResult<SummonerDto>
     */
    public function __invoke(ListSummonersQuery $query): PaginatedResult
    {
        $pagination = $query->pagination;

        $summoners = $this->summonerRepository->findPaginated($pagination->offset(), $pagination->limit);
        $total = $this->summonerRepository->count();

        return new PaginatedResult(
            items: array_map(SummonerDto::fromDomain(...), $summoners),
            total: $total,
            page: $pagination->page,
            limit: $pagination->limit,
        );
    }
}
