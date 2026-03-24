<?php

declare(strict_types=1);

namespace App\Match\Application\Filter;

use Doctrine\ORM\QueryBuilder;

final class MatchFilters
{
    /** @var MatchFilterSpecificationInterface[] */
    private array $specifications;

    public function __construct(MatchFilterSpecificationInterface ...$specifications)
    {
        $this->specifications = $specifications;
    }

    public function apply(QueryBuilder $qb): void
    {
        foreach ($this->specifications as $specification) {
            $specification->apply($qb);
        }
    }

    public function isEmpty(): bool
    {
        return [] === $this->specifications;
    }
}
