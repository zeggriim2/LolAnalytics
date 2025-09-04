<?php

declare(strict_types=1);

namespace App\Application\Version\Query;

final class GetVersionQuery
{
    public function __construct(
        private readonly int $id
    ) {}

    public function getId(): int
    {
        return $this->id;
    }
}
