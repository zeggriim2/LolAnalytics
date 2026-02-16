<?php

declare(strict_types=1);

namespace App\Summoner\Presentation\Api;

use App\SharedContext\Application\Bus\CommandBusInterface;
use App\SharedContext\Domain\ValueObjet\Platform;
use App\Summoner\Application\Command\ImportSummonerByRiotIdCommand;
use App\Summoner\Application\Command\ImportSummonerCommand;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/admin/summoners', name: 'api_admin_summoners_')]
final class SummonerSyncApiController extends AbstractController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
    ) {
    }

    #[Route('/import/riot-id', name: 'import_riot_id', methods: [Request::METHOD_POST])]
    public function importByRiotId(Request $request): JsonResponse
    {
        /** @var array{gameName?: string, tagLine?: string, platform?: string} $data */
        $data = json_decode($request->getContent(), true);

        $gameName = $data['gameName'] ?? '';
        $tagLine = $data['tagLine'] ?? '';
        $platformValue = $data['platform'] ?? '';

        if ('' === $gameName || '' === $tagLine || '' === $platformValue) {
            return $this->json([
                'status' => 'error',
                'message' => 'Fields "gameName", "tagLine" and "platform" are required.',
            ], Response::HTTP_BAD_REQUEST);
        }

        $platform = Platform::tryFrom($platformValue);

        if (null === $platform) {
            return $this->json([
                'status' => 'error',
                'message' => sprintf('Invalid platform: %s', $platformValue),
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $this->commandBus->dispatch(new ImportSummonerByRiotIdCommand($gameName, $tagLine, $platform));

            return $this->json([
                'status' => 'success',
                'message' => sprintf('Summoner "%s#%s" imported successfully.', $gameName, $tagLine),
            ]);
        } catch (\Throwable $e) {
            return $this->json([
                'status' => 'error',
                'message' => sprintf('Import failed: %s', $e->getMessage()),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/import/puuid', name: 'import_puuid', methods: [Request::METHOD_POST])]
    public function importByPuuid(Request $request): JsonResponse
    {
        /** @var array{puuid?: string, platform?: string} $data */
        $data = json_decode($request->getContent(), true);

        $puuid = $data['puuid'] ?? '';
        $platformValue = $data['platform'] ?? '';

        if ('' === $puuid || '' === $platformValue) {
            return $this->json([
                'status' => 'error',
                'message' => 'Fields "puuid" and "platform" are required.',
            ], Response::HTTP_BAD_REQUEST);
        }

        $platform = Platform::tryFrom($platformValue);

        if (null === $platform) {
            return $this->json([
                'status' => 'error',
                'message' => sprintf('Invalid platform: %s', $platformValue),
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $this->commandBus->dispatch(new ImportSummonerCommand($puuid, $platform));

            return $this->json([
                'status' => 'success',
                'message' => 'Summoner imported successfully by PUUID.',
            ]);
        } catch (\Throwable $e) {
            return $this->json([
                'status' => 'error',
                'message' => sprintf('Import failed: %s', $e->getMessage()),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
