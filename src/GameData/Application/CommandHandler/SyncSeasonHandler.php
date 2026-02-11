<?php

declare(strict_types=1);

namespace App\GameData\Application\CommandHandler;

use App\GameData\Application\Command\SyncSeasonCommand;
use App\GameData\Application\Port\RiotStaticDataProviderInterface;
use App\GameData\Domain\Model\Season;
use App\GameData\Domain\Repository\SeasonRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler('command.bus')]
final class SyncSeasonHandler
{
    public function __construct(
        private readonly RiotStaticDataProviderInterface $dataProvider,
        private readonly SeasonRepositoryInterface $seasonRepository,
    ) {
    }

    public function __invoke(SyncSeasonCommand $command): void
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
