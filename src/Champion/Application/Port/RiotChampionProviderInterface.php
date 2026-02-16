<?php

declare(strict_types=1);

namespace App\Champion\Application\Port;

use App\Champion\Application\Dto\ChampionDto;

interface RiotChampionProviderInterface
{
    /**
     * @return ChampionDto[]
     */
    public function fetchAllChampions(string $version, string $locale = 'fr_FR'): array;

    public function fetchChampion(string $key, string $version, string $locale = 'fr_FR'): ChampionDto;
}
