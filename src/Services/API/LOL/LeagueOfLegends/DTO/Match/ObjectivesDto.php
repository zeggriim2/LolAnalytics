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

    public function getBaron(): ObjectiveDto
    {
        return $this->baron;
    }

    public function setBaron(ObjectiveDto $baron): self
    {
        $this->baron = $baron;

        return $this;
    }

    public function getChampion(): ObjectiveDto
    {
        return $this->champion;
    }

    public function setChampion(ObjectiveDto $champion): self
    {
        $this->champion = $champion;

        return $this;
    }

    public function getDragon(): ObjectiveDto
    {
        return $this->dragon;
    }

    public function setDragon(ObjectiveDto $dragon): self
    {
        $this->dragon = $dragon;

        return $this;
    }

    public function getInhibitor(): ObjectiveDto
    {
        return $this->inhibitor;
    }

    public function setInhibitor(ObjectiveDto $inhibitor): self
    {
        $this->inhibitor = $inhibitor;

        return $this;
    }

    public function getRiftHerald(): ObjectiveDto
    {
        return $this->riftHerald;
    }

    public function setRiftHerald(ObjectiveDto $riftHerald): self
    {
        $this->riftHerald = $riftHerald;

        return $this;
    }

    public function getTower(): ObjectiveDto
    {
        return $this->tower;
    }

    public function setTower(ObjectiveDto $tower): self
    {
        $this->tower = $tower;

        return $this;
    }
}
