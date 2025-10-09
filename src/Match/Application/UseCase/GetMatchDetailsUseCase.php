<?php

declare(strict_types=1);

namespace App\Match\Application\UseCase;

use App\Match\Application\Query\GetMatchByIdQuery;
use App\Match\Domain\Model\Matche;
use App\SharedContext\Application\Bus\QueryBusInterface;

final class GetMatchDetailsUseCase
{
    public function __construct(private readonly QueryBusInterface $queryBus) {}

    public function execute(string $id): ?Matche
    {
        return $this->queryBus->handle(new GetMatchByIdQuery($id));
    }
}
