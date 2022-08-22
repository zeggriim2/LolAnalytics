<?php

namespace App\Services\API\LOL\DataDragon;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

class Queue extends AbstractEnumType
{
    public const RANKED_SOLO = 'RANKED_SOLO_5x5';
    public const RANKED_FLEX = 'RANKED_FLEX_SR';

    protected static array $choices = [
        self::RANKED_SOLO => self::RANKED_SOLO,
        self::RANKED_FLEX => self::RANKED_FLEX,
    ];

    public const REQUIREMENTS = self::RANKED_SOLO . '|' . self::RANKED_FLEX;

    public const ALL_QEUEUES = [
        self::RANKED_SOLO,
        self::RANKED_FLEX,
    ];
}
