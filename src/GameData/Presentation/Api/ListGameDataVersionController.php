<?php

declare(strict_types=1);

namespace App\GameData\Presentation\Api;

use App\GameData\Application\Query\ListVersionsQuery;
use App\GameData\Application\ReadModel\GameTypeReadModel;
use App\SharedContext\Application\Bus\QueryBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/versions', name: 'api_game_data_versions', methods: [Request::METHOD_GET])]
final class ListGameDataVersionController extends AbstractController
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
    ) {
    }

    public function __invoke(): JsonResponse
    {
        /** @var GameTypeReadModel[] $data */
        $data = $this->queryBus->handle(new ListVersionsQuery());

        return $this->json([
            'data' => $data,
            'total' => count($data),
        ]);
    }
}
