<?php

namespace App\Command\Traits;

use App\Entity\Invocateur;
use App\Entity\League;
use App\Services\API\LOL\LeagueOfLegends\DTO\League\LeagueEntryDTO;

trait LeagueTrait
{

    private function league(
        Invocateur $invocateur,
        LeagueEntryDTO $leagueEntryDto
    ): void
    {
        $league = (new League())
            ->setLeagueId($leagueEntryDto->getLeagueId())
            ->setLeaguePoints($leagueEntryDto->getLeaguePoints())
            ->setFreshBlood($leagueEntryDto->isFreshBlood())
            ->setInactive($leagueEntryDto->isInactive())
            ->setHotStreak($leagueEntryDto->isHotStreak())
            ->setVeteran($leagueEntryDto->isVeteran())
            ->setWins($leagueEntryDto->getWins())
            ->setLosses($leagueEntryDto->getLosses())
            ->setInvocateur($invocateur)
        ;

        $this->doctrine->persist($league);
    }
}