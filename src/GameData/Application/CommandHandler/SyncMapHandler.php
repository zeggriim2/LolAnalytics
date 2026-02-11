<?php

declare(strict_types=1);

namespace App\GameData\Application\CommandHandler;

use App\GameData\Application\Command\SyncMapCommand;
use App\GameData\Application\Port\RiotStaticDataProviderInterface;
use App\GameData\Domain\Model\Map;
use App\GameData\Domain\Repository\MapRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler('command.bus')]
final class SyncMapHandler
{
    public function __construct(
        private readonly RiotStaticDataProviderInterface $dataProvider,
        private readonly MapRepositoryInterface $mapRepository,
    ) {
    }

    public function __invoke(SyncMapCommand $command): void
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
}
