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
                'SUM(CASE WHEN p.win = true THEN 1 ELSE 0 END) as wins',
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
        $wins = (int) ($aggregates['wins'] ?? 0);
        $avgCs = round((float) ($aggregates['avgCs'] ?? 0), 1);
        $avgDurationSeconds = (int) round((float) ($aggregates['avgDuration'] ?? 0));
        $avgCsPerMin = $avgDurationSeconds > 0 ? round($avgCs / ($avgDurationSeconds / 60), 1) : 0.0;

        return [
            'totalGames' => $totalGames,
            'wins' => $wins,
            'losses' => $totalGames - $wins,
            'avgKills' => round((float) ($aggregates['avgKills'] ?? 0), 1),
            'avgDeaths' => round((float) ($aggregates['avgDeaths'] ?? 0), 1),
            'avgAssists' => round((float) ($aggregates['avgAssists'] ?? 0), 1),
            'avgCs' => $avgCs,
            'avgCsPerMin' => $avgCsPerMin,
            'avgGold' => (int) round((float) ($aggregates['avgGold'] ?? 0)),
            'avgDurationSeconds' => $avgDurationSeconds,
            'favoriteChampionId' => $favoriteChampion ? $favoriteChampion['championId'] : null,
        ];
    }

    public function getStatsByPositionByPuuid(string $puuid): array
    {
        $rows = $this->em->createQueryBuilder()
            ->select([
                'ps.individualPosition as position',
                'COUNT(p.id) as totalGames',
                'SUM(CASE WHEN p.win = true THEN 1 ELSE 0 END) as wins',
                'AVG(p.kills) as avgKills',
                'AVG(p.deaths) as avgDeaths',
                'AVG(p.assists) as avgAssists',
                'AVG(ps.cs) as avgCs',
            ])
            ->from(ParticipantEntity::class, 'p')
            ->join('p.stats', 'ps')
            ->where('p.summonerId = :puuid')
            ->andWhere('ps.individualPosition != :empty')
            ->setParameter('puuid', $puuid)
            ->setParameter('empty', '')
            ->groupBy('ps.individualPosition')
            ->orderBy('totalGames', 'DESC')
            ->getQuery()
            ->getArrayResult();

        return array_map(static fn (array $row): array => [
            'position' => $row['position'],
            'totalGames' => (int) $row['totalGames'],
            'wins' => (int) $row['wins'],
            'avgKills' => round((float) $row['avgKills'], 1),
            'avgDeaths' => round((float) $row['avgDeaths'], 1),
            'avgAssists' => round((float) $row['avgAssists'], 1),
            'avgCs' => round((float) $row['avgCs'], 1),
        ], $rows);
    }
}
