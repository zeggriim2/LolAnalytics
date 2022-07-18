<?php

namespace App\Services\API\LOL\DataDragon;

class Platform
{
    private const BR1   = "br1.api.riotgames.com";
    private const EUN1  = "eun1.api.riotgames.com";
    private const EUW1  = "euw1.api.riotgames.com";
    private const JP1   = "jp1.api.riotgames.com";
    private const KR    = "kr.api.riotgames.com";
    private const LA1   = "la1.api.riotgames.com";
    private const LA2   = "la2.api.riotgames.com";
    private const NA1   = "na1.api.riotgames.com";
    private const OC1   = "oc1.api.riotgames.com";
    private const TR1   = "tr1.api.riotgames.com";
    private const RU    = "ru.api.riotgames.com";

    const ALL_PLATFORM = [
        "BR1"   => self::BR1,
        "EUN1"  => self::EUN1,
        "EUW1"  => self::EUW1,
        "JP1"   => self::JP1,
        "KR"    => self::KR,
        "LA1"   => self::LA1,
        "LA2"   => self::LA2,
        "NA1"   => self::NA1,
        "OC1"   => self::OC1,
        "TR1"   => self::TR1,
        "RU"    => self::RU,
    ];
}