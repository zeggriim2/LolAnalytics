<?php

namespace App\Services\API\LOL\DataDragon;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

class Division extends AbstractEnumType
{
    public const DIVISION_I     = 'I';
    public const DIVISION_II    = 'II';
    public const DIVISION_III   = 'III';
    public const DIVISION_IV    = 'IV';

    public static array $choices = [
        self::DIVISION_I    => self::DIVISION_I,
        self::DIVISION_II   => self::DIVISION_II,
        self::DIVISION_III  => self::DIVISION_III,
        self::DIVISION_IV   => self::DIVISION_IV,
    ];

    public const ALL_DIVISION = [
        self::DIVISION_I,
        self::DIVISION_II,
        self::DIVISION_III,
        self::DIVISION_IV,
    ];
}
