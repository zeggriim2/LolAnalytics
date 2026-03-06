<?php

declare(strict_types=1);

namespace App\Match\Infrastructure\Adapter;

use App\Match\Application\Port\SummonerAdapterInterface;
use App\Summoner\Infrastructure\Persistence\Doctrine\Entity\SummonerEntity;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineSummonerAdapter implements SummonerAdapterInterface
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    /**
     * @param string[] $puuids
     *
     * @return array<string, string> map of puuid => gameName
     */
    public function findGameNamesByPuuids(array $puuids): array
    {
        if (empty($puuids)) {
            return [];
        }

        $results = $this->em->createQueryBuilder()
            ->select('s.puuid, s.gameName')
            ->from(SummonerEntity::class, 's')
            ->where('s.puuid IN (:puuids)')
            ->setParameter('puuids', $puuids)
            ->getQuery()
            ->getArrayResult();

        $map = [];

        foreach ($results as $row) {
            $map[$row['puuid']] = $row['gameName'];
        }

        return $map;
    }
}
