<?php

declare(strict_types=1);

namespace App\SharedContext\Application\Bus;

interface QueryBusInterface
{
    public function handle(object $query): mixed;
}
