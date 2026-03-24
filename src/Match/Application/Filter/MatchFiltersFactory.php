<?php

declare(strict_types=1);

namespace App\Match\Application\Filter;

use App\Match\Application\Filter\Specification\DateFromSpecification;
use App\Match\Application\Filter\Specification\DateToSpecification;
use App\Match\Application\Filter\Specification\GameModeSpecification;
use App\Match\Application\Filter\Specification\PlatformSpecification;
use App\Match\Application\Filter\Specification\VersionSpecification;

final class MatchFiltersFactory
{
    public static function fromQueryParams(
        ?string $platform,
        ?string $gameMode,
        ?string $version,
        ?string $dateFrom,
        ?string $dateTo,
    ): MatchFilters {
        $specifications = [];

        if (null !== $platform) {
            $specifications[] = new PlatformSpecification($platform);
        }

        if (null !== $gameMode) {
            $specifications[] = new GameModeSpecification($gameMode);
        }

        if (null !== $version) {
            $specifications[] = new VersionSpecification($version);
        }

        if (null !== $dateFrom) {
            $specifications[] = new DateFromSpecification(new \DateTimeImmutable($dateFrom . ' 00:00:00'));
        }

        if (null !== $dateTo) {
            $specifications[] = new DateToSpecification(new \DateTimeImmutable($dateTo . ' 23:59:59'));
        }

        return new MatchFilters(...$specifications);
    }
}
