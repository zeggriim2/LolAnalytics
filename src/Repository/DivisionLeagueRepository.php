<?php

namespace App\Repository;

use App\Entity\DivisionLeague;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DivisionLeague>
 *
 * @method DivisionLeague|null find($id, $lockMode = null, $lockVersion = null)
 * @method DivisionLeague|null findOneBy(array $criteria, array $orderBy = null)
 * @method DivisionLeague[]    findAll()
 * @method DivisionLeague[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DivisionLeagueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DivisionLeague::class);
    }

    public function save(DivisionLeague $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(DivisionLeague $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return DivisionLeague[] Returns an array of DivisionLeague objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DivisionLeague
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
