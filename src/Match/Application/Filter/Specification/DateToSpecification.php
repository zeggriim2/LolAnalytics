<?php

declare(strict_types=1);

namespace App\Match\Application\Filter\Specification;

use App\Match\Application\Filter\MatchFilterSpecificationInterface;
use Doctrine\ORM\QueryBuilder;

final class DateToSpecification implements MatchFilterSpecificationInterface
{
    public function __construct(private readonly \DateTimeImmutable $dateTo)
    {
    }

    public function apply(QueryBuilder $qb): void
    {
        $qb->andWhere('m.playedAt <= :dateTo')
            ->setParameter('dateTo', $this->dateTo);
    }
}
