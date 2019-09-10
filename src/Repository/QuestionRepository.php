<?php

namespace App\Repository;

use App\Entity\Question;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Question|null find($id, $lockMode = null, $lockVersion = null)
 * @method Question|null findOneBy(array $criteria, array $orderBy = null)
 * @method Question[]    findAll()
 * @method Question[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Question::class);
    }

    /**
     * @param $quiz Questionnaire dont l'on cherche toute les questions
     * @return Questions[] Retourne un tableau d'objet des questions
     */
    public function findByQuiz($quiz)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.quiz = :quiz')
            ->setParameter('quiz', $quiz)
            ->orderBy('q.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
