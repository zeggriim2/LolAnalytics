<?php

declare(strict_types=1);

namespace App\Match\Presentation\Api;

use App\Match\Application\Query\GetMatchByIdQuery;
use App\Match\Application\ReadModel\MatchDetailReadModel;
use App\SharedContext\Application\Bus\QueryBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/{matchId}', name: 'api_matches_show', methods: ['GET'])]
final class GetMatchController extends AbstractController
{
    public function __construct(
        private readonly QueryBusInterface $queryBus
    ) {
    }

    public function __invoke(string $matchId): JsonResponse
    {
        /** @var ?MatchDetailReadModel $match */
        $match = $this->queryBus->handle(new GetMatchByIdQuery($matchId));

        if (null === $match) {
            return $this->json(['error' => 'Match not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json(['data' => $match]);
    }
}
