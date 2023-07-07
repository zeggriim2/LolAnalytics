<?php

declare(strict_types=1);

namespace App\Services\API\LOL\LeagueOfLegends\DTO\League;

class LeagueItemDTO
{
    private bool $freshBlood;
    private int $wins;
    private string $summonerName;
    private array $miniSeries;
    private bool $inactive;
    private bool $veteran;
    private bool $hotStreak;
    private string $rank;
    private int $leaguePoints;
    private int $losses;
    private string $summonerId;

    public function isFreshBlood(): bool
    {
        return $this->freshBlood;
    }

    public function setFreshBlood(bool $freshBlood): self
    {
        $this->freshBlood = $freshBlood;

        return $this;
    }

    public function getWins(): int
    {
        return $this->wins;
    }

    public function setWins(int $wins): self
    {
        $this->wins = $wins;

        return $this;
    }

    public function getSummonerName(): string
    {
        return $this->summonerName;
    }

    public function setSummonerName(string $summonerName): self
    {
        $this->summonerName = $summonerName;

        return $this;
    }

    public function getMiniSeries(): array
    {
        return $this->miniSeries;
    }

    public function setMiniSeries(array $miniSeries): self
    {
        $this->miniSeries = $miniSeries;

        return $this;
    }

    public function isInactive(): bool
    {
        return $this->inactive;
    }

    public function setInactive(bool $inactive): self
    {
        $this->inactive = $inactive;

        return $this;
    }

    public function isVeteran(): bool
    {
        return $this->veteran;
    }

    public function setVeteran(bool $veteran): self
    {
        $this->veteran = $veteran;

        return $this;
    }

    public function isHotStreak(): bool
    {
        return $this->hotStreak;
    }

    public function setHotStreak(bool $hotStreak): self
    {
        $this->hotStreak = $hotStreak;

        return $this;
    }

    public function getRank(): string
    {
        return $this->rank;
    }

    public function setRank(string $rank): self
    {
        $this->rank = $rank;

        return $this;
    }

    public function getLeaguePoints(): int
    {
        return $this->leaguePoints;
    }

    public function setLeaguePoints(int $leaguePoints): self
    {
        $this->leaguePoints = $leaguePoints;

        return $this;
    }

    public function getLosses(): int
    {
        return $this->losses;
    }

    public function setLosses(int $losses): self
    {
        $this->losses = $losses;

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
}
