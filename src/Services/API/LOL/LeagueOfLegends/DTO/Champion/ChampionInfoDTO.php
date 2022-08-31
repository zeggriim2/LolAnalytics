<?php

namespace App\Services\API\LOL\LeagueOfLegends\DTO\Champion;

class ChampionInfoDTO
{
    private int $maxNewPlayerLevel;

    /**
     * @var int[]
     */
    private array $freeChampionIdsForNewPlayers;

    /**
     * @var int[]
     */
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

    /**
     * @return int[]
     */
    public function getFreeChampionIdsForNewPlayers(): array
    {
        return $this->freeChampionIdsForNewPlayers;
    }

    /**
     * @param int[] $freeChampionIdsForNewPlayers
     *
     * @return $this
     */
    public function setFreeChampionIdsForNewPlayers(array $freeChampionIdsForNewPlayers): self
    {
        $this->freeChampionIdsForNewPlayers = $freeChampionIdsForNewPlayers;

        return $this;
    }

    /**
     * @return int[]
     */
    public function getFreeChampionIds(): array
    {
        return $this->freeChampionIds;
    }

    /**
     * @param int[] $freeChampionIds
     *
     * @return $this
     */
    public function setFreeChampionIds(array $freeChampionIds): self
    {
        $this->freeChampionIds = $freeChampionIds;

        return $this;
    }
}
