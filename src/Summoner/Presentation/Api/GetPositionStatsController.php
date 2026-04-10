<?php

declare(strict_types=1);

namespace App\Summoner\Presentation\Api;

use App\SharedContext\Application\Bus\QueryBusInterface;
use App\Summoner\Application\Query\GetPositionStatsQuery;
use App\Summoner\Domain\ValueObject\Puuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/{puuid}/position-stats', name: 'api_summoners_position_stats', methods: [Request::METHOD_GET])]
final class GetPositionStatsController extends AbstractController
{
    public function __construct(private readonly QueryBusInterface $queryBus)
    {
    }

    public function __invoke(string $puuid): JsonResponse
    {
        $positions = $this->queryBus->handle(
            new GetPositionStatsQuery(Puuid::fromString($puuid))
        );

        return $this->json(['data' => $positions]);
    }
}
