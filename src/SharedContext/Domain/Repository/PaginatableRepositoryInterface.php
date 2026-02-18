<?php

declare(strict_types=1);

namespace App\SharedContext\Domain\Repository;

interface PaginatableRepositoryInterface
{
    /**
     * @return array<mixed>
     */
    public function findPaginated(int $offset, int $limit): array;

    public function count(): int;
}
