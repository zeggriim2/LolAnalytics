<?php

declare(strict_types=1);

namespace App\Match\Application\Filter\Specification;

use App\Match\Application\Filter\MatchFilterSpecificationInterface;
use Doctrine\ORM\QueryBuilder;

final class GameModeSpecification implements MatchFilterSpecificationInterface
{
    public function __construct(private readonly string $gameMode)
    {
    }

    public function apply(QueryBuilder $qb): void
    {
        $qb->innerJoin('m.gameMode', 'gm')
            ->andWhere('gm.gameMode = :gameMode')
            ->setParameter('gameMode', $this->gameMode);
    }
}
