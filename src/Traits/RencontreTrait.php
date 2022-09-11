<?php

namespace App\Traits;

use App\Entity\Invocateur;
use App\Entity\Map;
use App\Entity\Rencontre;
use App\Services\API\LOL\LeagueOfLegends\MatchApi;
use Doctrine\ORM\EntityManagerInterface;

trait RencontreTrait
{

    public function __construct(
        private EntityManagerInterface $em,
        private MatchApi               $matchApi
    )
    {
    }

    public function create(string $gameId,Invocateur $invocateur)
    {

    }
}