<?php

declare(strict_types=1);

namespace App\Summoner\Presentation\Api;

use App\SharedContext\Application\Bus\QueryBusInterface;
use App\Summoner\Application\Dto\SummonerDto;
use App\Summoner\Application\Query\GetSummonerByPuuidQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/{puuid}', name: 'api_summoners_show', methods: [Request::METHOD_GET])]
final class GetSummonerByPuuidController extends AbstractController
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
    ) {
    }

    public function __invoke(string $puuid): JsonResponse
    {
        /** @var ?SummonerDto $summoner */
        $summoner = $this->queryBus->handle(new GetSummonerByPuuidQuery($puuid));

        if (null === $summoner) {
            return $this->json(['error' => 'Summoner not found'], 404);
        }

        return $this->json([
            'data' => $summoner,
        ]);
    }
}
