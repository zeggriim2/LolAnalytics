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
     * @return TeamDto
     */
    public function setBans(array $bans): TeamDto
    {
        $this->bans = $bans;
        return $this;
    }

    /**
     * @return ObjectivesDto
     */
    public function getObjectives(): ObjectivesDto
    {
        return $this->objectives;
    }

    /**
     * @param ObjectivesDto $objectives
     * @return TeamDto
     */
    public function setObjectives(ObjectivesDto $objectives): TeamDto
    {
        $this->objectives = $objectives;
        return $this;
    }

    /**
     * @return int
     */
    public function getTeamId(): int
    {
        return $this->teamId;
    }

    /**
     * @param int $teamId
     * @return TeamDto
     */
    public function setTeamId(int $teamId): TeamDto
    {
        $this->teamId = $teamId;
        return $this;
    }

    /**
     * @return bool
     */
    public function isWin(): bool
    {
        return $this->win;
    }

    /**
     * @param bool $win
     * @return TeamDto
     */
    public function setWin(bool $win): TeamDto
    {
        $this->win = $win;
        return $this;
    }
}