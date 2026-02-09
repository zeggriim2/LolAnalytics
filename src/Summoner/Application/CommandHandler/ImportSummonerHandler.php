<?php

declare(strict_types=1);

namespace App\Summoner\Application\CommandHandler;

use App\SharedContext\Domain\ValueObjet\Platform;
use App\Summoner\Application\Command\ImportSummonerCommand;
use App\Summoner\Application\Port\RiotSummonerProviderInterface;
use App\Summoner\Domain\Model\Summoner;
use App\Summoner\Domain\Repository\SummonerRepositoryInterface;
use App\Summoner\Domain\ValueObject\Puuid;
use App\Summoner\Domain\ValueObject\RiotId;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'command.bus')]
final readonly class ImportSummonerHandler
{
    public function __construct(
        private RiotSummonerProviderInterface $summonerProvider,
        private SummonerRepositoryInterface $summonerRepository,
    ) {
    }

    public function __invoke(ImportSummonerCommand $command): void
    {
        $puuid = Puuid::fromString($command->puuid);
        $region = $command->platform->toRegion();

        $dto = $this->summonerProvider->fetchByPuuid(
            $command->puuid,
            $command->platform,
            $region,
        );

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
