<?php

declare(strict_types=1);

namespace App\Summoner\Infrastructure\Adapter;

use App\SharedContext\Domain\ValueObjet\Platform;
use App\Summoner\Application\Port\RiotLeagueProviderInterface;
use App\Summoner\Domain\Enum\TopLeagueTier;
use Zeggriim\RiotApiDataDragon\DataLeague\Endpoint\LeagueApiInterface;
use Zeggriim\RiotApiDataDragon\Enum\Platform as RiotPlatform;
use Zeggriim\RiotApiDataDragon\Enum\Queue;

final readonly class RiotLeagueProvider implements RiotLeagueProviderInterface
{
    public function __construct(private LeagueApiInterface $leagueApi)
    {
    }

    public function getTopLeaguePuuids(Platform $platform, Queue $queue, TopLeagueTier $tier): array
    {
        $riotPlatform = RiotPlatform::from($platform->value);

        $data = match ($tier) {
            TopLeagueTier::CHALLENGER => $this->leagueApi->getChallenger($riotPlatform, $queue),
            TopLeagueTier::GRANDMASTER => $this->leagueApi->getGrandMaster($riotPlatform, $queue),
            TopLeagueTier::MASTER => $this->leagueApi->getMaster($riotPlatform, $queue),
        };

        return array_values(array_filter(
            array_column($data['entries'] ?? [], 'puuid'),
            static fn (mixed $puuid): bool => is_string($puuid) && '' !== $puuid,
        ));
    }
}
