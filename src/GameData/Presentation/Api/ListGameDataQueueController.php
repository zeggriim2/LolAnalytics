<?php

declare(strict_types=1);

namespace App\GameData\Presentation\Api;

use App\GameData\Application\Query\ListQueuesQuery;
use App\GameData\Application\ReadModel\QueueReadModel;
use App\SharedContext\Application\Bus\QueryBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/queues', name: 'api_game_data_queue', methods: [Request::METHOD_GET])]
final class ListGameDataQueueController extends AbstractController
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
    ) {
    }

    public function __invoke(): JsonResponse
    {
        /** @var QueueReadModel[] $data */
        $data = $this->queryBus->handle(new ListQueuesQuery());

        return $this->json([
            'data' => $data,
            'total' => count($data),
        ]);
    }
}
