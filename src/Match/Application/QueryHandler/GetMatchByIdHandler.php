<?php

declare(strict_types=1);

namespace App\Match\Application\QueryHandler;

use App\Match\Application\Query\GetMatchByIdQuery;
use App\Match\Domain\Model\Matche;
use App\Match\Domain\Repository\MatchRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler('query.bus')]
final class GetMatchByIdHandler
{
    public function __construct(private MatchRepositoryInterface $repository)
    {
    }

    public function __invoke(GetMatchByIdQuery $query): ?Matche
    {
        return $this->repository->findById($query->id);
    }
}
