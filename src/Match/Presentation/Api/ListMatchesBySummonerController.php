<?php

declare(strict_types=1);

namespace App\Match\Presentation\Api;

use App\Match\Application\Query\ListMatchesBySummonerQuery;
use App\Match\Application\ReadModel\MatchDetailReadModel;
use App\SharedContext\Application\Bus\QueryBusInterface;
use App\SharedContext\Domain\Pagination\PaginatedResult;
use App\SharedContext\Domain\Pagination\PaginationRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/summoner/{puuid}', name: 'api_matches_by_summoner', methods: [Request::METHOD_GET])]
final class ListMatchesBySummonerController extends AbstractController
{
    public function __construct(
        private readonly QueryBusInterface $queryBus
    ) {
    }

    public function __invoke(string $puuid, Request $request): JsonResponse
    {
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 10);

        /** @var PaginatedResult<MatchDetailReadModel> $result */
        $result = $this->queryBus->handle(
            new ListMatchesBySummonerQuery($puuid, new PaginationRequest($page, $limit))
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
}
