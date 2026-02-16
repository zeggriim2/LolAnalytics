<?php

declare(strict_types=1);

namespace App\Champion\Presentation\Api;

use App\Champion\Application\Query\GetChampionByRiotIdQuery;
use App\Champion\Application\Query\ListChampionsQuery;
use App\Champion\Application\ReadModel\ChampionDetailReadModel;
use App\Champion\Application\ReadModel\ChampionReadModel;
use App\SharedContext\Application\Bus\QueryBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/champions', name: 'api_champions_')]
final class ChampionApiController extends AbstractController
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
    ) {
    }

    #[Route('', name: 'list', methods: [Request::METHOD_GET])]
    public function list(Request $request): JsonResponse
    {
        $version = $request->query->getString('version') ?: null;

        /** @var ChampionReadModel[] $champions */
        $champions = $this->queryBus->handle(new ListChampionsQuery($version));

        return $this->json([
            'data' => $champions,
            'total' => count($champions),
        ]);
    }

    #[Route('/{riotId}', name: 'show', methods: [Request::METHOD_GET])]
    public function show(string $riotId, Request $request): JsonResponse
    {
        $version = $request->query->getString('version') ?: null;

        /** @var ?ChampionDetailReadModel $champion */
        $champion = $this->queryBus->handle(new GetChampionByRiotIdQuery($riotId, $version));

        if (null === $champion) {
            return $this->json(['error' => 'Champion not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json([
            'data' => $champion,
        ]);
    }
}
