<?php

namespace App\Repository;

use App\Entity\Report;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Report|null find($id, $lockMode = null, $lockVersion = null)
 * @method Report|null findOneBy(array $criteria, array $orderBy = null)
 * @method Report[]    findAll()
 * @method Report[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Report::class);
    }

    /**
     * @return Report[] Retourne la liste des rapports par users
     */
    public function findByUser($user)
    {
        return $this->createQueryBuilder('r')
            ->where('r.student = :user')
            ->setParameter('user', $user)
            ->orderBy('r.dateAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Report[} Retourn la liste des rapports ou les étudiants sont actifs
     */
    public function findAllByUsersActive()
    {
        return $this->createQueryBuilder('r')
            ->innerJoin('r.student', 'u', 'WITH', 'u.isActive = true')
            ->orderBy('r.student', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
}
