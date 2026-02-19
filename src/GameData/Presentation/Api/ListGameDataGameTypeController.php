<?php

declare(strict_types=1);

namespace App\GameData\Presentation\Api;

use App\GameData\Application\Query\ListGameTypesQuery;
use App\GameData\Application\ReadModel\GameTypeReadModel;
use App\SharedContext\Application\Bus\QueryBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/game-types', name: 'api_game_data_game_types', methods: [Request::METHOD_GET])]
final class ListGameDataGameTypeController extends AbstractController
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
    ) {
    }

    public function __invoke(): JsonResponse
    {
        /** @var GameTypeReadModel[] $data */
        $data = $this->queryBus->handle(new ListGameTypesQuery());

        return $this->json([
            'data' => $data,
            'total' => count($data),
        ]);
    }
}
