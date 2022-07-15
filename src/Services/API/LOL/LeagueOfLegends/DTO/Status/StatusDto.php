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

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getMaintenanceStatus(): ?string
    {
        return $this->maintenance_status;
    }

    public function setMaintenanceStatus(?string $maintenance_status): self
    {
        $this->maintenance_status = $maintenance_status;

        return $this;
    }

    public function getIncidentSeverity(): ?string
    {
        return $this->incident_severity;
    }

    public function setIncidentSeverity(?string $incident_severity): self
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
     */
    public function setTitles(array $titles): self
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
     */
    public function setUpdates(array $updates): self
    {
        $this->updates = $updates;

        return $this;
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    public function setCreatedAt(string $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getArchiveAt(): ?string
    {
        return $this->archive_at;
    }

    public function setArchiveAt(?string $archive_at): self
    {
        $this->archive_at = $archive_at;

        return $this;
    }

    public function getUpdatedAt(): ?string
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?string $updated_at): self
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
     */
    public function setPlatforms(array $platforms): self
    {
        $this->platforms = $platforms;

        return $this;
    }
}
