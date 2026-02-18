<?php

declare(strict_types=1);

namespace App\Summoner\Presentation\Api;

use App\SharedContext\Application\Bus\QueryBusInterface;
use App\SharedContext\Domain\Pagination\PaginatedResult;
use App\SharedContext\Domain\Pagination\PaginationRequest;
use App\Summoner\Application\Dto\SummonerDto;
use App\Summoner\Application\Query\GetSummonerByPuuidQuery;
use App\Summoner\Application\Query\ListSummonersQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/summoners', name: 'api_summoners_')]
final class SummonerApiController extends AbstractController
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
    ) {
    }

    #[Route('', name: 'list', methods: [Request::METHOD_GET])]
    public function list(Request $request): JsonResponse
    {
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 20);

        /** @var PaginatedResult<SummonerDto> $result */
        $result = $this->queryBus->handle(
            new ListSummonersQuery(new PaginationRequest($page, $limit))
        );

        return $this->json([
            'data' => $result->items,
            'meta' => [
                'total' => $result->total,
                'page' => $result->page,
                'limit' => $result->limit,
                'totalPages' => $result->totalPages,
            ],
        ]);
    }

    #[Route('/{puuid}', name: 'show', methods: [Request::METHOD_GET])]
    public function show(string $puuid): JsonResponse
    {
        /** @var ?SummonerDto $summoner */
        $summoner = $this->queryBus->handle(new GetSummonerByPuuidQuery($puuid));

        if (null === $summoner) {
            return $this->json(['error' => 'Summoner not found'], 404);
        }

        return $this->json([
            'data' => $summoner,
        ]);
    }
}
