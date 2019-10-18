<?php

namespace App\Repository;

use App\Entity\Result;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Result|null find($id, $lockMode = null, $lockVersion = null)
 * @method Result|null findOneBy(array $criteria, array $orderBy = null)
 * @method Result[]    findAll()
 * @method Result[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResultRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Result::class);
    }

    /**
     * @return Result[] Retourne un tableau d'objet de résultat en fonction de l'utilisateur et du questionnaire
     */
    public function findByQuizAndUser($quiz, $user)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.quiz = :quiz')
            ->andWhere('r.student = :student')
            ->setParameter('quiz', $quiz)
            ->setParameter('student', $user)
            ->orderBy('r.dateAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Result[] Retourne un tableau d'objet de résultat en fonction de l'utilisateur
     */
    public function findByUser($user)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.student = :student')
            ->setParameter('student', $user)
            ->orderBy('r.dateAt', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @return Result[] Retoune un tableau d'objet de résultat en fonction du questionnaire
     */
    public function findByQuiz($quiz)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.quiz = :quiz')
            ->setParameter('quiz', $quiz)
            ->orderBy('r.dateAt', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }
}
