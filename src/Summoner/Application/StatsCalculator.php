<?php

declare(strict_types=1);

namespace App\Summoner\Application;

final class StatsCalculator
{
    public static function winRate(int $wins, int $totalGames): float
    {
        return $totalGames > 0
            ? round($wins / $totalGames * 100, 1)
            : 0.0;
    }

    public static function avgKda(float $kills, float $deaths, float $assists): float
    {
        return $deaths > 0
            ? round(($kills + $assists) / $deaths, 2)
            : round($kills + $assists, 2);
    }
}
