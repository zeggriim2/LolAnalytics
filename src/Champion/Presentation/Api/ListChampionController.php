<?php

declare(strict_types=1);

namespace App\Champion\Presentation\Api;

use App\Champion\Application\Query\ListChampionsQuery;
use App\Champion\Application\ReadModel\ChampionReadModel;
use App\SharedContext\Application\Bus\QueryBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('', name: 'api_summoners_list', methods: [Request::METHOD_GET])]
final class ListChampionController extends AbstractController
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $version = $request->query->getString('version') ?: null;

        /** @var ChampionReadModel[] $champions */
        $champions = $this->queryBus->handle(new ListChampionsQuery($version));

        return $this->json([
            'data' => $champions,
            'total' => count($champions),
        ]);
    }
}
