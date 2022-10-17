<?php

namespace App\Command\Traits;

use App\Entity\Champion;
use App\Entity\Image;
use App\Entity\InfoChampion;
use App\Entity\Version;

trait ChampionTrait
{
    public function createChampion(
        array $championApi,
        Version $version
    ): Champion
    {
        $champion = (new Champion())
            ->setIdName($championApi['id'])
            ->setName($championApi['name'])
            ->setKey($championApi['key'])
            ->setTitle($championApi['title'])
            ->setPartype($championApi['partype'] ?: null)
            ->setVersion($version)
        ;

        $image = $this->createImageChampion($championApi['image']);

        return $champion;
    }

    /**
     * @param array<string, mixed> $championApi
     * @return InfoChampion
     */
    public function createInfoChampion(array $championApi): InfoChampion
    {
        $infoChampion = (new InfoChampion())
            ->setAttack($championApi['info']['attack'])
            ->setDifficulty($championApi['info']['difficulty'])
            ->setDefense($championApi['info']['defense'])
            ->setMagic($championApi['info']['magic'])
        ;
        $this->doctrine->persist($infoChampion);
        return $infoChampion;
    }


    /**
     * @param array<string, mixed> $championImageApi
     * @return Image
     */
    private function createImageChampion(array $championImageApi): Image
    {

        return (new Image())
            ->setComplete($championImageApi['full'])
            ->setGroupe($championImageApi['sprite'])
            ->setSprite($championImageApi['group'])
            ->setX($championImageApi['x'])
            ->setY($championImageApi['y'])
            ->setW($championImageApi['w'])
            ->setH($championImageApi['h']);
    }
}