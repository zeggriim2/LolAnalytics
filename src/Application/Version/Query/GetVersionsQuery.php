<?php

declare(strict_types=1);

namespace App\Application\Version\Query;

final class GetVersionsQuery
{
    public function __construct(
        private readonly int $limit = 50,
        private readonly int $offset = 0,
        private readonly bool $onlyActive = false
    ) {
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function isOnlyActive(): bool
    {
        return $this->onlyActive;
    }
}
