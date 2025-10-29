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
use Symfony\Component\HttpFoundation\Request;

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
    public function mesRisques(
        GoudurixRepository $goudurixRepository,
        ServiceRepository $serviceRepository,
        \App\Repository\LieuRepository $lieuRepository,
        Request $request
    ): Response {
        $user = $this->getUser();
        $userService = $user->getService();
        $isAdmin = in_array('ROLE_ADMIN', $user->getRoles());
        
        // Récupérer les filtres depuis la requête
        $serviceParam = $request->query->get('service', '');
        $lieuParam = $request->query->get('lieu', '');
        $serviceId = !empty($serviceParam) && is_numeric($serviceParam) ? (int) $serviceParam : 0;
        $lieuId = !empty($lieuParam) && is_numeric($lieuParam) ? (int) $lieuParam : 0;
        
        // Déterminer les services disponibles
        if ($isAdmin) {
            // Admin : voir tous les services actifs
            $servicesDisponibles = $serviceRepository->findBy(['actif' => true], ['nom' => 'ASC']);
        } else {
            // Chef : seulement son service
            $servicesDisponibles = $userService ? [$userService] : [];
        }
        
        // Service sélectionné (ou service de l'utilisateur par défaut)
        $serviceSelectionne = null;
        if ($serviceId > 0 && $isAdmin) {
            $serviceSelectionne = $serviceRepository->find($serviceId);
        } elseif (!$isAdmin && $userService) {
            $serviceSelectionne = $userService;
        }
        
        // Lieux disponibles selon le service sélectionné
        $lieuxDisponibles = [];
        if ($serviceSelectionne) {
            $lieuxDisponibles = $serviceSelectionne->getLieux()->toArray();
        } elseif ($isAdmin) {
            // Si admin sans service sélectionné, tous les lieux
            $lieuxDisponibles = $lieuRepository->findAll();
        }
        
        // Lieu sélectionné
        $lieuSelectionne = null;
        if ($lieuId > 0) {
            $lieuSelectionne = $lieuRepository->find($lieuId);
        }
        
        // Construire la requête des risques
        $queryBuilder = $goudurixRepository->createQueryBuilder('g');
        
        if ($serviceSelectionne) {
            $queryBuilder->andWhere('g.service = :service')
                ->setParameter('service', $serviceSelectionne);
        } elseif (!$isAdmin && $userService) {
            $queryBuilder->andWhere('g.service = :service')
                ->setParameter('service', $userService);
        }
        
        if ($lieuSelectionne) {
            $queryBuilder->andWhere('g.lieu = :lieu')
                ->setParameter('lieu', $lieuSelectionne);
        }
        
        $risques = $queryBuilder
            ->orderBy('g.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
        
        // Organiser les risques par service et lieu
        $risquesParServiceLieu = [];
        foreach ($risques as $risque) {
            $serviceKey = $risque->getService() ? $risque->getService()->getId() : 0;
            $lieuKey = $risque->getLieu() ? $risque->getLieu()->getId() : 'sans-lieu';
            $lieuNom = $risque->getLieu() ? $risque->getLieu()->getNom() : 'Non spécifié';
            
            if (!isset($risquesParServiceLieu[$serviceKey])) {
                $risquesParServiceLieu[$serviceKey] = [
                    'service' => $risque->getService(),
                    'lieux' => []
                ];
            }
            
            if (!isset($risquesParServiceLieu[$serviceKey]['lieux'][$lieuKey])) {
                $risquesParServiceLieu[$serviceKey]['lieux'][$lieuKey] = [
                    'lieu' => $risque->getLieu(),
                    'lieuNom' => $lieuNom,
                    'risques' => []
                ];
            }
            
            $risquesParServiceLieu[$serviceKey]['lieux'][$lieuKey]['risques'][] = $risque;
        }

        return $this->render('dashboard/mes_risques_simple.html.twig', [
            'risques' => $risques,
            'risquesParServiceLieu' => $risquesParServiceLieu,
            'userService' => $userService,
            'isAdmin' => $isAdmin,
            'servicesDisponibles' => $servicesDisponibles,
            'lieuxDisponibles' => $lieuxDisponibles,
            'serviceSelectionne' => $serviceSelectionne,
            'lieuSelectionne' => $lieuSelectionne,
        ]);
    }

    #[Route('/api/services/{serviceId}/lieux', name: 'api_service_lieux', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function getLieuxByService(
        int $serviceId,
        ServiceRepository $serviceRepository
    ): Response {
        $service = $serviceRepository->find($serviceId);
        
        if (!$service) {
            return $this->json(['error' => 'Service non trouvé'], 404);
        }
        
        $lieux = $service->getLieux()->map(function($lieu) {
            return [
                'id' => $lieu->getId(),
                'nom' => $lieu->getNom()
            ];
        })->toArray();
        
        return $this->json(['lieux' => $lieux]);
    }
}
