<?php

namespace App\Services\API\LOL\LeagueOfLegends\DTO\Match;

class InfoDto
{
    private int $gameCreation;
    private int $gameDuration;
    private int $gameEndTimestamp;
    private int $gameId;
    private string $gameMode;
    private string $gameName;
    private int $gameStartTimestamp;
    private string $gameType;
    private string $gameVersion;
    private int $mapId;
    /**
     * @var ParticipantDto[]
     */
    private array $participants;
    private string $platformId;
    private int $queueId;
    /**
     * @var TeamDto[]
     */
    private array $teams;
    private string $tournamentCode;

    /**
     * @return int
     */
    public function getGameCreation(): int
    {
        return $this->gameCreation;
    }

    /**
     * @param int $gameCreation
     * @return InfoDto
     */
    public function setGameCreation(int $gameCreation): InfoDto
    {
        $this->gameCreation = $gameCreation;
        return $this;
    }

    /**
     * @return int
     */
    public function getGameDuration(): int
    {
        return $this->gameDuration;
    }

    /**
     * @param int $gameDuration
     * @return InfoDto
     */
    public function setGameDuration(int $gameDuration): InfoDto
    {
        $this->gameDuration = $gameDuration;
        return $this;
    }

    /**
     * @return int
     */
    public function getGameEndTimestamp(): int
    {
        return $this->gameEndTimestamp;
    }

    /**
     * @param int $gameEndTimestamp
     * @return InfoDto
     */
    public function setGameEndTimestamp(int $gameEndTimestamp): InfoDto
    {
        $this->gameEndTimestamp = $gameEndTimestamp;
        return $this;
    }

    /**
     * @return int
     */
    public function getGameId(): int
    {
        return $this->gameId;
    }

    /**
     * @param int $gameId
     * @return InfoDto
     */
    public function setGameId(int $gameId): InfoDto
    {
        $this->gameId = $gameId;
        return $this;
    }

    /**
     * @return string
     */
    public function getGameMode(): string
    {
        return $this->gameMode;
    }

    /**
     * @param string $gameMode
     * @return InfoDto
     */
    public function setGameMode(string $gameMode): InfoDto
    {
        $this->gameMode = $gameMode;
        return $this;
    }

    /**
     * @return string
     */
    public function getGameName(): string
    {
        return $this->gameName;
    }

    /**
     * @param string $gameName
     * @return InfoDto
     */
    public function setGameName(string $gameName): InfoDto
    {
        $this->gameName = $gameName;
        return $this;
    }

    /**
     * @return int
     */
    public function getGameStartTimestamp(): int
    {
        return $this->gameStartTimestamp;
    }

    /**
     * @param int $gameStartTimestamp
     * @return InfoDto
     */
    public function setGameStartTimestamp(int $gameStartTimestamp): InfoDto
    {
        $this->gameStartTimestamp = $gameStartTimestamp;
        return $this;
    }

    /**
     * @return string
     */
    public function getGameType(): string
    {
        return $this->gameType;
    }

    /**
     * @param string $gameType
     * @return InfoDto
     */
    public function setGameType(string $gameType): InfoDto
    {
        $this->gameType = $gameType;
        return $this;
    }

    /**
     * @return string
     */
    public function getGameVersion(): string
    {
        return $this->gameVersion;
    }

    /**
     * @param string $gameVersion
     * @return InfoDto
     */
    public function setGameVersion(string $gameVersion): InfoDto
    {
        $this->gameVersion = $gameVersion;
        return $this;
    }

    /**
     * @return int
     */
    public function getMapId(): int
    {
        return $this->mapId;
    }

    /**
     * @param int $mapId
     * @return InfoDto
     */
    public function setMapId(int $mapId): InfoDto
    {
        $this->mapId = $mapId;
        return $this;
    }

    /**
     * @return ParticipantDto[]
     */
    public function getParticipants(): array
    {
        return $this->participants;
    }

    /**
     * @param ParticipantDto[] $participants
     * @return InfoDto
     */
    public function setParticipants(array $participants): InfoDto
    {
        $this->participants = $participants;
        return $this;
    }

    /**
     * @return string
     */
    public function getPlatformId(): string
    {
        return $this->platformId;
    }

    /**
     * @param string $platformId
     * @return InfoDto
     */
    public function setPlatformId(string $platformId): InfoDto
    {
        $this->platformId = $platformId;
        return $this;
    }

    /**
     * @return int
     */
    public function getQueueId(): int
    {
        return $this->queueId;
    }

    /**
     * @param int $queueId
     * @return InfoDto
     */
    public function setQueueId(int $queueId): InfoDto
    {
        $this->queueId = $queueId;
        return $this;
    }

    /**
     * @return TeamDto[]
     */
    public function getTeams(): array
    {
        return $this->teams;
    }

    /**
     * @param TeamDto[] $teams
     * @return InfoDto
     */
    public function setTeams(array $teams): InfoDto
    {
        $this->teams = $teams;
        return $this;
    }

    /**
     * @return string
     */
    public function getTournamentCode(): string
    {
        return $this->tournamentCode;
    }

    /**
     * @param string $tournamentCode
     * @return InfoDto
     */
    public function setTournamentCode(string $tournamentCode): InfoDto
    {
        $this->tournamentCode = $tournamentCode;
        return $this;
    }
}