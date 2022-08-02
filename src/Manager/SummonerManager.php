<?php

namespace App\Manager;

use App\Entity\Invocateur;
use App\Services\API\LOL\LeagueOfLegends\DTO\SummonerDTO;
use Doctrine\ORM\EntityManagerInterface;

class SummonerManager
{
    public function __construct(
        private EntityManagerInterface $doctrine
    )
    {
    }

    /**
     * @param SummonerDTO|null $summonerApi
     * @return string|void
     */
    public function enregistrer(?SummonerDTO $summonerApi)
    {
        if($summonerApi === null){
            // TODO Exception
            return $error['message'] = 'Nom d\'utilisateur introuvable';
        } elseif ($this->doctrine->getRepository(Invocateur::class)->findOneBy(['idLol' => $summonerApi->getId()])) {
            // TODO Exception
            return $error['message'] = 'Nom d\'utilisateur déjà existant';
        }

        $invocateur = (new Invocateur())
            ->setName($summonerApi->getName())
            ->setIdLol($summonerApi->getId())
            ->setAccoundId($summonerApi->getAccountId())
            ->setPuuid($summonerApi->getPuuid())
            ->setProfileIconId($summonerApi->getProfileIconId())
            ->setSummonerLevel($summonerApi->getSummonerLevel())
        ;

        $this->doctrine->persist($invocateur);
        $this->doctrine->flush();
    }

}