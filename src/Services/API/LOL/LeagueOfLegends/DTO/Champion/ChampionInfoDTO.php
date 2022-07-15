<?php

namespace App\Services\API\LOL\LeagueOfLegends\DTO\Champion;

class ChampionInfoDTO
{
    private int $maxNewPlayerLevel;
    private array $freeChampionIdsForNewPlayers;
    private array $freeChampionIds;

    /**
     * @return int
     */
    public function getMaxNewPlayerLevel(): int
    {
        return $this->maxNewPlayerLevel;
    }

    /**
     * @param int $maxNewPlayerLevel
     * @return ChampionInfoDTO
     */
    public function setMaxNewPlayerLevel(int $maxNewPlayerLevel): ChampionInfoDTO
    {
        $this->maxNewPlayerLevel = $maxNewPlayerLevel;
        return $this;
    }

    /**
     * @return array
     */
    public function getFreeChampionIdsForNewPlayers(): array
    {
        return $this->freeChampionIdsForNewPlayers;
    }

    /**
     * @param array $freeChampionIdsForNewPlayers
     * @return ChampionInfoDTO
     */
    public function setFreeChampionIdsForNewPlayers(array $freeChampionIdsForNewPlayers): ChampionInfoDTO
    {
        $this->freeChampionIdsForNewPlayers = $freeChampionIdsForNewPlayers;
        return $this;
    }

    /**
     * @return array
     */
    public function getFreeChampionIds(): array
    {
        return $this->freeChampionIds;
    }

    /**
     * @param array $freeChampionIds
     * @return ChampionInfoDTO
     */
    public function setFreeChampionIds(array $freeChampionIds): ChampionInfoDTO
    {
        $this->freeChampionIds = $freeChampionIds;
        return $this;
    }
}