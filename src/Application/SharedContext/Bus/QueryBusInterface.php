<?php

declare(strict_types=1);

namespace App\Application\SharedContext\Bus;

interface QueryBusInterface
{
    public function handle(object $query): mixed;

}
