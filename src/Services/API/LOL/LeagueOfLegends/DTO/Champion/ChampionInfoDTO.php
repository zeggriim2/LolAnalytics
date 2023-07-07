<?php

declare(strict_types=1);

namespace App\Services\API\LOL\LeagueOfLegends\DTO\Champion;

class ChampionInfoDTO
{
    private int $maxNewPlayerLevel;
    private array $freeChampionIdsForNewPlayers;
    private array $freeChampionIds;

    public function getMaxNewPlayerLevel(): int
    {
        return $this->maxNewPlayerLevel;
    }

    public function setMaxNewPlayerLevel(int $maxNewPlayerLevel): self
    {
        $this->maxNewPlayerLevel = $maxNewPlayerLevel;

        return $this;
    }

    public function getFreeChampionIdsForNewPlayers(): array
    {
        return $this->freeChampionIdsForNewPlayers;
    }

    public function setFreeChampionIdsForNewPlayers(array $freeChampionIdsForNewPlayers): self
    {
        $this->freeChampionIdsForNewPlayers = $freeChampionIdsForNewPlayers;

        return $this;
    }

    public function getFreeChampionIds(): array
    {
        return $this->freeChampionIds;
    }

    public function setFreeChampionIds(array $freeChampionIds): self
    {
        $this->freeChampionIds = $freeChampionIds;

        return $this;
    }
}
