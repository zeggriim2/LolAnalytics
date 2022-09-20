<?php

namespace App\Tests\Traits;

use App\Services\API\LOL\LeagueOfLegends\DTO\Champion\ChampionInfoDTO;

trait CheckChampionApi
{

    private function checkChampionInfoDto(ChampionInfoDTO $championInfoDTO)
    {
        $this->assertIsArray($championInfoDTO->getFreeChampionIds());
        $this->assertIsArray($championInfoDTO->getFreeChampionIdsForNewPlayers());
        $this->assertIsInt($championInfoDTO->getMaxNewPlayerLevel());
    }
}