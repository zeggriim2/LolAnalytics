<?php

namespace App\Traits\Command;

use App\Entity\Champion;
use App\Entity\Image;
use App\Entity\InfoChampion;
use App\Entity\Version;

trait CreateObjectChampionTrait
{
    /**
     * @param mixed[] $championApi
     * @param Version $version
     * @return void
     */
    private function createChampion(array $championApi, Version $version): void
    {
        $champion = (new Champion())
            ->setIdName($championApi['id'])
            ->setName($championApi['name'])
            ->setKey($championApi['key'])
            ->setTitle($championApi['title'])
            ->setVersion($version)
        ;
        if (key_exists('partype', $championApi)){
            $champion->setPartype($championApi['partype']);
        }
        $infoChampion = $this->createInfoChampion($championApi['info']);
        $champion->setInfoChampion($infoChampion);
        $image = $this->createImageChampion($championApi['image']);
        $champion->setImage($image);

        $this->doctrine->persist($champion);

        if (($this->inscrementBatch % $this->batchSize) === 0) {
            $this->doctrine->flush();
            $this->doctrine->clear();
//            $this->doctrine->clear(Champion::class);
//            $this->doctrine->clear(InfoChampion::class);
//            $this->doctrine->clear(Image::class);
        }
    }

    /**
     * @param mixed[] $info
     * @return InfoChampion
     */
    private function createInfoChampion(array $info): InfoChampion
    {
        return (new InfoChampion())
            ->setAttack($info['attack'])
            ->setDefense($info['defense'])
            ->setMagic($info['magic'])
            ->setDifficulty($info['difficulty'])
            ;
    }

    /**
     * @param mixed[] $image
     * @return Image
     */
    private function createImageChampion(array $image): Image
    {
        $image = (new Image())
            ->setComplete($image['full'])
            ->setSprite($image['sprite'])
            ->setGroupe($image['group'])
            ->setH($image['h'])
            ->setW($image['w'])
            ->setX($image['x'])
            ->setY($image['y'])
        ;
        $this->doctrine->persist($image);
        return $image;
    }
}