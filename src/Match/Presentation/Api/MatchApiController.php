<?php

declare(strict_types=1);

namespace App\Match\Presentation\Api;

use App\Match\Application\Query\GetMatchByIdQuery;
use App\Match\Application\Query\ListMatchesQuery;
use App\Match\Application\ReadModel\MatchDetailReadModel;
use App\Match\Application\ReadModel\MatchReadModel;
use App\SharedContext\Application\Bus\QueryBusInterface;
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
    public function list(): JsonResponse
    {
        /** @var MatchReadModel[] $matches */
        $matches = $this->queryBus->handle(new ListMatchesQuery());

        return $this->json([
            'data' => $matches,
            'total' => count($matches),
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
