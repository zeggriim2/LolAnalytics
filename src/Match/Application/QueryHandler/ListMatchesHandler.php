<?php

declare(strict_types=1);

namespace App\Match\Application\QueryHandler;

use App\Match\Application\Query\ListMatchesQuery;
use App\Match\Domain\Model\Matche;
use App\Match\Domain\Repository\MatchRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler('query.bus')]
final class ListMatchesHandler
{
    public function __construct(private MatchRepositoryInterface $repository)
    {
    }

    /**
     * @return Matche[]
     */
    public function __invoke(ListMatchesQuery $query): array
    {
        return $this->repository->findAll();
    }
}
