<?php

declare(strict_types=1);

namespace App\Services\API\LOL\LeagueOfLegends\DTO\Status;

class PlatformDataDto
{
    private string $id;
    private string $name;
    /**
     * @var array<string>
     */
    private array $locales;
    /**
     * @var StatusDto[]
     */
    private array $maintenances;
    /**
     * @var StatusDto[]
     */
    private array $incidents;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

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

    /**
     * @return string[]
     */
    public function getLocales(): array
    {
        return $this->locales;
    }

    /**
     * @param string[] $locales
     */
    public function setLocales(array $locales): self
    {
        $this->locales = $locales;

        return $this;
    }

    /**
     * @return StatusDto[]
     */
    public function getMaintenances(): array
    {
        return $this->maintenances;
    }

    /**
     * @param StatusDto[] $maintenances
     */
    public function setMaintenances(array $maintenances): self
    {
        $this->maintenances = $maintenances;

        return $this;
    }

    /**
     * @return StatusDto[]
     */
    public function getIncidents(): array
    {
        return $this->incidents;
    }

    /**
     * @param StatusDto[] $incidents
     */
    public function setIncidents(array $incidents): self
    {
        $this->incidents = $incidents;

        return $this;
    }
}
