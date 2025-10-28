<?php

namespace App\Repository;

use App\Entity\RetourAction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RetourAction>
 */
class RetourActionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RetourAction::class);
    }

    public function findByRisque($risque): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.risque = :risque')
            ->setParameter('risque', $risque)
            ->orderBy('r.dateCreation', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findEnAttente(): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.statut = :statut')
            ->setParameter('statut', 'en_attente')
            ->orderBy('r.dateCreation', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findByAuteur($auteur): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.auteur = :auteur')
            ->setParameter('auteur', $auteur)
            ->orderBy('r.dateCreation', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
