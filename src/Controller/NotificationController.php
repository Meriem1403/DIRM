<?php

namespace App\Controller;

use App\Entity\Notification;
use App\Repository\NotificationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/notifications')]
#[IsGranted('ROLE_USER')]
class NotificationController extends AbstractController
{
    #[Route('/', name: 'notifications')]
    public function index(NotificationRepository $notificationRepository): Response
    {
        $notifications = $notificationRepository->findBy(
            ['destinataire' => $this->getUser()],
            ['dateCreation' => 'DESC']
        );

        return $this->render('notifications/index.html.twig', [
            'notifications' => $notifications,
        ]);
    }

    #[Route('/{id}/marquer-lu', name: 'notification_marquer_lu', methods: ['POST'])]
    public function marquerLu(Notification $notification, EntityManagerInterface $entityManager): Response
    {
        if ($notification->getDestinataire() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $notification->setLu(true);
        $entityManager->flush();

        $this->addFlash('success', 'Notification marquée comme lue.');
        return $this->redirectToRoute('notifications');
    }

    #[Route('/{id}/ouvrir-risque', name: 'notification_ouvrir_risque', methods: ['GET'])]
    public function ouvrirRisque(Notification $notification, EntityManagerInterface $entityManager): Response
    {
        if ($notification->getDestinataire() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        // Marquer la notification comme lue si elle ne l'est pas déjà
        if (!$notification->isLu()) {
            $notification->setLu(true);
            $entityManager->flush();
        }

        // Rediriger vers la page du risque
        if ($notification->getRisque()) {
            return $this->redirectToRoute('risque_detail', ['id' => $notification->getRisque()->getId()]);
        }

        return $this->redirectToRoute('notifications');
    }

    #[Route('/marquer-toutes-lues', name: 'notifications_marquer_toutes_lues', methods: ['POST'])]
    public function marquerToutesLues(NotificationRepository $notificationRepository, EntityManagerInterface $entityManager): Response
    {
        $notifications = $notificationRepository->findBy([
            'destinataire' => $this->getUser(),
            'lu' => false
        ]);

        $nbMarquees = 0;
        foreach ($notifications as $notification) {
            $notification->setLu(true);
            $nbMarquees++;
        }

        $entityManager->flush();

        if ($nbMarquees > 0) {
            $this->addFlash('success', $nbMarquees . ' notification(s) marquée(s) comme lue(s).');
        } else {
            $this->addFlash('info', 'Aucune notification non lue à marquer.');
        }
        
        return $this->redirectToRoute('notifications');
    }
}
