<?php

declare(strict_types=1);

namespace App\Match\Presentation\Api;

use App\Match\Application\Query\ListMatchesQuery;
use App\Match\Application\ReadModel\MatchReadModel;
use App\SharedContext\Application\Bus\QueryBusInterface;
use App\SharedContext\Domain\Pagination\PaginatedResult;
use App\SharedContext\Domain\Pagination\PaginationRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('', name: 'api_matches_list', methods: [Request::METHOD_GET])]
final class ListMatchesController extends AbstractController
{
    public function __construct(
        private readonly QueryBusInterface $queryBus
    ) {
    }

    public function __invoke(Request $request): JsonResponse
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
}
