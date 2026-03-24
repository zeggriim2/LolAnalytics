<?php

declare(strict_types=1);

namespace App\Tests\Unit\Match\Application\Filter\Specification;

use App\Match\Application\Filter\Specification\DateToSpecification;
use Doctrine\ORM\QueryBuilder;
use PHPUnit\Framework\TestCase;

final class DateToSpecificationTest extends TestCase
{
    public function testAppliesWhereClause(): void
    {
        $date = new \DateTimeImmutable('2024-12-31 23:59:59');

        $qb = $this->createMock(QueryBuilder::class);
        $qb->expects($this->once())
            ->method('andWhere')
            ->with('m.playedAt <= :dateTo')
            ->willReturnSelf();

        $qb->expects($this->once())
            ->method('setParameter')
            ->with('dateTo', $date)
            ->willReturnSelf();

        (new DateToSpecification($date))->apply($qb);
    }
}
