<?php

namespace App\Controller\Admin;

use App\Entity\Notification;
use App\Repository\NotificationRepository;
use App\Service\NotificationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class NotificationController extends AbstractController
{
    private NotificationRepository $notificationRepository;
    private NotificationService $notificationService;

    public function __construct(NotificationRepository $notificationRepository, NotificationService $notificationService)
    {
        $this->notificationRepository = $notificationRepository;
        $this->notificationService = $notificationService;
    }

    #[Route('/notifications', name: 'notifications')]
    #[IsGranted('ROLE_USER')]
    public function index(): Response
    {
        $notifications = $this->notificationRepository->findByDestinataire($this->getUser());

        return $this->render('admin/notifications.html.twig', [
            'notifications' => $notifications,
        ]);
    }

    #[Route('/notifications/marquer-lu/{id}', name: 'notification_marquer_lu', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function marquerCommeLu(Notification $notification): JsonResponse
    {
        if ($notification->getDestinataire() !== $this->getUser()) {
            return new JsonResponse(['error' => 'Accès refusé'], 403);
        }

        $this->notificationService->marquerCommeLu($notification);

        return new JsonResponse(['success' => true]);
    }

    #[Route('/notifications/marquer-toutes-lues', name: 'notifications_marquer_toutes_lues', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function marquerToutesCommeLues(): JsonResponse
    {
        $this->notificationService->marquerToutesCommeLues($this->getUser());

        return new JsonResponse(['success' => true]);
    }

    #[Route('/notifications/count', name: 'notifications_count')]
    #[IsGranted('ROLE_USER')]
    public function count(): JsonResponse
    {
        $count = $this->notificationRepository->countNonLuesByDestinataire($this->getUser());

        return new JsonResponse(['count' => $count]);
    }
}
