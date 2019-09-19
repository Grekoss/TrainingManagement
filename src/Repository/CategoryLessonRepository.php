<?php

namespace App\Repository;

use App\Entity\CategoryLesson;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method CategoryLesson|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoryLesson|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoryLesson[]    findAll()
 * @method CategoryLesson[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryLessonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoryLesson::class);
    }

    // /**
    //  * @return CategoryLesson[] Returns an array of CategoryLesson objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CategoryLesson
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
