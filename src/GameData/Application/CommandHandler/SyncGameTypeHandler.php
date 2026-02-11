<?php

declare(strict_types=1);

namespace App\GameData\Application\CommandHandler;

use App\GameData\Application\Command\SyncGameTypeCommand;
use App\GameData\Application\Port\RiotStaticDataProviderInterface;
use App\GameData\Domain\Model\GameType;
use App\GameData\Domain\Repository\GameTypeRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler('command.bus')]
final class SyncGameTypeHandler
{
    public function __construct(
        private readonly RiotStaticDataProviderInterface $dataProvider,
        private readonly GameTypeRepositoryInterface $gameTypeRepository,
    ) {
    }

    public function __invoke(SyncGameTypeCommand $command): void
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
}
