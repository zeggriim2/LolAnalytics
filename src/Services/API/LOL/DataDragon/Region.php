<?php

namespace App\Services\API\LOL\DataDragon;

class Region
{
    private const AMERICAS  = "americas.api.riotgames.com";
    private const ASIA      = "asia.api.riotgames.com";
    private const EUROPE    = "europe.api.riotgames.com";
    private const SEA       = "sea.api.riotgames.com";

    const ALL_REGION = [
        "AMERICAS"  => self::AMERICAS,
        "ASIA"      => self::ASIA,
        "EUROPE"    => self::EUROPE,
        "SEA"       => self::SEA,
    ];
}