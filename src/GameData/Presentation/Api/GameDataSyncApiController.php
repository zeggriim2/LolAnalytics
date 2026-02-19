<?php

declare(strict_types=1);

namespace App\GameData\Presentation\Api;

use App\GameData\Application\UseCase\SyncAllGameDataUseCase;
use App\GameData\Application\UseCase\SyncGameModeUseCase;
use App\GameData\Application\UseCase\SyncGameTypeUseCase;
use App\GameData\Application\UseCase\SyncMapUseCase;
use App\GameData\Application\UseCase\SyncQueueUseCase;
use App\GameData\Application\UseCase\SyncSeasonUseCase;
use App\GameData\Application\UseCase\SyncVersionUseCase;
use App\GameData\Domain\GameDataType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class GameDataSyncApiController extends AbstractController
{
    public function __construct(
        private readonly SyncAllGameDataUseCase $syncAllGameDataUseCase,
        private readonly SyncVersionUseCase $syncVersionUseCase,
        private readonly SyncQueueUseCase $syncQueueUseCase,
        private readonly SyncMapUseCase $syncMapUseCase,
        private readonly SyncGameModeUseCase $syncGameModeUseCase,
        private readonly SyncGameTypeUseCase $syncGameTypeUseCase,
        private readonly SyncSeasonUseCase $syncSeasonUseCase,
    ) {
    }

    #[Route('/sync', name: 'sync_all', methods: [Request::METHOD_POST])]
    public function syncAll(): JsonResponse
    {
        try {
            $this->syncAllGameDataUseCase->execute();

            return $this->json([
                'status' => 'success',
                'message' => 'All game data synchronized successfully.',
            ]);
        } catch (\Throwable $e) {
            return $this->json([
                'status' => 'error',
                'message' => sprintf('Synchronization failed: %s', $e->getMessage()),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/sync/{type}', name: 'sync_type', methods: [Request::METHOD_POST])]
    public function syncByType(string $type): JsonResponse
    {
        $gameDataType = GameDataType::tryFrom($type);

        if (null === $gameDataType) {
            return $this->json([
                'status' => 'error',
                'message' => sprintf('Unknown game data type: %s', $type),
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $this->executeSync($gameDataType);

            return $this->json([
                'status' => 'success',
                'message' => sprintf('Game data "%s" synchronized successfully.', $gameDataType->value),
            ]);
        } catch (\Throwable $e) {
            return $this->json([
                'status' => 'error',
                'message' => sprintf('Synchronization of "%s" failed: %s', $gameDataType->value, $e->getMessage()),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function executeSync(GameDataType $type): void
    {
        match ($type) {
            GameDataType::Versions => $this->syncVersionUseCase->execute(),
            GameDataType::Queues => $this->syncQueueUseCase->execute(),
            GameDataType::Maps => $this->syncMapUseCase->execute(),
            GameDataType::GameModes => $this->syncGameModeUseCase->execute(),
            GameDataType::GameTypes => $this->syncGameTypeUseCase->execute(),
            GameDataType::Seasons => $this->syncSeasonUseCase->execute(),
        };
    }
}
