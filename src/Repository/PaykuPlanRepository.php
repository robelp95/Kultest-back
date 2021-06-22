<?php

namespace App\Repository;

use App\Entity\PaykuPlan;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PaykuPlan|null find($id, $lockMode = null, $lockVersion = null)
 * @method PaykuPlan|null findOneBy(array $criteria, array $orderBy = null)
 * @method PaykuPlan[]    findAll()
 * @method PaykuPlan[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaykuPlanRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PaykuPlan::class);
    }

    // /**
    //  * @return PaykuPlan[] Returns an array of PaykuPlan objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PaykuPlan
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
