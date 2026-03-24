<?php

declare(strict_types=1);

namespace App\Match\Application\Filter\Specification;

use App\Match\Application\Filter\MatchFilterSpecificationInterface;
use Doctrine\ORM\QueryBuilder;

final class PlatformSpecification implements MatchFilterSpecificationInterface
{
    public function __construct(private readonly string $platform)
    {
    }

    public function apply(QueryBuilder $qb): void
    {
        $qb->andWhere('m.platform = :platform')
            ->setParameter('platform', $this->platform);
    }
}
