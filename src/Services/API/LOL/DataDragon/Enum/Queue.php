<?php

declare(strict_types=1);

namespace App\Services\API\LOL\DataDragon\Enum;

class Queue
{
    public const RANKED_SOLO = 'RANKED_SOLO_5x5';
    public const RANKED_FLEX = 'RANKED_FLEX_SR';

    public const ALL_QEUEUES = [
        self::RANKED_SOLO,
        self::RANKED_FLEX,
    ];
}
