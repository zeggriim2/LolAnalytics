<?php

declare(strict_types=1);

namespace App\Tests\Unit\Match\Application\Filter\Specification;

use App\Match\Application\Filter\Specification\PlatformSpecification;
use Doctrine\ORM\QueryBuilder;
use PHPUnit\Framework\TestCase;

final class PlatformSpecificationTest extends TestCase
{
    public function testAppliesWhereClause(): void
    {
        $qb = $this->createMock(QueryBuilder::class);
        $qb->expects($this->once())
            ->method('andWhere')
            ->with('m.platform = :platform')
            ->willReturnSelf();

        $qb->expects($this->once())
            ->method('setParameter')
            ->with('platform', 'euw1')
            ->willReturnSelf();

        (new PlatformSpecification('euw1'))->apply($qb);
    }
}
