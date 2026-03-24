<?php

declare(strict_types=1);

namespace App\Tests\Unit\Match\Application\Filter;

use App\Match\Application\Filter\MatchFilters;
use App\Match\Application\Filter\MatchFiltersFactory;
use PHPUnit\Framework\TestCase;

final class MatchFiltersFactoryTest extends TestCase
{
    public function testReturnsEmptyFiltersWhenAllParamsAreNull(): void
    {
        $filters = MatchFiltersFactory::fromQueryParams(null, null, null, null, null);

        $this->assertInstanceOf(MatchFilters::class, $filters);
        $this->assertTrue($filters->isEmpty());
    }

    public function testReturnsNonEmptyFiltersWhenPlatformIsSet(): void
    {
        $filters = MatchFiltersFactory::fromQueryParams(platform: 'euw1', gameMode: null, version: null, dateFrom: null, dateTo: null);

        $this->assertFalse($filters->isEmpty());
    }

    public function testReturnsNonEmptyFiltersWhenGameModeIsSet(): void
    {
        $filters = MatchFiltersFactory::fromQueryParams(platform: null, gameMode: 'CLASSIC', version: null, dateFrom: null, dateTo: null);

        $this->assertFalse($filters->isEmpty());
    }

    public function testReturnsNonEmptyFiltersWhenVersionIsSet(): void
    {
        $filters = MatchFiltersFactory::fromQueryParams(platform: null, gameMode: null, version: '14.6', dateFrom: null, dateTo: null);

        $this->assertFalse($filters->isEmpty());
    }

    public function testReturnsNonEmptyFiltersWhenDateFromIsSet(): void
    {
        $filters = MatchFiltersFactory::fromQueryParams(platform: null, gameMode: null, version: null, dateFrom: '2024-01-01', dateTo: null);

        $this->assertFalse($filters->isEmpty());
    }

    public function testReturnsNonEmptyFiltersWhenDateToIsSet(): void
    {
        $filters = MatchFiltersFactory::fromQueryParams(platform: null, gameMode: null, version: null, dateFrom: null, dateTo: '2024-12-31');

        $this->assertFalse($filters->isEmpty());
    }

    public function testReturnsNonEmptyFiltersWhenAllParamsAreSet(): void
    {
        $filters = MatchFiltersFactory::fromQueryParams('euw1', 'CLASSIC', '14.6', '2024-01-01', '2024-12-31');

        $this->assertFalse($filters->isEmpty());
    }
}
