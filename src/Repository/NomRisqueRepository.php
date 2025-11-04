<?php

namespace App\Repository;

use App\Entity\NomRisque;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<NomRisque>
 */
class NomRisqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NomRisque::class);
    }

    public function save(NomRisque $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(NomRisque $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Trouve les noms de risques actifs
     */
    public function findActifs(): array
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.actif = :actif')
            ->setParameter('actif', true)
            ->orderBy('n.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }
}

