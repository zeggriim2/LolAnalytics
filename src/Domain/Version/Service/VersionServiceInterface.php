<?php

declare(strict_types=1);

namespace App\Domain\Version\Service;

use App\Domain\Version\Model\Version;

interface VersionServiceInterface
{
    /**
     * @return Version[]
     */
    public function fetchVersionsFromApi(): array;
}
