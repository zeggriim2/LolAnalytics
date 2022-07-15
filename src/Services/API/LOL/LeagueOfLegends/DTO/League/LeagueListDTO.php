<?php

namespace App\Services\API\LOL\LeagueOfLegends\DTO\League;

class LeagueListDTO
{
    private string $leagueId;
    private string $tier;
    private string $name;
    private string $queue;
    /**
     * @var LeagueItemDTO[]
     */
    private array $entries;

    /**
     * @return string
     */
    public function getLeagueId(): string
    {
        return $this->leagueId;
    }

    /**
     * @param string $leagueId
     * @return LeagueListDTO
     */
    public function setLeagueId(string $leagueId): LeagueListDTO
    {
        $this->leagueId = $leagueId;
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
     * @return LeagueListDTO
     */
    public function setTier(string $tier): LeagueListDTO
    {
        $this->tier = $tier;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return LeagueListDTO
     */
    public function setName(string $name): LeagueListDTO
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getQueue(): string
    {
        return $this->queue;
    }

    /**
     * @param string $queue
     * @return LeagueListDTO
     */
    public function setQueue(string $queue): LeagueListDTO
    {
        $this->queue = $queue;
        return $this;
    }

    /**
     * @return LeagueItemDTO[]
     */
    public function getEntries(): array
    {
        return $this->entries;
    }

    /**
     * @param LeagueItemDTO[] $entries
     * @return LeagueListDTO
     */
    public function setEntries(array $entries): LeagueListDTO
    {
        $this->entries = $entries;
        return $this;
    }
}