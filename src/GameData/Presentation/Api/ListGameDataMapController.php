<?php

declare(strict_types=1);

namespace App\GameData\Presentation\Api;

use App\GameData\Application\Query\ListMapsQuery;
use App\GameData\Application\ReadModel\MapReadModel;
use App\SharedContext\Application\Bus\QueryBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/maps', name: 'api_game_data_map', methods: [Request::METHOD_GET])]
final class ListGameDataMapController extends AbstractController
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
    ) {
    }

    public function __invoke(): JsonResponse
    {
        /** @var MapReadModel[] $data */
        $data = $this->queryBus->handle(new ListMapsQuery());

        return $this->json([
            'data' => $data,
            'total' => count($data),
        ]);
    }
}
