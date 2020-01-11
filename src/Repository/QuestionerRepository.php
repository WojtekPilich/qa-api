<?php

namespace App\Repository;

use App\Entity\Questioner;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Questioner|null find($id, $lockMode = null, $lockVersion = null)
 * @method Questioner|null findOneBy(array $criteria, array $orderBy = null)
 * @method Questioner[]    findAll()
 * @method Questioner[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Questioner::class);
    }

    // /**
    //  * @return Questioner[] Returns an array of Questioner objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('q.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Questioner
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
