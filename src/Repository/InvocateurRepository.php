<?php

namespace App\Repository;

use App\Entity\Invocateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Invocateur>
 *
 * @method Invocateur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Invocateur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Invocateur[]    findAll()
 * @method Invocateur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InvocateurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Invocateur::class);
    }

    public function add(Invocateur $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Invocateur $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @throws NonUniqueResultException
     *
     * @return Invocateur|null
     */
    public function findOrderByCreatedAt(string $summonerId)
    {
        return $this->createQueryBuilder('i')
            ->leftJoin('i.historiqueLeagues', 'h')
            ->andWhere('i.idLol= :summonerId')
            ->setParameter('summonerId', $summonerId)
            ->orderBy('h.createAt')
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

//    /**
//     * @return Invocateur[] Returns an array of Invocateur objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Invocateur
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
