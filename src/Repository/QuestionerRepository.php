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
}
