<?php

declare(strict_types=1);

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

    public function getGameCreation(): int
    {
        return $this->gameCreation;
    }

    public function setGameCreation(int $gameCreation): self
    {
        $this->gameCreation = $gameCreation;

        return $this;
    }

    public function getGameDuration(): int
    {
        return $this->gameDuration;
    }

    public function setGameDuration(int $gameDuration): self
    {
        $this->gameDuration = $gameDuration;

        return $this;
    }

    public function getGameEndTimestamp(): int
    {
        return $this->gameEndTimestamp;
    }

    public function setGameEndTimestamp(int $gameEndTimestamp): self
    {
        $this->gameEndTimestamp = $gameEndTimestamp;

        return $this;
    }

    public function getGameId(): int
    {
        return $this->gameId;
    }

    public function setGameId(int $gameId): self
    {
        $this->gameId = $gameId;

        return $this;
    }

    public function getGameMode(): string
    {
        return $this->gameMode;
    }

    public function setGameMode(string $gameMode): self
    {
        $this->gameMode = $gameMode;

        return $this;
    }

    public function getGameName(): string
    {
        return $this->gameName;
    }

    public function setGameName(string $gameName): self
    {
        $this->gameName = $gameName;

        return $this;
    }

    public function getGameStartTimestamp(): int
    {
        return $this->gameStartTimestamp;
    }

    public function setGameStartTimestamp(int $gameStartTimestamp): self
    {
        $this->gameStartTimestamp = $gameStartTimestamp;

        return $this;
    }

    public function getGameType(): string
    {
        return $this->gameType;
    }

    public function setGameType(string $gameType): self
    {
        $this->gameType = $gameType;

        return $this;
    }

    public function getGameVersion(): string
    {
        return $this->gameVersion;
    }

    public function setGameVersion(string $gameVersion): self
    {
        $this->gameVersion = $gameVersion;

        return $this;
    }

    public function getMapId(): int
    {
        return $this->mapId;
    }

    public function setMapId(int $mapId): self
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
     */
    public function setParticipants(array $participants): self
    {
        $this->participants = $participants;

        return $this;
    }

    public function getPlatformId(): string
    {
        return $this->platformId;
    }

    public function setPlatformId(string $platformId): self
    {
        $this->platformId = $platformId;

        return $this;
    }

    public function getQueueId(): int
    {
        return $this->queueId;
    }

    public function setQueueId(int $queueId): self
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
     */
    public function setTeams(array $teams): self
    {
        $this->teams = $teams;

        return $this;
    }

    public function getTournamentCode(): string
    {
        return $this->tournamentCode;
    }

    public function setTournamentCode(string $tournamentCode): self
    {
        $this->tournamentCode = $tournamentCode;

        return $this;
    }
}
