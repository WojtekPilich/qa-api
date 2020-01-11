<?php

namespace App\Repository;

use App\Entity\Answerer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Answerer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Answerer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Answerer[]    findAll()
 * @method Answerer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnswererRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Answerer::class);
    }

    // /**
    //  * @return Answerer[] Returns an array of Answerer objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Answerer
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
