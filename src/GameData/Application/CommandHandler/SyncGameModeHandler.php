<?php

declare(strict_types=1);

namespace App\GameData\Application\CommandHandler;

use App\GameData\Application\Command\SyncGameModeCommand;
use App\GameData\Application\Port\RiotStaticDataProviderInterface;
use App\GameData\Domain\Model\GameMode;
use App\GameData\Domain\Repository\GameModeRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler('command.bus')]
final class SyncGameModeHandler
{
    public function __construct(
        private readonly RiotStaticDataProviderInterface $dataProvider,
        private readonly GameModeRepositoryInterface $gameModeRepository,
    ) {
    }

    public function __invoke(SyncGameModeCommand $command): void
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
}
