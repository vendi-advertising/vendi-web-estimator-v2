<?php

namespace App\Repository;

use App\Entity\HourRangeLineItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method HourRangeLineItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method HourRangeLineItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method HourRangeLineItem[]    findAll()
 * @method HourRangeLineItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HourRangeLineItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HourRangeLineItem::class);
    }

    // /**
    //  * @return HourRangeLineItem[] Returns an array of HourRangeLineItem objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?HourRangeLineItem
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
