<?php

namespace App\Tests\Traits;

use App\Services\API\LOL\LeagueOfLegends\DTO\SummonerDTO;

trait CheckSummonerApi
{

    private function checkSummonerDto(SummonerDTO $summonerDTO)
    {
        $this->assertIsString($summonerDTO->getName());
        $this->assertIsString($summonerDTO->getPuuid());
        $this->assertIsString($summonerDTO->getId());
        $this->assertIsString($summonerDTO->getAccountId());
        $this->assertIsInt($summonerDTO->getSummonerLevel());
        $this->assertIsInt($summonerDTO->getProfileIconId());
        $this->assertIsInt($summonerDTO->getRevisionDate());

    }
}