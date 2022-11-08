<?php

namespace App\Command\Traits;

use App\Entity\Champion;
use App\Entity\Image;
use App\Entity\InfoChampion;
use App\Entity\StatChampion;
use App\Entity\Version;

trait ChampionTrait
{
    /**
     * @param mixed[] $championApi
     * @param Version $version
     * @return Champion
     */
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
        $info = $this->createInfoChampion($championApi['info']);
        $stats = $this->createStatChampion($championApi['stats']);
        $champion->setImage($image);
        $champion->setInfoChampion($info);
        $champion->setStatChampion($stats);

        $this->doctrine->persist($champion);

        return $champion;
    }

    /**
     * @param array<string, mixed> $championApi
     * @return InfoChampion
     */
    public function createInfoChampion(array $championApi): InfoChampion
    {
        $infoChampion = (new InfoChampion())
            ->setAttack($championApi['attack'])
            ->setDifficulty($championApi['difficulty'])
            ->setDefense($championApi['defense'])
            ->setMagic($championApi['magic'])
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

    /**
     * @param array<string, mixed> $championStatApi
     * @return StatChampion
     */
    private function createStatChampion(array $championStatApi): StatChampion
    {
        return (new StatChampion())
            ->setHp($championStatApi['hp'])
            ->setHpperlevel($championStatApi['hpperlevel'])
            ->setMp($championStatApi['mp'])
            ->setMpperlevel($championStatApi['mpperlevel'])
            ->setMovespeed($championStatApi['movespeed'])
            ->setArmor($championStatApi['armor'])
            ->setArmorperlevel($championStatApi['armorperlevel'])
            ->setSpellblock($championStatApi['spellblock'])
            ->setSpellblockperlevel($championStatApi['spellblockperlevel'])
            ->setAttackrange($championStatApi['attackrange'])
            ->setHpregen($championStatApi['hpregen'])
            ->setHpregenperlevel($championStatApi['hpregenperlevel'])
            ->setMpregen($championStatApi['mpregen'])
            ->setMpregenperlevel($championStatApi['mpregenperlevel'])
            ->setCrit($championStatApi['crit'])
            ->setCritperlevel($championStatApi['critperlevel'])
            ->setAttackdamage($championStatApi['attackdamage'])
            ->setAttackdamageperlevel($championStatApi['attackdamageperlevel'])
            ->setAttackspeedperlevel($championStatApi['attackspeedperlevel'])
            ->setAttackspeed($championStatApi['attackspeed'])
            ;
    }
}