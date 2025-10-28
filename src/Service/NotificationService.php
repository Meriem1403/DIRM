<?php

namespace App\Service;

use App\Entity\Notification;
use App\Entity\RetourAction;
use App\Entity\User;
use App\Entity\Goudurix;
use Doctrine\ORM\EntityManagerInterface;

class NotificationService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function creerNotification(
        string $titre,
        string $message,
        string $type,
        User $destinataire,
        ?User $expediteur = null,
        ?RetourAction $retourAction = null,
        ?Goudurix $risque = null
    ): Notification {
        $notification = new Notification();
        $notification->setTitre($titre);
        $notification->setMessage($message);
        $notification->setType($type);
        $notification->setDestinataire($destinataire);
        $notification->setExpediteur($expediteur);
        $notification->setRetourAction($retourAction);
        $notification->setRisque($risque);

        $this->entityManager->persist($notification);
        $this->entityManager->flush();

        return $notification;
    }

    public function notifierRetourActionCree(RetourAction $retourAction): void
    {
        // Notifier les administrateurs
        $admins = $this->entityManager->getRepository(User::class)
            ->findBy(['role' => 'ROLE_ADMIN']);

        foreach ($admins as $admin) {
            $this->creerNotification(
                'Nouveau retour d\'action',
                sprintf(
                    'Le chef de service %s %s a fait un retour d\'action sur le risque "%s".',
                    $retourAction->getAuteur()->getPrenom(),
                    $retourAction->getAuteur()->getNom(),
                    $retourAction->getRisque()->getTitre()
                ),
                'retour_action',
                $admin,
                $retourAction->getAuteur(),
                $retourAction,
                $retourAction->getRisque()
            );
        }
    }

    public function notifierValidationRetourAction(RetourAction $retourAction, bool $valide): void
    {
        $statut = $valide ? 'validé' : 'refusé';
        $message = $valide 
            ? sprintf('Votre retour d\'action sur le risque "%s" a été validé.', $retourAction->getRisque()->getTitre())
            : sprintf('Votre retour d\'action sur le risque "%s" a été refusé.', $retourAction->getRisque()->getTitre());

        if ($retourAction->getCommentaireValidation()) {
            $message .= ' Commentaire : ' . $retourAction->getCommentaireValidation();
        }

        $this->creerNotification(
            sprintf('Retour d\'action %s', $statut),
            $message,
            'validation',
            $retourAction->getAuteur(),
            $retourAction->getValidateur(),
            $retourAction,
            $retourAction->getRisque()
        );
    }

    public function marquerCommeLu(Notification $notification): void
    {
        $notification->setLu(true);
        $this->entityManager->persist($notification);
        $this->entityManager->flush();
    }

    public function marquerToutesCommeLues(User $user): void
    {
        $notifications = $this->entityManager->getRepository(Notification::class)
            ->findNonLuesByDestinataire($user);

        foreach ($notifications as $notification) {
            $notification->setLu(true);
            $this->entityManager->persist($notification);
        }

        $this->entityManager->flush();
    }
}
