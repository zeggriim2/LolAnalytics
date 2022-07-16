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

    /**
     * @return string
     */
    public function getDataVersion(): string
    {
        return $this->dataVersion;
    }

    /**
     * @param string $dataVersion
     * @return MetadataDto
     */
    public function setDataVersion(string $dataVersion): MetadataDto
    {
        $this->dataVersion = $dataVersion;
        return $this;
    }

    /**
     * @return string
     */
    public function getMatchId(): string
    {
        return $this->matchId;
    }

    /**
     * @param string $matchId
     * @return MetadataDto
     */
    public function setMatchId(string $matchId): MetadataDto
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
     * @return MetadataDto
     */
    public function setParticipants(array $participants): MetadataDto
    {
        $this->participants = $participants;
        return $this;
    }
}