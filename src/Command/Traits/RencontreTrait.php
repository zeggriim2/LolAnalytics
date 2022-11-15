<?php

namespace App\Command\Traits;

use App\Entity\Invocateur;
use App\Entity\Map;
use App\Entity\Rencontre;
use App\Services\API\LOL\LeagueOfLegends\DTO\Match\MatchDto;

trait RencontreTrait
{
    public function rencontre(MatchDto $matchDto,?Map $map): Rencontre
    {
        $rencontre = (new Rencontre())
            ->setGameId($matchDto->getInfo()->getGameId())
            ->setGameDuration($matchDto->getInfo()->getGameDuration())
            ->setGameCreation(
                (new \DateTimeImmutable())->setTimestamp((int)floor($matchDto->getInfo()->getGameCreation() / 1000))
            )
            ->setMap($map)
            ->setGameMode($matchDto->getInfo()->getGameMode())
            ->setGameVersion($matchDto->getInfo()->getGameVersion())
        ;
        return $rencontre;
    }
}