<?php

namespace App\Services\API\LOL\DataDragon;

class Platform
{
    private const URL   = '.api.riotgames.com';

    private const BR1   = 'br1' . self::URL;
    private const EUN1  = 'eun1' . self::URL;
    private const EUW1  = 'euw1' . self::URL;
    private const JP1   = 'jp1' . self::URL;
    private const KR    = 'kr' . self::URL;
    private const LA1   = 'la1' . self::URL;
    private const LA2   = 'la2' . self::URL;
    private const NA1   = 'na1' . self::URL;
    private const OC1   = 'oc1' . self::URL;
    private const TR1   = 'tr1' . self::URL;
    private const RU    = 'ru' . self::URL;

    public const ALL_PLATFORM = [
        'BR1'   => self::BR1,
        'EUN1'  => self::EUN1,
        'EUW1'  => self::EUW1,
        'JP1'   => self::JP1,
        'KR'    => self::KR,
        'LA1'   => self::LA1,
        'LA2'   => self::LA2,
        'NA1'   => self::NA1,
        'OC1'   => self::OC1,
        'TR1'   => self::TR1,
        'RU'    => self::RU,
    ];
}
