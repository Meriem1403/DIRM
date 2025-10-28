<?php

namespace App\Repository;

use App\Entity\Notification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Notification>
 */
class NotificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Notification::class);
    }

    public function findByDestinataire($destinataire): array
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.destinataire = :destinataire')
            ->setParameter('destinataire', $destinataire)
            ->orderBy('n.dateCreation', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findNonLuesByDestinataire($destinataire): array
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.destinataire = :destinataire')
            ->andWhere('n.lu = :lu')
            ->setParameter('destinataire', $destinataire)
            ->setParameter('lu', false)
            ->orderBy('n.dateCreation', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function countNonLuesByDestinataire($destinataire): int
    {
        return $this->createQueryBuilder('n')
            ->select('COUNT(n.id)')
            ->andWhere('n.destinataire = :destinataire')
            ->andWhere('n.lu = :lu')
            ->setParameter('destinataire', $destinataire)
            ->setParameter('lu', false)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
