<?php

declare(strict_types=1);

namespace App\Match\Presentation\Api;

use App\Match\Application\Command\IngestMatchesByPuuidCommand;
use App\Match\Application\UseCase\IngestMatchesUseCase;
use App\Match\Application\UseCase\IngestMatchUseCase;
use App\SharedContext\Application\Bus\CommandBusInterface;
use App\Summoner\Domain\Repository\SummonerRepositoryInterface;
use App\Summoner\Domain\ValueObject\Puuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/summoner/{puuid}/sync', name: 'api_matches_sync_by_summoner', methods: [Request::METHOD_POST])]
final class SyncMatchesBySummonerController
{
    public function __construct(
        private readonly IngestMatchesUseCase $ingestMatchesUseCase,
        private readonly SummonerRepositoryInterface $summonerRepository,
    ) {
    }

    public function __invoke(string $puuid, Request $request): JsonResponse
    {
        $summoner = $this->summonerRepository->findByPuuid(Puuid::fromString($puuid));

        if (null === $summoner) {
            return new JsonResponse(['error' => 'Summoner not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);
        $count = isset($data['count']) && is_int($data['count']) ? $data['count'] : 20;

        $region = $summoner->platform()->toRegion();
        $this->ingestMatchesUseCase->execute($puuid, $region, $count);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
