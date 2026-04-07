<?php

declare(strict_types=1);

namespace App\League\Domain\Enum;

enum LeagueTier: string
{
    case CHALLENGER = 'challenger';
    case GRANDMASTER = 'grandmaster';
    case MASTER = 'master';
}
