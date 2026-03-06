<?php

declare(strict_types=1);

namespace App\Match\Application\Port;

interface SummonerAdapterInterface
{
    /**
     * @param string[] $puuids
     *
     * @return array<string, string> map of puuid => gameName
     */
    public function findGameNamesByPuuids(array $puuids): array;
}
