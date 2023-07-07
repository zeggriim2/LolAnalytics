<?php

declare(strict_types=1);

namespace App\Services\API\LOL\LeagueOfLegends\DTO\Match;

class BanDto
{
    private int $championId;
    private int $pickTurn;

    public function getChampionId(): int
    {
        return $this->championId;
    }

    public function setChampionId(int $championId): self
    {
        $this->championId = $championId;

        return $this;
    }

    public function getPickTurn(): int
    {
        return $this->pickTurn;
    }

    public function setPickTurn(int $pickTurn): self
    {
        $this->pickTurn = $pickTurn;

        return $this;
    }
}
