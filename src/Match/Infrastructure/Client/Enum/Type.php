<?php

namespace App\Match\Infrastructure\Client\Enum;

enum Type: string
{
    case RANKED = 'ranked';
    case NORMAL = 'normal';
    case TOURNEY = 'tourney';
    case TUTORIAL = 'tutorial';
}
