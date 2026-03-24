<?php

declare(strict_types=1);

namespace App\Tests\Unit\Match\Application\Query;

use App\Match\Application\Filter\MatchFilters;
use App\Match\Application\Filter\MatchFilterSpecificationInterface;
use Doctrine\ORM\QueryBuilder;
use PHPUnit\Framework\TestCase;

final class MatchFiltersTest extends TestCase
{
    public function testIsEmptyWithNoSpecifications(): void
    {
        $this->assertTrue((new MatchFilters())->isEmpty());
    }

    public function testIsNotEmptyWithOneSpecification(): void
    {
        $spec = $this->createStub(MatchFilterSpecificationInterface::class);

        $this->assertFalse((new MatchFilters($spec))->isEmpty());
    }

    public function testIsNotEmptyWithMultipleSpecifications(): void
    {
        $spec1 = $this->createStub(MatchFilterSpecificationInterface::class);
        $spec2 = $this->createStub(MatchFilterSpecificationInterface::class);

        $this->assertFalse((new MatchFilters($spec1, $spec2))->isEmpty());
    }

    public function testApplyCallsEachSpecification(): void
    {
        $qb = $this->createStub(QueryBuilder::class);

        $spec1 = $this->createMock(MatchFilterSpecificationInterface::class);
        $spec1->expects($this->once())->method('apply')->with($qb);

        $spec2 = $this->createMock(MatchFilterSpecificationInterface::class);
        $spec2->expects($this->once())->method('apply')->with($qb);

        (new MatchFilters($spec1, $spec2))->apply($qb);
    }

    public function testApplyWithNoSpecificationsDoesNothing(): void
    {
        $qb = $this->createMock(QueryBuilder::class);
        $qb->expects($this->never())->method($this->anything());

        (new MatchFilters())->apply($qb);
    }
}
