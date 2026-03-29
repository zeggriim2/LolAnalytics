<?php

declare(strict_types=1);

namespace App\Summoner\Domain\Enum;

enum TopLeagueTier: string
{
    case CHALLENGER = 'challenger';
    case GRANDMASTER = 'grandmaster';
    case MASTER = 'master';
}
