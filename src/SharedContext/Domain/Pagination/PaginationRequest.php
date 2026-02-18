<?php

declare(strict_types=1);

namespace App\SharedContext\Domain\Pagination;

final readonly class PaginationRequest
{
    private const int DEFAULT_PAGE = 1;
    private const int DEFAULT_LIMIT = 20;
    private const int MAX_LIMIT = 100;

    public int $page;
    public int $limit;

    public function __construct(int $page = self::DEFAULT_PAGE, int $limit = self::DEFAULT_LIMIT)
    {
        $this->page = max(1, $page);
        $this->limit = min(max(1, $limit), self::MAX_LIMIT);
    }

    public function offset(): int
    {
        return ($this->page - 1) * $this->limit;
    }
}
