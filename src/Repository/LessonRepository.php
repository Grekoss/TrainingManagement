<?php

namespace App\Repository;

use App\Entity\Lesson;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Lesson|null find($id, $lockMode = null, $lockVersion = null)
 * @method Lesson|null findOneBy(array $criteria, array $orderBy = null)
 * @method Lesson[]    findAll()
 * @method Lesson[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LessonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lesson::class);
    }

    /**
     * @return Lesson[] Retourne un tableau de leçons par catégorie
     */
    public function findByCategory($category)
    {
        return $this->createQueryBuilder('l')
            ->where('l.category = :category')
            ->setParameter('category', $category)
            ->orderBy('l.updatedAt', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }
}
