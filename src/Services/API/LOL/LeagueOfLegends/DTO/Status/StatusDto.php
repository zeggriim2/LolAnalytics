<?php

namespace App\Services\API\LOL\LeagueOfLegends\DTO\Status;

class StatusDto
{
    private int     $id;
    private ?string $maintenance_status;
    private ?string $incident_severity;
    /**
     * @var ContentDto[]
     */
    private array   $titles;
    /**
     * @var UpdateDto[]
     */
    private array   $updates;
    private string  $created_at;
    private ?string $archive_at;
    private ?string $updated_at;
    /**
     * @var array<string>
     */
    private array   $platforms;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return StatusDto
     */
    public function setId(int $id): StatusDto
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMaintenanceStatus(): ?string
    {
        return $this->maintenance_status;
    }

    /**
     * @param string|null $maintenance_status
     * @return StatusDto
     */
    public function setMaintenanceStatus(?string $maintenance_status): StatusDto
    {
        $this->maintenance_status = $maintenance_status;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getIncidentSeverity(): ?string
    {
        return $this->incident_severity;
    }

    /**
     * @param string|null $incident_severity
     * @return StatusDto
     */
    public function setIncidentSeverity(?string $incident_severity): StatusDto
    {
        $this->incident_severity = $incident_severity;
        return $this;
    }

    /**
     * @return ContentDto[]
     */
    public function getTitles(): array
    {
        return $this->titles;
    }

    /**
     * @param ContentDto[] $titles
     * @return StatusDto
     */
    public function setTitles(array $titles): StatusDto
    {
        $this->titles = $titles;
        return $this;
    }

    /**
     * @return UpdateDto[]
     */
    public function getUpdates(): array
    {
        return $this->updates;
    }

    /**
     * @param UpdateDto[] $updates
     * @return StatusDto
     */
    public function setUpdates(array $updates): StatusDto
    {
        $this->updates = $updates;
        return $this;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    /**
     * @param string $created_at
     * @return StatusDto
     */
    public function setCreatedAt(string $created_at): StatusDto
    {
        $this->created_at = $created_at;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getArchiveAt(): ?string
    {
        return $this->archive_at;
    }

    /**
     * @param string|null $archive_at
     * @return StatusDto
     */
    public function setArchiveAt(?string $archive_at): StatusDto
    {
        $this->archive_at = $archive_at;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getUpdatedAt(): ?string
    {
        return $this->updated_at;
    }

    /**
     * @param string|null $updated_at
     * @return StatusDto
     */
    public function setUpdatedAt(?string $updated_at): StatusDto
    {
        $this->updated_at = $updated_at;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getPlatforms(): array
    {
        return $this->platforms;
    }

    /**
     * @param string[] $platforms
     * @return StatusDto
     */
    public function setPlatforms(array $platforms): StatusDto
    {
        $this->platforms = $platforms;
        return $this;
    }
}