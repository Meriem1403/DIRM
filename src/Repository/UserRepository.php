<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    //    /**
    //     * @return User[] Returns an array of User objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    public function findByRole(string $role): array
    {
        // Les rôles sont stockés en JSON dans Symfony
        // On récupère tous les utilisateurs et on filtre en PHP pour être compatible avec toutes les BDD
        $allUsers = $this->findAll();
        $usersWithRole = [];
        
        foreach ($allUsers as $user) {
            $roles = $user->getRoles();
            if (in_array($role, $roles, true)) {
                $usersWithRole[] = $user;
            }
        }
        
        return $usersWithRole;
    }
}
