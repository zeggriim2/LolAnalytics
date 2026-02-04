<?php

declare(strict_types=1);

namespace App\GameData\Application\CommandHandler;

use App\GameData\Application\Command\SyncGameDataCommand;
use App\GameData\Application\Port\RiotStaticDataProviderInterface;
use App\GameData\Domain\Model\GameMode;
use App\GameData\Domain\Model\GameType;
use App\GameData\Domain\Model\Map;
use App\GameData\Domain\Model\Queue;
use App\GameData\Domain\Model\Season;
use App\GameData\Domain\Repository\GameModeRepositoryInterface;
use App\GameData\Domain\Repository\GameTypeRepositoryInterface;
use App\GameData\Domain\Repository\MapRepositoryInterface;
use App\GameData\Domain\Repository\QueueRepositoryInterface;
use App\GameData\Domain\Repository\SeasonRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler('command.bus')]
final class SyncGameDataHandler
{
    public function __construct(
        private readonly RiotStaticDataProviderInterface $dataProvider,
        private readonly QueueRepositoryInterface $queueRepository,
        private readonly GameModeRepositoryInterface $gameModeRepository,
        private readonly GameTypeRepositoryInterface $gameTypeRepository,
        private readonly MapRepositoryInterface $mapRepository,
        private readonly SeasonRepositoryInterface $seasonRepository,
    ) {
    }

    public function __invoke(SyncGameDataCommand $command): void
    {
        $this->syncQueues();
        $this->syncGameModes();
        $this->syncGameTypes();
        $this->syncMaps();
        $this->syncSeasons();
    }

    private function syncQueues(): void
    {
        $dtos = $this->dataProvider->fetchQueues();

        foreach ($dtos as $dto) {
            $queue = new Queue(
                $dto->queueId,
                $dto->map,
                $dto->description,
                $dto->notes,
            );
            $this->queueRepository->save($queue);
        }
    }

    private function syncGameModes(): void
    {
        $dtos = $this->dataProvider->fetchGameModes();

        foreach ($dtos as $dto) {
            $gameMode = new GameMode(
                $dto->gameMode,
                $dto->description,
            );
            $this->gameModeRepository->save($gameMode);
        }
    }

    private function syncGameTypes(): void
    {
        $dtos = $this->dataProvider->fetchGameTypes();

        foreach ($dtos as $dto) {
            $gameType = new GameType(
                $dto->gameType,
                $dto->description,
            );
            $this->gameTypeRepository->save($gameType);
        }
    }

    private function syncMaps(): void
    {
        $dtos = $this->dataProvider->fetchMaps();

        foreach ($dtos as $dto) {
            $map = new Map(
                $dto->mapId,
                $dto->mapName,
                $dto->notes,
            );
            $this->mapRepository->save($map);
        }
    }

    private function syncSeasons(): void
    {
        $dtos = $this->dataProvider->fetchSeasons();

        foreach ($dtos as $dto) {
            $season = new Season(
                $dto->id,
                $dto->season,
            );
            $this->seasonRepository->save($season);
        }
    }
}
