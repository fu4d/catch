<?php

namespace App\Repository;

use App\Entity\Geocode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Geocode|null find($id, $lockMode = null, $lockVersion = null)
 * @method Geocode|null findOneBy(array $criteria, array $orderBy = null)
 * @method Geocode[]    findAll()
 * @method Geocode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GeocodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Geocode::class);
    }

    // /**
    //  * @return Geocode[] Returns an array of Geocode objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Geocode
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
