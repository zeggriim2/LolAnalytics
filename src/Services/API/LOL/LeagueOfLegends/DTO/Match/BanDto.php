<?php

namespace App\Services\API\LOL\LeagueOfLegends\DTO\Match;

class BanDto
{
    private int $championId;
    private int $pickTurn;

    /**
     * @return int
     */
    public function getChampionId(): int
    {
        return $this->championId;
    }

    /**
     * @param int $championId
     * @return BanDto
     */
    public function setChampionId(int $championId): BanDto
    {
        $this->championId = $championId;
        return $this;
    }

    /**
     * @return int
     */
    public function getPickTurn(): int
    {
        return $this->pickTurn;
    }

    /**
     * @param int $pickTurn
     * @return BanDto
     */
    public function setPickTurn(int $pickTurn): BanDto
    {
        $this->pickTurn = $pickTurn;
        return $this;
    }
}