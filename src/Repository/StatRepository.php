<?php

namespace App\Repository;

use App\Entity\Stat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Zeggriim\RiotApiDatadragon\Model\Champion\Stats;

/**
 * @extends ServiceEntityRepository<Stat>
 *
 * @method Stat|null find($id, $lockMode = null, $lockVersion = null)
 * @method Stat|null findOneBy(array $criteria, array $orderBy = null)
 * @method Stat[]    findAll()
 * @method Stat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Stat::class);
    }

    public function save(Stat $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Stat $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function checkDataExistWhithApi(Stats $statsApi): ?Stat
    {
        return $this->createQueryBuilder('s')
            ->where('s.hp = :hp')
            ->andWhere('s.hpperlevel = :hpPerLevel')
            ->andWhere('s.mp = :mp')
            ->andWhere('s.mpperlevel = :mpPerLevel')
            ->andWhere('s.movespeed = :moveSpeed')
            ->andWhere('s.armor = :armor')
            ->andWhere('s.armorperlevel = :armorPerLevel')
            ->andWhere('s.spellblock = :spellBlock')
            ->andWhere('s.spellblockperlevel = :spellBlockPerLevel')
            ->andWhere('s.attackrange = :attackRange')
            ->andWhere('s.hpregen = :hpRegen')
            ->andWhere('s.hpregenperlevel = :hpRegenPerLevel')
            ->andWhere('s.mpregen = :mpRegen')
            ->andWhere('s.mpregenperlevel = :mpRegenPerLevel')
            ->andWhere('s.crit = :crit')
            ->andWhere('s.critperlevel = :critPerLevel')
            ->andWhere('s.attackdamage = :attackDamage')
            ->andWhere('s.attackdamageperlevel = :attackDamageLevel')
            ->andWhere('s.attackspeed = :attackSpeed')
            ->andWhere('s.attackspeedperlevel = :attackSpeedLevel')
            ->setParameters(
                [
                    'hp'                    => $statsApi->getHp(),
                    'hpPerLevel'            => $statsApi->getHpperlevel(),
                    'mp'                    => $statsApi->getMp(),
                    'mpPerLevel'            => $statsApi->getMpperlevel(),
                    'moveSpeed'             => $statsApi->getMovespeed(),
                    'armor'                 => $statsApi->getArmor(),
                    'armorPerLevel'         => $statsApi->getArmorperlevel(),
                    'spellBlock'            => $statsApi->getSpellblock(),
                    'spellBlockPerLevel'    => $statsApi->getSpellblockperlevel(),
                    'attackRange'           => $statsApi->getAttackrange(),
                    'hpRegen'               => $statsApi->getHpregen(),
                    'hpRegenPerLevel'       => $statsApi->getHpregenperlevel(),
                    'mpRegen'               => $statsApi->getMpregen(),
                    'mpRegenPerLevel'       => $statsApi->getMpregenperlevel(),
                    'crit'                  => $statsApi->getCrit(),
                    'critPerLevel'          => $statsApi->getCritperlevel(),
                    'attackDamage'          => $statsApi->getAttackdamage(),
                    'attackDamageLevel'     => $statsApi->getAttackdamageperlevel(),
                    'attackSpeed'           => $statsApi->getAttackspeed(),
                    'attackSpeedLevel'      => $statsApi->getAttackspeedperlevel(),
                ]
            )
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

//    /**
//     * @return Stat[] Returns an array of Stat objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Stat
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
