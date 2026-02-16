<?php

declare(strict_types=1);

namespace App\GameData\Domain;

enum GameDataType: string
{
    case Versions = 'versions';
    case Queues = 'queues';
    case Maps = 'maps';
    case GameModes = 'game-modes';
    case GameTypes = 'game-types';
    case Seasons = 'seasons';
}
