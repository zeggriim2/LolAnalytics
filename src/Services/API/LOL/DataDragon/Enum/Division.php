<?php

declare(strict_types=1);

namespace App\Services\API\LOL\DataDragon\Enum;

class Division
{
    public const DIVISION_I = 'I';
    public const DIVISION_II = 'II';
    public const DIVISION_III = 'III';
    public const DIVISION_IV = 'IV';
    public const ALL_DIVISION = [
        self::DIVISION_I,
        self::DIVISION_II,
        self::DIVISION_III,
        self::DIVISION_IV,
    ];
}
