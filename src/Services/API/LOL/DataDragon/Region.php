<?php

namespace App\Services\API\LOL\DataDragon;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

class Region extends AbstractEnumType
{
    private const URL = '.api.riotgames.com';

    private const AMERICAS = 'AMERICAS';
    private const ASIA = 'ASIA';
    private const EUROPE = 'EUROPE';
    private const SEA = 'SEA';

    public static array $choices = [
        self::AMERICAS => 'americas',
        self::ASIA => 'asia',
        self::EUROPE => 'europe',
        self::SEA => 'sea',
    ];
}
