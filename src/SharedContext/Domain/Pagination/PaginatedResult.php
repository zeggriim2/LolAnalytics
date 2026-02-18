<?php

declare(strict_types=1);

namespace App\SharedContext\Domain\Pagination;

/**
 * @template T
 */
final readonly class PaginatedResult
{
    public int $totalPages;

    /**
     * @param T[] $items
     */
    public function __construct(
        public array $items,
        public int $total,
        public int $page,
        public int $limit,
    ) {
        $this->totalPages = $limit > 0 ? (int) ceil($total / $limit) : 0;
    }
}
