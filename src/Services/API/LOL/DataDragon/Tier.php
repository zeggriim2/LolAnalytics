<?php

namespace App\Services\API\LOL\DataDragon;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

class Tier extends AbstractEnumType
{
    public const TIER_CHALLENGER = 'CHALLENGER';
    public const TIER_GRANDMASTER = 'GRANDMASTER';
    public const TIER_MASTER = 'MASTER';
    public const TIER_DIAMOND = 'DIAMOND';
    public const TIER_PLATINUM = 'PLATINUM';
    public const TIER_GOLD = 'GOLD';
    public const TIER_SILVER = 'SILVER';
    public const TIER_BRONZE = 'BRONZE';
    public const TIER_IRON = 'IRON';

    public static array $choices = [
        self::TIER_CHALLENGER => self::TIER_CHALLENGER,
        self::TIER_GRANDMASTER => self::TIER_GRANDMASTER,
        self::TIER_MASTER => self::TIER_MASTER,
        self::TIER_DIAMOND => self::TIER_DIAMOND,
        self::TIER_PLATINUM => self::TIER_PLATINUM,
        self::TIER_GOLD => self::TIER_GOLD,
        self::TIER_SILVER => self::TIER_SILVER,
        self::TIER_BRONZE => self::TIER_BRONZE,
        self::TIER_IRON => self::TIER_IRON,
    ];

    public const REQUIREMENTS = self::TIER_CHALLENGER . '|' . self::TIER_GRANDMASTER . '|'
. self::TIER_MASTER . '|' . self::TIER_DIAMOND . '|' . self::TIER_PLATINUM . '|' . self::TIER_GOLD . '|' . self::TIER_SILVER . '|'
. self::TIER_BRONZE . '|' . self::TIER_IRON;

    public const ALL_TIERS = [
        self::TIER_CHALLENGER,
        self::TIER_GRANDMASTER,
        self::TIER_MASTER,
        self::TIER_DIAMOND,
        self::TIER_PLATINUM,
        self::TIER_GOLD,
        self::TIER_SILVER,
        self::TIER_BRONZE,
        self::TIER_IRON,
    ];
}
