<?php

declare(strict_types=1);

namespace App\Match\Application\Filter\Specification;

use App\Match\Application\Filter\MatchFilterSpecificationInterface;
use Doctrine\ORM\QueryBuilder;

final class VersionSpecification implements MatchFilterSpecificationInterface
{
    public function __construct(private readonly string $version)
    {
    }

    public function apply(QueryBuilder $qb): void
    {
        $qb->innerJoin('m.version', 'v')
            ->andWhere('v.version LIKE :version')
            ->setParameter('version', $this->version . '%');
    }
}
