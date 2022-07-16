<?php

namespace App\Services\API\LOL\LeagueOfLegends\DTO\ChampionMastery;

class ChampionMasteryDto
{
    private int $championPointsUntilNextLevel;
    private bool $chestGranted;
    private int $championId;
    private int $lastPlayTime;
    private int $championLevel;
    private string $summonerId;
    private int $championPoints;
    private int $championPointsSinceLastLevel;
    private int $tokensEarned;

    /**
     * @return int
     */
    public function getChampionPointsUntilNextLevel(): int
    {
        return $this->championPointsUntilNextLevel;
    }

    /**
     * @param int $championPointsUntilNextLevel
     * @return ChampionMasteryDto
     */
    public function setChampionPointsUntilNextLevel(int $championPointsUntilNextLevel): ChampionMasteryDto
    {
        $this->championPointsUntilNextLevel = $championPointsUntilNextLevel;
        return $this;
    }

    /**
     * @return bool
     */
    public function isChestGranted(): bool
    {
        return $this->chestGranted;
    }

    /**
     * @param bool $chestGranted
     * @return ChampionMasteryDto
     */
    public function setChestGranted(bool $chestGranted): ChampionMasteryDto
    {
        $this->chestGranted = $chestGranted;
        return $this;
    }

    /**
     * @return int
     */
    public function getChampionId(): int
    {
        return $this->championId;
    }

    /**
     * @param int $championId
     * @return ChampionMasteryDto
     */
    public function setChampionId(int $championId): ChampionMasteryDto
    {
        $this->championId = $championId;
        return $this;
    }

    /**
     * @return int
     */
    public function getLastPlayTime(): int
    {
        return $this->lastPlayTime;
    }

    /**
     * @param int $lastPlayTime
     * @return ChampionMasteryDto
     */
    public function setLastPlayTime(int $lastPlayTime): ChampionMasteryDto
    {
        $this->lastPlayTime = $lastPlayTime;
        return $this;
    }

    /**
     * @return int
     */
    public function getChampionLevel(): int
    {
        return $this->championLevel;
    }

    /**
     * @param int $championLevel
     * @return ChampionMasteryDto
     */
    public function setChampionLevel(int $championLevel): ChampionMasteryDto
    {
        $this->championLevel = $championLevel;
        return $this;
    }

    /**
     * @return string
     */
    public function getSummonerId(): string
    {
        return $this->summonerId;
    }

    /**
     * @param string $summonerId
     * @return ChampionMasteryDto
     */
    public function setSummonerId(string $summonerId): ChampionMasteryDto
    {
        $this->summonerId = $summonerId;
        return $this;
    }

    /**
     * @return int
     */
    public function getChampionPoints(): int
    {
        return $this->championPoints;
    }

    /**
     * @param int $championPoints
     * @return ChampionMasteryDto
     */
    public function setChampionPoints(int $championPoints): ChampionMasteryDto
    {
        $this->championPoints = $championPoints;
        return $this;
    }

    /**
     * @return int
     */
    public function getChampionPointsSinceLastLevel(): int
    {
        return $this->championPointsSinceLastLevel;
    }

    /**
     * @param int $championPointsSinceLastLevel
     * @return ChampionMasteryDto
     */
    public function setChampionPointsSinceLastLevel(int $championPointsSinceLastLevel): ChampionMasteryDto
    {
        $this->championPointsSinceLastLevel = $championPointsSinceLastLevel;
        return $this;
    }

    /**
     * @return int
     */
    public function getTokensEarned(): int
    {
        return $this->tokensEarned;
    }

    /**
     * @param int $tokensEarned
     * @return ChampionMasteryDto
     */
    public function setTokensEarned(int $tokensEarned): ChampionMasteryDto
    {
        $this->tokensEarned = $tokensEarned;
        return $this;
    }
}