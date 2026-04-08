<?php

declare(strict_types=1);

namespace App\Summoner\Infrastructure\Adapter;

use App\Match\Infrastructure\Persistence\Doctrine\Entity\ParticipantEntity;
use App\Summoner\Application\Port\SummonerMatchStatsProviderInterface;
use Doctrine\ORM\EntityManagerInterface;

final readonly class DoctrineMatchStatsAdapter implements SummonerMatchStatsProviderInterface
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function getAggregateStatsByPuuid(string $puuid): array
    {
        $aggregates = $this->em->createQueryBuilder()
            ->select([
                'COUNT(p.id) as totalGames',
                'AVG(p.kills) as avgKills',
                'AVG(p.deaths) as avgDeaths',
                'AVG(p.assists) as avgAssists',
                'AVG(ps.cs) as avgCs',
                'AVG(ps.goldEarned) as avgGold',
                'AVG(m.durationSeconds) as avgDuration',
            ])
            ->from(ParticipantEntity::class, 'p')
            ->join('p.stats', 'ps')
            ->join('p.match', 'm')
            ->where('p.summonerId = :puuid')
            ->setParameter('puuid', $puuid)
            ->getQuery()
            ->getSingleResult();

        $wins = (int) $this->em->createQueryBuilder()
            ->select('COUNT(p.id)')
            ->from(ParticipantEntity::class, 'p')
            ->where('p.summonerId = :puuid')
            ->andWhere('p.win = true')
            ->setParameter('puuid', $puuid)
            ->getQuery()
            ->getSingleScalarResult();

        $favoriteChampion = $this->em->createQueryBuilder()
            ->select('p.championId, COUNT(p.id) as cnt')
            ->from(ParticipantEntity::class, 'p')
            ->where('p.summonerId = :puuid')
            ->setParameter('puuid', $puuid)
            ->groupBy('p.championId')
            ->orderBy('cnt', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        $totalGames = (int) ($aggregates['totalGames'] ?? 0);
        $avgCs = round((float) ($aggregates['avgCs'] ?? 0), 1);
        $avgDurationSeconds = (int) round((float) ($aggregates['avgDuration'] ?? 0));
        $avgDurationMinutes = $avgDurationSeconds > 0 ? $avgDurationSeconds / 60 : 1;

        return [
            'totalGames' => $totalGames,
            'wins' => $wins,
            'losses' => $totalGames - $wins,
            'avgKills' => round((float) ($aggregates['avgKills'] ?? 0), 1),
            'avgDeaths' => round((float) ($aggregates['avgDeaths'] ?? 0), 1),
            'avgAssists' => round((float) ($aggregates['avgAssists'] ?? 0), 1),
            'avgCs' => $avgCs,
            'avgCsPerMin' => round($avgCs / $avgDurationMinutes, 1),
            'avgGold' => (int) round((float) ($aggregates['avgGold'] ?? 0)),
            'avgDurationSeconds' => $avgDurationSeconds,
            'favoriteChampionId' => $favoriteChampion ? $favoriteChampion['championId'] : null,
        ];
    }
}
