<?php

namespace App\Services\API\LOL\LeagueOfLegends\DTO\Match;

class MetadataDto
{
    private string $dataVersion;
    private string $matchId;
    /**
     * @var string[]
     */
    private array $participants;

    public function getDataVersion(): string
    {
        return $this->dataVersion;
    }

    public function setDataVersion(string $dataVersion): self
    {
        $this->dataVersion = $dataVersion;

        return $this;
    }

    public function getMatchId(): string
    {
        return $this->matchId;
    }

    public function setMatchId(string $matchId): self
    {
        $this->matchId = $matchId;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getParticipants(): array
    {
        return $this->participants;
    }

    /**
     * @param string[] $participants
     */
    public function setParticipants(array $participants): self
    {
        $this->participants = $participants;

        return $this;
    }
}
