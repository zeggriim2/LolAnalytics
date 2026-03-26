<?php

declare(strict_types=1);

namespace App\Summoner\Infrastructure\Adapter;

use App\SharedContext\Domain\ValueObjet\Platform;
use App\Summoner\Application\Port\RiotLeagueProviderInterface;
use Zeggriim\RiotApiDataDragon\DataLeague\Endpoint\LeagueApiInterface;
use Zeggriim\RiotApiDataDragon\Enum\Platform as RiotPlatform;
use Zeggriim\RiotApiDataDragon\Enum\Queue;

final readonly class RiotLeagueProvider implements RiotLeagueProviderInterface
{
    public function __construct(private LeagueApiInterface $leagueApi)
    {
    }

    public function getChallengerPuuids(Platform $platform, Queue $queue): array
    {
        $riotPlatform = RiotPlatform::from($platform->value);

        $data = $this->leagueApi->getChallenger($riotPlatform, $queue);

        return array_values(array_filter(
            array_column($data['entries'] ?? [], 'puuid'),
            static fn (mixed $puuid): bool => is_string($puuid) && '' !== $puuid,
        ));
    }
}
