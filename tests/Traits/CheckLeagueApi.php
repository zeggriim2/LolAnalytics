<?php

namespace App\Tests\Traits;

use App\Services\API\LOL\LeagueOfLegends\DTO\League\LeagueEntryDTO;
use App\Services\API\LOL\LeagueOfLegends\DTO\League\LeagueItemDTO;
use App\Services\API\LOL\LeagueOfLegends\DTO\League\LeagueListDTO;
use function PHPUnit\Framework\isNull;

trait CheckLeagueApi
{
    private function checkLeagueListDto(LeagueListDTO $leagueListDTO): void
    {
        $this->assertIsString($leagueListDTO->getName());
        $this->assertIsString($leagueListDTO->getLeagueId());
        $this->assertIsString($leagueListDTO->getQueue());
        $this->assertIsString($leagueListDTO->getTier());

        if ($leagueListDTO->getEntries() > 0) {
            foreach ($leagueListDTO->getEntries() as $entry) {
                $this->assertInstanceOf(LeagueItemDTO::class, $entry);
            }
            $this->checkLeagueItemDto($leagueListDTO->getEntries()[0]);
        }
    }

    private function checkLeagueItemDto(LeagueItemDTO $leagueItemDTO)
    {
        $this->assertIsInt($leagueItemDTO->getLeaguePoints());
        $this->assertIsInt($leagueItemDTO->getLosses());
        $this->assertIsInt($leagueItemDTO->getWins());
        $this->assertIsString($leagueItemDTO->getRank());
        $this->assertIsString($leagueItemDTO->getSummonerId());
        $this->assertIsString($leagueItemDTO->getSummonerName());
        if(isNull($leagueItemDTO->getMiniSeries())){
            $this->assertNull($leagueItemDTO->getMiniSeries());
        }else{
            $this->assertIsArray($leagueItemDTO->getMiniSeries());
        }
        $this->assertIsBool($leagueItemDTO->isFreshBlood());
        $this->assertIsBool($leagueItemDTO->isHotStreak());
        $this->assertIsBool($leagueItemDTO->isInactive());
        $this->assertIsBool($leagueItemDTO->isVeteran());
    }


    private function checkLeagueEntryDto(LeagueEntryDTO $leagueEntryDTO): void
    {
        $this->assertIsString($leagueEntryDTO->getSummonerName());
        $this->assertIsString($leagueEntryDTO->getSummonerId());
        $this->assertIsString($leagueEntryDTO->getRank());
        $this->assertIsString($leagueEntryDTO->getTier());
        $this->assertIsString($leagueEntryDTO->getQueueType());
        $this->assertIsString($leagueEntryDTO->getLeagueId());
        $this->assertIsInt($leagueEntryDTO->getWins());
        $this->assertIsInt($leagueEntryDTO->getLosses());
        $this->assertIsInt($leagueEntryDTO->getLeaguePoints());
        if(isNull($leagueEntryDTO->getMiniSeries())){
            $this->assertNull($leagueEntryDTO->getMiniSeries());
        }else{
            $this->assertIsArray($leagueEntryDTO->getMiniSeries());
        }
        $this->assertIsBool($leagueEntryDTO->isVeteran());
        $this->assertIsBool($leagueEntryDTO->isInactive());
        $this->assertIsBool($leagueEntryDTO->isHotStreak());
        $this->assertIsBool($leagueEntryDTO->isFreshBlood());
    }
}