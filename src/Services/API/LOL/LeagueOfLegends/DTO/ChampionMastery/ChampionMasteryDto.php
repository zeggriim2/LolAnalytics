<?php

declare(strict_types=1);

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

    public function getChampionPointsUntilNextLevel(): int
    {
        return $this->championPointsUntilNextLevel;
    }

    public function setChampionPointsUntilNextLevel(int $championPointsUntilNextLevel): self
    {
        $this->championPointsUntilNextLevel = $championPointsUntilNextLevel;

        return $this;
    }

    public function isChestGranted(): bool
    {
        return $this->chestGranted;
    }

    public function setChestGranted(bool $chestGranted): self
    {
        $this->chestGranted = $chestGranted;

        return $this;
    }

    public function getChampionId(): int
    {
        return $this->championId;
    }

    public function setChampionId(int $championId): self
    {
        $this->championId = $championId;

        return $this;
    }

    public function getLastPlayTime(): int
    {
        return $this->lastPlayTime;
    }

    public function setLastPlayTime(int $lastPlayTime): self
    {
        $this->lastPlayTime = $lastPlayTime;

        return $this;
    }

    public function getChampionLevel(): int
    {
        return $this->championLevel;
    }

    public function setChampionLevel(int $championLevel): self
    {
        $this->championLevel = $championLevel;

        return $this;
    }

    public function getSummonerId(): string
    {
        return $this->summonerId;
    }

    public function setSummonerId(string $summonerId): self
    {
        $this->summonerId = $summonerId;

        return $this;
    }

    public function getChampionPoints(): int
    {
        return $this->championPoints;
    }

    public function setChampionPoints(int $championPoints): self
    {
        $this->championPoints = $championPoints;

        return $this;
    }

    public function getChampionPointsSinceLastLevel(): int
    {
        return $this->championPointsSinceLastLevel;
    }

    public function setChampionPointsSinceLastLevel(int $championPointsSinceLastLevel): self
    {
        $this->championPointsSinceLastLevel = $championPointsSinceLastLevel;

        return $this;
    }

    public function getTokensEarned(): int
    {
        return $this->tokensEarned;
    }

    public function setTokensEarned(int $tokensEarned): self
    {
        $this->tokensEarned = $tokensEarned;

        return $this;
    }
}
