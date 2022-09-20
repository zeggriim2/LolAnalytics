<?php

namespace App\Tests\Traits;

use App\Services\API\LOL\LeagueOfLegends\DTO\Status\ContentDto;
use App\Services\API\LOL\LeagueOfLegends\DTO\Status\PlatformDataDto;
use App\Services\API\LOL\LeagueOfLegends\DTO\Status\StatusDto;
use App\Services\API\LOL\LeagueOfLegends\DTO\Status\UpdateDto;

trait CheckStatusApi
{

    private function checkPlatformDataDto(PlatformDataDto $platformDataDto)
    {
        $this->assertIsString($platformDataDto->getId());
        $this->assertIsString($platformDataDto->getName());
        $this->assertIsArray($platformDataDto->getLocales());
        if (\count($platformDataDto->getMaintenances()) > 0) {
            foreach ($platformDataDto->getMaintenances() as $maintenance) {
                $this->assertInstanceOf(StatusDto::class, $maintenance);
            }
            $this->checkStatusDTO($platformDataDto->getMaintenances()[0]);
        }

        foreach ($platformDataDto->getIncidents() as $incident) {
            $this->assertInstanceOf(StatusDto::class, $incident);
        }
    }

    private function checkStatusDTO(StatusDto $statusDto)
    {
        $this->assertIsInt($statusDto->getId());
        $this->assertIsString($statusDto->getMaintenanceStatus());
        $this->assertIsString($statusDto->getCreatedAt());
        $this->assertIsArray($statusDto->getPlatforms());
        if (\is_string($statusDto->getIncidentSeverity())) {
            $this->assertIsString($statusDto->getIncidentSeverity());
        } else {
            $this->assertNull($statusDto->getIncidentSeverity());
        }

        if (\is_string($statusDto->getArchiveAt())) {
            $this->assertIsString($statusDto->getArchiveAt());
        } else {
            $this->assertNull($statusDto->getArchiveAt());
        }

        if (\is_string($statusDto->getUpdatedAt())) {
            $this->assertIsString($statusDto->getUpdatedAt());
        } else {
            $this->assertNull($statusDto->getUpdatedAt());
        }

        if ($statusDto->getTitles() > 0) {
            foreach ($statusDto->getTitles() as $title) {
                $this->assertInstanceOf(ContentDto::class, $title);
            }
            $this->checkContentDTO($statusDto->getTitles()[0]);
        }

        if ($statusDto->getUpdates() > 0) {
            foreach ($statusDto->getUpdates() as $update) {
                $this->assertInstanceOf(UpdateDto::class, $update);
            }
            $this->checkUpdateDTO($statusDto->getUpdates()[0]);
        }
    }

    private function checkContentDTO(ContentDto $contentDto)
    {
        $this->assertIsString($contentDto->getContent());
        $this->assertIsString($contentDto->getLocale());
    }

    private function checkUpdateDTO(UpdateDto $updateDto)
    {
        $this->assertIsInt($updateDto->getId());
        $this->assertIsString($updateDto->getAuthor());
        $this->assertIsBool($updateDto->isPublish());
        $this->assertIsArray($updateDto->getPublishLocations());
        $this->assertIsString($updateDto->getCreatedAt());
        $this->assertIsString($updateDto->getUpdatedAt());
        $this->assertIsArray($updateDto->getTranslations());
    }
}