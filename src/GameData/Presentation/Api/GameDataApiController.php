<?php

declare(strict_types=1);

namespace App\GameData\Presentation\Api;

use App\GameData\Application\Query\ListGameModesQuery;
use App\GameData\Application\Query\ListGameTypesQuery;
use App\GameData\Application\Query\ListMapsQuery;
use App\GameData\Application\Query\ListQueuesQuery;
use App\GameData\Application\Query\ListVersionsQuery;
use App\GameData\Application\ReadModel\GameModeReadModel;
use App\GameData\Application\ReadModel\GameTypeReadModel;
use App\GameData\Application\ReadModel\MapReadModel;
use App\GameData\Application\ReadModel\QueueReadModel;
use App\SharedContext\Application\Bus\QueryBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/game-data', name: 'api_game_data_')]
final class GameDataApiController extends AbstractController
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
    ) {
    }

    #[Route('/queues', name: 'queues', methods: [Request::METHOD_GET])]
    public function queues(): JsonResponse
    {
        /** @var QueueReadModel[] $data */
        $data = $this->queryBus->handle(new ListQueuesQuery());

        return $this->json([
            'data' => $data,
            'total' => count($data),
        ]);
    }

    #[Route('/maps', name: 'maps', methods: [Request::METHOD_GET])]
    public function maps(): JsonResponse
    {
        /** @var MapReadModel[] $data */
        $data = $this->queryBus->handle(new ListMapsQuery());

        return $this->json([
            'data' => $data,
            'total' => count($data),
        ]);
    }

    #[Route('/game-modes', name: 'game_modes', methods: [Request::METHOD_GET])]
    public function gameModes(): JsonResponse
    {
        /** @var GameModeReadModel[] $data */
        $data = $this->queryBus->handle(new ListGameModesQuery());

        return $this->json([
            'data' => $data,
            'total' => count($data),
        ]);
    }

    #[Route('/game-types', name: 'game_types', methods: [Request::METHOD_GET])]
    public function gameTypes(): JsonResponse
    {
        /** @var GameTypeReadModel[] $data */
        $data = $this->queryBus->handle(new ListGameTypesQuery());

        return $this->json([
            'data' => $data,
            'total' => count($data),
        ]);
    }

    #[Route('/versions', name: 'versions', methods: [Request::METHOD_GET])]
    public function versions(): JsonResponse
    {
        /** @var GameTypeReadModel[] $data */
        $data = $this->queryBus->handle(new ListVersionsQuery());

        return $this->json([
            'data' => $data,
            'total' => count($data),
        ]);
    }
}
