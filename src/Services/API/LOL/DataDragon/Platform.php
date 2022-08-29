<?php

namespace App\Services\API\LOL\DataDragon;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

class Platform extends AbstractEnumType
{
    public const BR1 = 'BR1';
    public const EUN1 = 'EUN1';
    public const EUW1 = 'EUW1';
    public const JP1 = 'JP1';
    public const KR = 'KR';
    public const LA1 = 'LA1';
    public const LA2 = 'LA2';
    public const NA1 = 'NA1';
    public const OC1 = 'OC1';
    public const TR1 = 'TR1';
    public const RU = 'RU';

    public static array $choices = [
        self::BR1 => 'br1',
        self::EUN1 => 'eun1',
        self::EUW1 => 'euw1',
        self::JP1 => 'jp1',
        self::KR => 'kr',
        self::LA1 => 'la1',
        self::LA2 => 'la2',
        self::NA1 => 'na1',
        self::OC1 => 'oc1',
        self::TR1 => 'tr1',
        self::RU => 'ru',
    ];
}
