<?php

declare(strict_types=1);

namespace App\Tests\Unit\Match\Application\Filter\Specification;

use App\Match\Application\Filter\Specification\DateFromSpecification;
use Doctrine\ORM\QueryBuilder;
use PHPUnit\Framework\TestCase;

final class DateFromSpecificationTest extends TestCase
{
    public function testAppliesWhereClause(): void
    {
        $date = new \DateTimeImmutable('2024-01-01 00:00:00');

        $qb = $this->createMock(QueryBuilder::class);
        $qb->expects($this->once())
            ->method('andWhere')
            ->with('m.playedAt >= :dateFrom')
            ->willReturnSelf();

        $qb->expects($this->once())
            ->method('setParameter')
            ->with('dateFrom', $date)
            ->willReturnSelf();

        (new DateFromSpecification($date))->apply($qb);
    }
}
