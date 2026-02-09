<?php

declare(strict_types=1);

namespace App\Summoner\Application\CommandHandler;

use App\SharedContext\Domain\ValueObjet\Platform;
use App\Summoner\Application\Command\ImportSummonerByRiotIdCommand;
use App\Summoner\Application\Port\RiotSummonerProviderInterface;
use App\Summoner\Domain\Model\Summoner;
use App\Summoner\Domain\Repository\SummonerRepositoryInterface;
use App\Summoner\Domain\ValueObject\Puuid;
use App\Summoner\Domain\ValueObject\RiotId;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'command.bus')]
final readonly class ImportSummonerByRiotIdHandler
{
    public function __construct(
        private RiotSummonerProviderInterface $summonerProvider,
        private SummonerRepositoryInterface $summonerRepository,
    ) {
    }

    public function __invoke(ImportSummonerByRiotIdCommand $command): void
    {
        $region = $command->platform->toRegion();

        $dto = $this->summonerProvider->fetchByRiotId(
            $command->gameName,
            $command->tagLine,
            $command->platform,
            $region,
        );

        $puuid = Puuid::fromString($dto->puuid);
        $existingSummoner = $this->summonerRepository->findByPuuid($puuid);

        if (null !== $existingSummoner) {
            $existingSummoner->updateProfile(
                RiotId::create($dto->gameName, $dto->tagLine),
                $dto->profileIconId,
                $dto->summonerLevel,
                $dto->lastUpdatedAt,
            );
            $this->summonerRepository->save($existingSummoner);

            return;
        }

        $summoner = Summoner::create(
            $puuid,
            RiotId::create($dto->gameName, $dto->tagLine),
            $dto->profileIconId,
            $dto->summonerLevel,
            Platform::from($dto->platform),
            $dto->lastUpdatedAt,
        );

        $this->summonerRepository->save($summoner);
    }
}
