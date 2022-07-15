<?php

namespace App\Services\API\LOL\DTO;

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

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return PlatformDataDto
     */
    public function setId(string $id): PlatformDataDto
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return PlatformDataDto
     */
    public function setName(string $name): PlatformDataDto
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
     * @return PlatformDataDto
     */
    public function setLocales(array $locales): PlatformDataDto
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
     * @return PlatformDataDto
     */
    public function setMaintenances(array $maintenances): PlatformDataDto
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
     * @return PlatformDataDto
     */
    public function setIncidents(array $incidents): PlatformDataDto
    {
        $this->incidents = $incidents;
        return $this;
    }
}