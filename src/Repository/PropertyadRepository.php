<?php

namespace App\Repository;

use App\Entity\Propertyad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Propertyad>
 *
 * @method Propertyad|null find($id, $lockMode = null, $lockVersion = null)
 * @method Propertyad|null findOneBy(array $criteria, array $orderBy = null)
 * @method Propertyad[]    findAll()
 * @method Propertyad[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PropertyadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Propertyad::class);
    }

//    /**
//     * @return Propertyad[] Returns an array of Propertyad objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Propertyad
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
