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
    private array $miniSeries;

    /**
     * @return string
     */
    public function getLeagueId(): string
    {
        return $this->leagueId;
    }

    /**
     * @param string $leagueId
     * @return LeagueEntryDTO
     */
    public function setLeagueId(string $leagueId): LeagueEntryDTO
    {
        $this->leagueId = $leagueId;
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
     * @return LeagueEntryDTO
     */
    public function setSummonerId(string $summonerId): LeagueEntryDTO
    {
        $this->summonerId = $summonerId;
        return $this;
    }

    /**
     * @return string
     */
    public function getSummonerName(): string
    {
        return $this->summonerName;
    }

    /**
     * @param string $summonerName
     * @return LeagueEntryDTO
     */
    public function setSummonerName(string $summonerName): LeagueEntryDTO
    {
        $this->summonerName = $summonerName;
        return $this;
    }

    /**
     * @return string
     */
    public function getQueueType(): string
    {
        return $this->queueType;
    }

    /**
     * @param string $queueType
     * @return LeagueEntryDTO
     */
    public function setQueueType(string $queueType): LeagueEntryDTO
    {
        $this->queueType = $queueType;
        return $this;
    }

    /**
     * @return string
     */
    public function getTier(): string
    {
        return $this->tier;
    }

    /**
     * @param string $tier
     * @return LeagueEntryDTO
     */
    public function setTier(string $tier): LeagueEntryDTO
    {
        $this->tier = $tier;
        return $this;
    }

    /**
     * @return string
     */
    public function getRank(): string
    {
        return $this->rank;
    }

    /**
     * @param string $rank
     * @return LeagueEntryDTO
     */
    public function setRank(string $rank): LeagueEntryDTO
    {
        $this->rank = $rank;
        return $this;
    }

    /**
     * @return int
     */
    public function getLeaguePoints(): int
    {
        return $this->leaguePoints;
    }

    /**
     * @param int $leaguePoints
     * @return LeagueEntryDTO
     */
    public function setLeaguePoints(int $leaguePoints): LeagueEntryDTO
    {
        $this->leaguePoints = $leaguePoints;
        return $this;
    }

    /**
     * @return int
     */
    public function getWins(): int
    {
        return $this->wins;
    }

    /**
     * @param int $wins
     * @return LeagueEntryDTO
     */
    public function setWins(int $wins): LeagueEntryDTO
    {
        $this->wins = $wins;
        return $this;
    }

    /**
     * @return int
     */
    public function getLosses(): int
    {
        return $this->losses;
    }

    /**
     * @param int $losses
     * @return LeagueEntryDTO
     */
    public function setLosses(int $losses): LeagueEntryDTO
    {
        $this->losses = $losses;
        return $this;
    }

    /**
     * @return bool
     */
    public function isHotStreak(): bool
    {
        return $this->hotStreak;
    }

    /**
     * @param bool $hotStreak
     * @return LeagueEntryDTO
     */
    public function setHotStreak(bool $hotStreak): LeagueEntryDTO
    {
        $this->hotStreak = $hotStreak;
        return $this;
    }

    /**
     * @return bool
     */
    public function isVeteran(): bool
    {
        return $this->veteran;
    }

    /**
     * @param bool $veteran
     * @return LeagueEntryDTO
     */
    public function setVeteran(bool $veteran): LeagueEntryDTO
    {
        $this->veteran = $veteran;
        return $this;
    }

    /**
     * @return bool
     */
    public function isFreshBlood(): bool
    {
        return $this->freshBlood;
    }

    /**
     * @param bool $freshBlood
     * @return LeagueEntryDTO
     */
    public function setFreshBlood(bool $freshBlood): LeagueEntryDTO
    {
        $this->freshBlood = $freshBlood;
        return $this;
    }

    /**
     * @return bool
     */
    public function isInactive(): bool
    {
        return $this->inactive;
    }

    /**
     * @param bool $inactive
     * @return LeagueEntryDTO
     */
    public function setInactive(bool $inactive): LeagueEntryDTO
    {
        $this->inactive = $inactive;
        return $this;
    }

    /**
     * @return array
     */
    public function getMiniSeries(): array
    {
        return $this->miniSeries;
    }

    /**
     * @param array $miniSeries
     * @return LeagueEntryDTO
     */
    public function setMiniSeries(array $miniSeries): LeagueEntryDTO
    {
        $this->miniSeries = $miniSeries;
        return $this;
    }
}