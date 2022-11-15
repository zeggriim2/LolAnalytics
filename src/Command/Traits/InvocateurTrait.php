<?php

namespace App\Command\Traits;

use App\Entity\Invocateur;
use App\Services\API\LOL\LeagueOfLegends\DTO\SummonerDTO;

trait InvocateurTrait
{
    private function invocateur(SummonerDTO $summonerApi, bool $flush = true): Invocateur
    {
        $invocateur = (new Invocateur())
            ->setName($summonerApi->getName())
            ->setIdLol($summonerApi->getId())
            ->setAccoundId($summonerApi->getAccountId())
            ->setPuuid($summonerApi->getPuuid())
            ->setProfileIconId($summonerApi->getProfileIconId())
            ->setSummonerLevel($summonerApi->getSummonerLevel())
        ;

        $this->doctrine->persist($invocateur);

        if($flush) {
            $this->doctrine->flush();
        }
        return $invocateur;
    }
}