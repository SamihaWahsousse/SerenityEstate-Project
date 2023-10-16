<?php

namespace App\Repository;

use App\Entity\CityRegion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CityRegion>
 *
 * @method CityRegion|null find($id, $lockMode = null, $lockVersion = null)
 * @method CityRegion|null findOneBy(array $criteria, array $orderBy = null)
 * @method CityRegion[]    findAll()
 * @method CityRegion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CityRegionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CityRegion::class);
    }

//    /**
//     * @return CityRegion[] Returns an array of CityRegion objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CityRegion
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
