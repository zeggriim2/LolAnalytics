<?php

declare(strict_types=1);

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

    public function getLeagueId(): string
    {
        return $this->leagueId;
    }

    public function setLeagueId(string $leagueId): self
    {
        $this->leagueId = $leagueId;

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

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getQueue(): string
    {
        return $this->queue;
    }

    public function setQueue(string $queue): self
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
     */
    public function setEntries(array $entries): self
    {
        $this->entries = $entries;

        return $this;
    }
}
