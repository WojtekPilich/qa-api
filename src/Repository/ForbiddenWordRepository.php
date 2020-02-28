<?php

namespace App\Repository;

use App\Entity\ForbiddenWord;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ForbiddenWord|null find($id, $lockMode = null, $lockVersion = null)
 * @method ForbiddenWord|null findOneBy(array $criteria, array $orderBy = null)
 * @method ForbiddenWord[]    findAll()
 * @method ForbiddenWord[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ForbiddenWordRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ForbiddenWord::class);
    }
}
