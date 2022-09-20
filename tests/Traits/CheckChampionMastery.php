<?php

namespace App\Tests\Traits;

use App\Services\API\LOL\LeagueOfLegends\DTO\ChampionMastery\ChampionMasteryDto;

trait CheckChampionMastery
{
    private function checkChampionMasteryDto(ChampionMasteryDto $championMasteryDto)
    {
        $this->assertIsString($championMasteryDto->getSummonerId());
        $this->assertIsInt($championMasteryDto->getChampionId());
        $this->assertIsInt($championMasteryDto->getChampionLevel());
        $this->assertIsInt($championMasteryDto->getChampionPoints());
        $this->assertIsInt($championMasteryDto->getChampionPointsSinceLastLevel());
        $this->assertIsInt($championMasteryDto->getChampionPointsUntilNextLevel());
        $this->assertIsInt($championMasteryDto->getLastPlayTime());
        $this->assertIsInt($championMasteryDto->getTokensEarned());
        $this->assertIsBool($championMasteryDto->isChestGranted());
    }
}