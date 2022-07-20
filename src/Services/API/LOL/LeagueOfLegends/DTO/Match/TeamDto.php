<?php

namespace App\Services\API\LOL\LeagueOfLegends\DTO\Match;

class TeamDto
{
    /**
     * @var BanDto[]
     */
    private array $bans;
    private ObjectivesDto $objectives;
    private int $teamId;
    private bool $win;

    /**
     * @return BanDto[]
     */
    public function getBans(): array
    {
        return $this->bans;
    }

    /**
     * @param BanDto[] $bans
     */
    public function setBans(array $bans): self
    {
        $this->bans = $bans;

        return $this;
    }

    public function getObjectives(): ObjectivesDto
    {
        return $this->objectives;
    }

    public function setObjectives(ObjectivesDto $objectives): self
    {
        $this->objectives = $objectives;

        return $this;
    }

    public function getTeamId(): int
    {
        return $this->teamId;
    }

    public function setTeamId(int $teamId): self
    {
        $this->teamId = $teamId;

        return $this;
    }

    public function isWin(): bool
    {
        return $this->win;
    }

    public function setWin(bool $win): self
    {
        $this->win = $win;

        return $this;
    }
}
