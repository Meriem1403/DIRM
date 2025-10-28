<?php

namespace App\Controller;

use App\Entity\Goudurix;
use App\Entity\Service;
use App\Repository\GoudurixRepository;
use App\Repository\ServiceRepository;
use App\Repository\NotificationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'dashboard')]
    #[IsGranted('ROLE_USER')]
    public function dashboard(
        GoudurixRepository $goudurixRepository,
        ServiceRepository $serviceRepository,
        NotificationRepository $notificationRepository
    ): Response {
        $user = $this->getUser();
        $userService = $user->getService();
        
        // Récupérer les risques du service de l'utilisateur avec une requête plus explicite
        $risquesService = [];
        if ($userService) {
            // Utiliser une requête DQL pour être sûr
            $risquesService = $goudurixRepository->createQueryBuilder('g')
                ->where('g.service = :service')
                ->setParameter('service', $userService)
                ->getQuery()
                ->getResult();
        }
        
        // Récupérer les notifications non lues
        $notificationsNonLues = $notificationRepository->countNonLuesByDestinataire($user);
        
        // Statistiques pour le service
        $statsService = [
            'total' => count($risquesService),
            'critiques' => count(array_filter($risquesService, fn($r) => $r->getNiveauRisque() === 'critique')),
            'eleves' => count(array_filter($risquesService, fn($r) => $r->getNiveauRisque() === 'élevé')),
            'en_cours' => count(array_filter($risquesService, fn($r) => $r->getStatut() === 'en_cours')),
            'traites' => count(array_filter($risquesService, fn($r) => $r->getStatut() === 'traité')),
        ];

        return $this->render('dashboard/user_dashboard.html.twig', [
            'user' => $user,
            'userService' => $userService,
            'risquesService' => $risquesService,
            'statsService' => $statsService,
            'notificationsNonLues' => $notificationsNonLues,
        ]);
    }

    #[Route('/mes-risques', name: 'mes_risques')]
    #[IsGranted('ROLE_USER')]
    public function mesRisques(GoudurixRepository $goudurixRepository): Response
    {
        $user = $this->getUser();
        $userService = $user->getService();
        
        $risques = [];
        if ($userService) {
            $risques = $goudurixRepository->createQueryBuilder('g')
                ->where('g.service = :service')
                ->setParameter('service', $userService)
                ->orderBy('g.createdAt', 'DESC')
                ->getQuery()
                ->getResult();
        }

        return $this->render('dashboard/mes_risques_simple.html.twig', [
            'risques' => $risques,
            'userService' => $userService,
        ]);
    }
}
