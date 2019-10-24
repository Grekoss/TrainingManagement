<?php

namespace App\Repository;

use App\Entity\User;
use App\Enum\FunctionEnum;
use App\Enum\RoleEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Liste des users qui sont suceptible de gérer un rush
     */
    public function userForManageRush()
    {
        return $this->createQueryBuilder('u')
            ->where('u.function = :zone')
            ->orWhere('u.function = :manager')
            ->orWhere('u.function = :deputy')
            ->setParameter('zone', FunctionEnum::ZONE_MANAGER)
            ->setParameter('manager', FunctionEnum::MANAGER)
            ->setParameter('deputy', FunctionEnum::DEPUTY_DIRECTOR)
            ->orderBy('u.firstName', 'ASC')
            ;
    }

    /**
     * Liste des teachers et store
     * Je fais appel a UserRepository car j'utilise cette fonction dans easy-admin et mon appel l'empeche d'utiliser THIS!
     */
    public function findTeachersEasyAdmin(UserRepository $users)
    {
        return $users->createQueryBuilder('u')
            ->where('u.role = :teacher')
            ->orWhere('u.role = :store')
            ->setParameter('teacher', RoleEnum::ROLE_TEACHER[0])
            ->setParameter('store', RoleEnum::ROLE_STORE[0])
            ->orderBy('u.firstName', 'ASC')
            ;
    }
}
