<?php

namespace App\Services\API\LOL\LeagueOfLegends\DTO\Match;

class ObjectivesDto
{
    private ObjectiveDto $baron;
    private ObjectiveDto $champion;
    private ObjectiveDto $dragon;
    private ObjectiveDto $inhibitor;
    private ObjectiveDto $riftHerald;
    private ObjectiveDto $tower;

    /**
     * @return ObjectiveDto
     */
    public function getBaron(): ObjectiveDto
    {
        return $this->baron;
    }

    /**
     * @param ObjectiveDto $baron
     * @return ObjectivesDto
     */
    public function setBaron(ObjectiveDto $baron): ObjectivesDto
    {
        $this->baron = $baron;
        return $this;
    }

    /**
     * @return ObjectiveDto
     */
    public function getChampion(): ObjectiveDto
    {
        return $this->champion;
    }

    /**
     * @param ObjectiveDto $champion
     * @return ObjectivesDto
     */
    public function setChampion(ObjectiveDto $champion): ObjectivesDto
    {
        $this->champion = $champion;
        return $this;
    }

    /**
     * @return ObjectiveDto
     */
    public function getDragon(): ObjectiveDto
    {
        return $this->dragon;
    }

    /**
     * @param ObjectiveDto $dragon
     * @return ObjectivesDto
     */
    public function setDragon(ObjectiveDto $dragon): ObjectivesDto
    {
        $this->dragon = $dragon;
        return $this;
    }

    /**
     * @return ObjectiveDto
     */
    public function getInhibitor(): ObjectiveDto
    {
        return $this->inhibitor;
    }

    /**
     * @param ObjectiveDto $inhibitor
     * @return ObjectivesDto
     */
    public function setInhibitor(ObjectiveDto $inhibitor): ObjectivesDto
    {
        $this->inhibitor = $inhibitor;
        return $this;
    }

    /**
     * @return ObjectiveDto
     */
    public function getRiftHerald(): ObjectiveDto
    {
        return $this->riftHerald;
    }

    /**
     * @param ObjectiveDto $riftHerald
     * @return ObjectivesDto
     */
    public function setRiftHerald(ObjectiveDto $riftHerald): ObjectivesDto
    {
        $this->riftHerald = $riftHerald;
        return $this;
    }

    /**
     * @return ObjectiveDto
     */
    public function getTower(): ObjectiveDto
    {
        return $this->tower;
    }

    /**
     * @param ObjectiveDto $tower
     * @return ObjectivesDto
     */
    public function setTower(ObjectiveDto $tower): ObjectivesDto
    {
        $this->tower = $tower;
        return $this;
    }
}