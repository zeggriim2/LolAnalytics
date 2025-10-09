<?php

declare(strict_types=1);

namespace App\Match\Application\Query;

final class GetMatchByIdQuery
{
    public function __construct(public readonly string $id) {}
}
