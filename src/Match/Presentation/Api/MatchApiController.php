<?php

declare(strict_types=1);

namespace App\Match\Presentation\Api;

use App\Match\Application\Query\GetMatchByIdQuery;
use App\Match\Application\Query\ListMatchesBySummonerQuery;
use App\Match\Application\Query\ListMatchesQuery;
use App\Match\Application\ReadModel\MatchDetailReadModel;
use App\Match\Application\ReadModel\MatchReadModel;
use App\SharedContext\Application\Bus\QueryBusInterface;
use App\SharedContext\Domain\Pagination\PaginatedResult;
use App\SharedContext\Domain\Pagination\PaginationRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/matches', name: 'api_matches_')]
final class MatchApiController extends AbstractController
{
    public function __construct(
        private readonly QueryBusInterface $queryBus
    ) {
    }

    #[Route('', name: 'list', methods: [Request::METHOD_GET])]
    public function list(Request $request): JsonResponse
    {
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 20);

        /** @var PaginatedResult<MatchReadModel> $result */
        $result = $this->queryBus->handle(
            new ListMatchesQuery(new PaginationRequest($page, $limit))
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

    #[Route('/summoner/{puuid}', name: 'by_summoner', methods: [Request::METHOD_GET])]
    public function bySummoner(string $puuid, Request $request): JsonResponse
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

    #[Route('/{matchId}', name: 'show', methods: [Request::METHOD_GET])]
    public function show(string $matchId): JsonResponse
    {
        /** @var ?MatchDetailReadModel $match */
        $match = $this->queryBus->handle(new GetMatchByIdQuery($matchId));

        if (null === $match) {
            return $this->json(['error' => 'Match not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json([
            'data' => $match,
        ]);
    }
}
