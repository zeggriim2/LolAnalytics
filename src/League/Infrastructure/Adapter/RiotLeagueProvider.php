<?php

declare(strict_types=1);

namespace App\League\Infrastructure\Adapter;

use App\League\Application\Dto\LeagueEntryDto;
use App\League\Application\Port\RiotLeagueProviderInterface;
use App\League\Domain\Enum\LeagueTier;
use App\SharedContext\Domain\ValueObjet\Platform;
use Zeggriim\RiotApiDataDragon\DataLeague\Endpoint\LeagueApiInterface;
use Zeggriim\RiotApiDataDragon\Enum\Platform as RiotPlatform;
use Zeggriim\RiotApiDataDragon\Enum\Queue;

final readonly class RiotLeagueProvider implements RiotLeagueProviderInterface
{
    public function __construct(private LeagueApiInterface $leagueApi)
    {
    }

    /**
     * @return LeagueEntryDto[]
     */
    public function getLeagueEntries(Platform $platform, Queue $queue, LeagueTier $tier): array
    {
        $riotPlatform = RiotPlatform::from($platform->value);

        $data = match ($tier) {
            LeagueTier::CHALLENGER => $this->leagueApi->getChallenger($riotPlatform, $queue),
            LeagueTier::GRANDMASTER => $this->leagueApi->getGrandMaster($riotPlatform, $queue),
            LeagueTier::MASTER => $this->leagueApi->getMaster($riotPlatform, $queue),
        };

        $dtos = [];

        foreach ($data['entries'] ?? [] as $entry) {
            $puuid = $entry['puuid'] ?? '';
            $summonerId = $entry['summonerId'] ?? '';

            if ('' === $puuid || '' === $summonerId) {
                continue;
            }

            $dtos[] = new LeagueEntryDto(
                puuid: $puuid,
                summonerId: $summonerId,
                leaguePoints: (int) ($entry['leaguePoints'] ?? 0),
                wins: (int) ($entry['wins'] ?? 0),
                losses: (int) ($entry['losses'] ?? 0),
                rank: isset($entry['rank']) && '' !== $entry['rank'] ? $entry['rank'] : null,
                hotStreak: (bool) ($entry['hotStreak'] ?? false),
                veteran: (bool) ($entry['veteran'] ?? false),
                freshBlood: (bool) ($entry['freshBlood'] ?? false),
            );
        }

        return $dtos;
    }
}
