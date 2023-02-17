<?php

namespace App\Repository;

use App\Entity\Image;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Zeggriim\RiotApiDatadragon\Model\General\Image as ImageApi;

/**
 * @extends ServiceEntityRepository<Image>
 *
 * @method Image|null find($id, $lockMode = null, $lockVersion = null)
 * @method Image|null findOneBy(array $criteria, array $orderBy = null)
 * @method Image[]    findAll()
 * @method Image[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Image::class);
    }

    public function save(Image $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Image $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function checkDataExistWhithApi(ImageApi $imageApi): ?Image
    {
        return $this->createQueryBuilder('i')
            ->where('i.fullname = :fullName')
            ->andWhere('i.groupe = :groupe')
            ->andWhere('i.sprite = :sprite')
            ->andWhere('i.x = :x')
            ->andWhere('i.y = :y')
            ->andWhere('i.w = :w')
            ->andWhere('i.h = :h')
            ->setParameters([
                'fullName' => $imageApi->getFull(),
                'groupe' => $imageApi->getGroup(),
                'sprite' => $imageApi->getSprite(),
                'x' => $imageApi->getX(),
                'y' => $imageApi->getY(),
                'w' => $imageApi->getW(),
                'h' => $imageApi->getH(),
            ])
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

//    /**
//     * @return Image[] Returns an array of Image objects
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

//    public function findOneBySomeField($value): ?Image
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
