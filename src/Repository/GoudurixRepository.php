<?php

namespace App\Repository;

use App\Entity\Goudurix;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Goudurix>
 */
class GoudurixRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Goudurix::class);
    }

    public function save(Goudurix $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Goudurix $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Trouve les risques par niveau
     */
    public function findByNiveauRisque(string $niveau): array
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.niveauRisque = :niveau')
            ->setParameter('niveau', $niveau)
            ->orderBy('g.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les risques par statut
     */
    public function findByStatut(string $statut): array
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.statut = :statut')
            ->setParameter('statut', $statut)
            ->orderBy('g.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les risques critiques non traités
     */
    public function findRisquesCritiquesNonTraites(): array
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.niveauRisque = :niveau')
            ->andWhere('g.statut != :statut')
            ->setParameter('niveau', 'critique')
            ->setParameter('statut', 'traité')
            ->orderBy('g.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les risques par service
     */
    public function findByService(int $serviceId): array
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.service = :serviceId')
            ->setParameter('serviceId', $serviceId)
            ->orderBy('g.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les risques par responsable
     */
    public function findByResponsable(int $userId): array
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.responsable = :userId')
            ->setParameter('userId', $userId)
            ->orderBy('g.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Compte les risques par niveau
     */
    public function countByNiveauRisque(): array
    {
        return $this->createQueryBuilder('g')
            ->select('g.niveauRisque, COUNT(g.id) as count')
            ->groupBy('g.niveauRisque')
            ->getQuery()
            ->getResult();
    }

    /**
     * Compte les risques par statut
     */
    public function countByStatut(): array
    {
        return $this->createQueryBuilder('g')
            ->select('g.statut, COUNT(g.id) as count')
            ->groupBy('g.statut')
            ->getQuery()
            ->getResult();
    }
}
