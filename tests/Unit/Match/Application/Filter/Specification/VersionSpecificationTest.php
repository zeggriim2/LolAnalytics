<?php

declare(strict_types=1);

namespace App\Tests\Unit\Match\Application\Filter\Specification;

use App\Match\Application\Filter\Specification\VersionSpecification;
use Doctrine\ORM\QueryBuilder;
use PHPUnit\Framework\TestCase;

final class VersionSpecificationTest extends TestCase
{
    public function testAppliesJoinAndLikeClause(): void
    {
        $qb = $this->createMock(QueryBuilder::class);
        $qb->expects($this->once())
            ->method('innerJoin')
            ->with('m.version', 'v')
            ->willReturnSelf();

        $qb->expects($this->once())
            ->method('andWhere')
            ->with('v.version LIKE :version')
            ->willReturnSelf();

        $qb->expects($this->once())
            ->method('setParameter')
            ->with('version', '14.6%')
            ->willReturnSelf();

        (new VersionSpecification('14.6'))->apply($qb);
    }
}
