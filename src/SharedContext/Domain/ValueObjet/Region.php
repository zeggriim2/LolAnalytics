<?php

declare(strict_types=1);

namespace App\SharedContext\Domain\ValueObjet;

enum Region: string
{
    case AMERICAS = 'americas';
    case ASIA = 'asia';
    case EUROPE = 'europe';
    case SEA = 'sea';
}
