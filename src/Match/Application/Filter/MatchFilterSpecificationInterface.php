<?php

declare(strict_types=1);

namespace App\Match\Application\Filter;

use Doctrine\ORM\QueryBuilder;

interface MatchFilterSpecificationInterface
{
    public function apply(QueryBuilder $qb): void;
}
