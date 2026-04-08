<?php

declare(strict_types=1);

namespace App\Summoner\Presentation\Api;

use App\SharedContext\Application\Bus\QueryBusInterface;
use App\Summoner\Application\Dto\SummonerStatsDto;
use App\Summoner\Application\Query\GetSummonerStatsQuery;
use App\Summoner\Domain\ValueObject\Puuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/{puuid}/stats', name: 'api_summoners_stats', methods: [Request::METHOD_GET])]
final class GetSummonerStatsController extends AbstractController
{
    public function __construct(private readonly QueryBusInterface $queryBus)
    {
    }

    public function __invoke(string $puuid): JsonResponse
    {
        /** @var SummonerStatsDto $stats */
        $stats = $this->queryBus->handle(
            new GetSummonerStatsQuery(Puuid::fromString($puuid))
        );

        return $this->json(['data' => $stats]);
    }
}
