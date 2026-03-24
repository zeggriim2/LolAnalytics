<?php

declare(strict_types=1);

namespace App\Tests\Unit\Match\Application\Filter\Specification;

use App\Match\Application\Filter\Specification\GameModeSpecification;
use Doctrine\ORM\QueryBuilder;
use PHPUnit\Framework\TestCase;

final class GameModeSpecificationTest extends TestCase
{
    public function testAppliesJoinAndWhereClause(): void
    {
        $qb = $this->createMock(QueryBuilder::class);
        $qb->expects($this->once())
            ->method('innerJoin')
            ->with('m.gameMode', 'gm')
            ->willReturnSelf();

        $qb->expects($this->once())
            ->method('andWhere')
            ->with('gm.gameMode = :gameMode')
            ->willReturnSelf();

        $qb->expects($this->once())
            ->method('setParameter')
            ->with('gameMode', 'CLASSIC')
            ->willReturnSelf();

        (new GameModeSpecification('CLASSIC'))->apply($qb);
    }
}
