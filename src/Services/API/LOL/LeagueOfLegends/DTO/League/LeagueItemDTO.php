<?php

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

    /**
     * @return bool
     */
    public function isFreshBlood(): bool
    {
        return $this->freshBlood;
    }

    /**
     * @param bool $freshBlood
     * @return LeagueItemDTO
     */
    public function setFreshBlood(bool $freshBlood): LeagueItemDTO
    {
        $this->freshBlood = $freshBlood;
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
     * @return LeagueItemDTO
     */
    public function setWins(int $wins): LeagueItemDTO
    {
        $this->wins = $wins;
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
     * @return LeagueItemDTO
     */
    public function setSummonerName(string $summonerName): LeagueItemDTO
    {
        $this->summonerName = $summonerName;
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
     * @return LeagueItemDTO
     */
    public function setMiniSeries(array $miniSeries): LeagueItemDTO
    {
        $this->miniSeries = $miniSeries;
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
     * @return LeagueItemDTO
     */
    public function setInactive(bool $inactive): LeagueItemDTO
    {
        $this->inactive = $inactive;
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
     * @return LeagueItemDTO
     */
    public function setVeteran(bool $veteran): LeagueItemDTO
    {
        $this->veteran = $veteran;
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
     * @return LeagueItemDTO
     */
    public function setHotStreak(bool $hotStreak): LeagueItemDTO
    {
        $this->hotStreak = $hotStreak;
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
     * @return LeagueItemDTO
     */
    public function setRank(string $rank): LeagueItemDTO
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
     * @return LeagueItemDTO
     */
    public function setLeaguePoints(int $leaguePoints): LeagueItemDTO
    {
        $this->leaguePoints = $leaguePoints;
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
     * @return LeagueItemDTO
     */
    public function setLosses(int $losses): LeagueItemDTO
    {
        $this->losses = $losses;
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
     * @return LeagueItemDTO
     */
    public function setSummonerId(string $summonerId): LeagueItemDTO
    {
        $this->summonerId = $summonerId;
        return $this;
    }
}