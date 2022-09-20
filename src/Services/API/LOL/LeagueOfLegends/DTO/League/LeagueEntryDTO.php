<?php

namespace App\Services\API\LOL\LeagueOfLegends\DTO\League;

class LeagueEntryDTO
{
    private string $leagueId;
    private string $summonerId;
    private string $summonerName;
    private string $queueType;
    private string $tier;
    private string $rank;
    private int $leaguePoints;
    private int $wins;
    private int $losses;
    private bool $hotStreak;
    private bool $veteran;
    private bool $freshBlood;
    private bool $inactive;
    /**
     * @var array<string, int|string>
     */
    private ?array $miniSeries = null;

    public function getLeagueId(): string
    {
        return $this->leagueId;
    }

    public function setLeagueId(string $leagueId): self
    {
        $this->leagueId = $leagueId;

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

    public function getSummonerName(): string
    {
        return $this->summonerName;
    }

    public function setSummonerName(string $summonerName): self
    {
        $this->summonerName = $summonerName;

        return $this;
    }

    public function getQueueType(): string
    {
        return $this->queueType;
    }

    public function setQueueType(string $queueType): self
    {
        $this->queueType = $queueType;

        return $this;
    }

    public function getTier(): string
    {
        return $this->tier;
    }

    public function setTier(string $tier): self
    {
        $this->tier = $tier;

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

    public function getWins(): int
    {
        return $this->wins;
    }

    public function setWins(int $wins): self
    {
        $this->wins = $wins;

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

    public function isHotStreak(): bool
    {
        return $this->hotStreak;
    }

    public function setHotStreak(bool $hotStreak): self
    {
        $this->hotStreak = $hotStreak;

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

    public function isFreshBlood(): bool
    {
        return $this->freshBlood;
    }

    public function setFreshBlood(bool $freshBlood): self
    {
        $this->freshBlood = $freshBlood;

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

    /**
     * @return null|array<string, int|string>
     */
    public function getMiniSeries(): ?array
    {
        return $this->miniSeries;
    }

    /**
     * @param array<string, int|string> $miniSeries
     *
     * @return $this
     */
    public function setMiniSeries(array $miniSeries): self
    {
        $this->miniSeries = $miniSeries;

        return $this;
    }
}
