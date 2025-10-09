<?php

declare(strict_types=1);

namespace App\Match\Application\UseCase;

use App\Match\Application\Query\ListMatchesQuery;
use App\SharedContext\Application\Bus\QueryBusInterface;

final class ListMatchesUseCase
{
    public function __construct(private readonly QueryBusInterface $queryBus)
    {
    }

    public function execute(): array
    {
        return $this->queryBus->handle(new ListMatchesQuery());
    }
}
