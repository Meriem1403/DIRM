<?php

namespace App\Controller;

use App\Entity\Service;
use App\Entity\Goudurix;
use App\Repository\ServiceRepository;
use App\Repository\GoudurixRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ServiceController extends AbstractController
{
    #[Route('/services', name: 'app_services')]
    public function index(ServiceRepository $serviceRepository): Response
    {
        $services = $serviceRepository->findAll();
        
        return $this->render('service/index.html.twig', [
            'services' => $services,
        ]);
    }

    #[Route('/service/{id}/risques', name: 'app_service_risques')]
    public function showRisques(Service $service, GoudurixRepository $goudurixRepository): Response
    {
        $risques = $goudurixRepository->findBy(['service' => $service], ['createdAt' => 'DESC']);
        
        return $this->render('service/risques.html.twig', [
            'service' => $service,
            'risques' => $risques,
        ]);
    }

    #[Route('/service/{id}/dashboard', name: 'app_service_dashboard')]
    public function dashboard(Service $service, GoudurixRepository $goudurixRepository): Response
    {
        $risques = $goudurixRepository->findBy(['service' => $service], ['createdAt' => 'DESC']);
        
        // Statistiques
        $stats = [
            'total' => count($risques),
            'en_cours' => count(array_filter($risques, fn($r) => $r->getStatut() === 'en_cours')),
            'traite' => count(array_filter($risques, fn($r) => $r->getStatut() === 'traité')),
            'surveille' => count(array_filter($risques, fn($r) => $r->getStatut() === 'surveillé')),
            'archive' => count(array_filter($risques, fn($r) => $r->getStatut() === 'archivé')),
            'critique' => count(array_filter($risques, fn($r) => $r->getNiveauRisque() === 'critique')),
            'eleve' => count(array_filter($risques, fn($r) => $r->getNiveauRisque() === 'élevé')),
            'moyen' => count(array_filter($risques, fn($r) => $r->getNiveauRisque() === 'moyen')),
            'faible' => count(array_filter($risques, fn($r) => $r->getNiveauRisque() === 'faible')),
        ];
        
        return $this->render('service/dashboard.html.twig', [
            'service' => $service,
            'risques' => $risques,
            'stats' => $stats,
        ]);
    }
}
