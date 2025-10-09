<?php

namespace App\Match\Infrastructure\Client;

use App\Match\Infrastructure\Client\Enum\Type;

interface RiotApiClientInterface
{
    /**
     * Retourne le payload brut du match depuis Riot (array).
     *
     * @throws \RuntimeException on error
     */
    public function fetchMatch(string $matchId, string $region): array;

    /**
     * @return string[] Liste des matchId (EUW1_xxx)
     */
    public function fetchMatchesByPuuid(string $puuid, string $region, Type $type, int $start, int $count): array;
}
