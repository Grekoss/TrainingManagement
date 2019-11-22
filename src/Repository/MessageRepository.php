<?php

namespace App\Repository;

use App\Entity\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    /**
     * @return Message[] Retourne un tableau de messages regroupant l'utilisateur qu'il soit le receveur ou l'envoyeur
     */
    public function messagesByUser($user)
    {
        return $this->createQueryBuilder('m')
            ->orWhere('m.received = :user')
            ->orWhere('m.sender = :user')
            ->orderBy('m.writeAt', 'DESC')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @return Message[] Retourne un tableau de messages regroupant deux utilisateurs
     */
    public function messagesForTwoUsers($mainUser, $secondaryUser)
    {
        return $this->createQueryBuilder('m')
            ->where('m.sender = :mainUser')
            ->andWhere('m.received = :secondaryUser')
            ->setParameter('mainUser', $mainUser)
            ->setParameter('secondaryUser', $secondaryUser)
            ->orderBy('m.writeAt', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @return Messages[] Retourn un tableau de messages regroupant tout les messages recu par le mainUser non lu (isRead = false)
     */
    public function allMessagesNotReadByUser($mainUser)
    {
        return $this->createQueryBuilder('m')
            ->where('m.received = :mainUser')
            ->andWhere('m.isRead = false')
            ->setParameter('mainUser', $mainUser)
            ->getQuery()
            ->getResult()
            ;
    }
}
