<?php

namespace App\Traits;

use App\Entity\Invocateur;
use App\Services\API\LOL\LeagueOfLegends\DTO\SummonerDTO;
use Doctrine\ORM\EntityManagerInterface;

trait InvocateurTrait
{

    public function __construct(
        private EntityManagerInterface $em
    ){

    }

    /**
     * @param SummonerDTO $summonerDTO
     * @return Invocateur
     */
    public function invocateurCreate(SummonerDTO $summonerDTO): Invocateur
    {
        $invocateur = (new Invocateur())
            ->setName($summonerDTO->getName())
            ->setAccoundId($summonerDTO->getAccountId())
            ->setIdLol($summonerDTO->getId())
            ->setSummonerLevel($summonerDTO->getSummonerLevel())
            ->setPuuid($summonerDTO->getPuuid())
            ->setProfileIconId($summonerDTO->getProfileIconId())
        ;

        $this->em->persist($invocateur);
        $this->em->flush();

        return $invocateur;
    }
}