<?php

namespace App\Repository;

use App\Entity\OrderVia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OrderVia|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderVia|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderVia[]    findAll()
 * @method OrderVia[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderViaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderVia::class);
    }

    // /**
    //  * @return OrderVia[] Returns an array of OrderVia objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OrderVia
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
