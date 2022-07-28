<?php

namespace App\Services\API\LOL\DataDragon;

class Region
{
    private const URL       = '.api.riotgames.com';

    private const AMERICAS  = 'americas' . self::URL;
    private const ASIA      = 'asia' . self::URL;
    private const EUROPE    = 'europe' . self::URL;
    private const SEA       = 'sea' . self::URL;

    public const ALL_REGION = [
        'AMERICAS'  => self::AMERICAS,
        'ASIA'      => self::ASIA,
        'EUROPE'    => self::EUROPE,
        'SEA'       => self::SEA,
    ];
}
