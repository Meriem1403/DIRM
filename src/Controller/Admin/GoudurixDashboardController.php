<?php

namespace App\Controller\Admin;

use App\Entity\Service;
use App\Entity\Goudurix;
use App\Repository\ServiceRepository;
use App\Repository\GoudurixRepository;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GoudurixDashboardController extends AbstractController
{
    public function __construct(
        private readonly AdminUrlGenerator $adminUrlGenerator
    ) {
    }

    #[Route('/goudurix-dashboard', name: 'goudurix_dashboard')]
    public function dashboard(ServiceRepository $serviceRepository, GoudurixRepository $goudurixRepository): Response
    {
        $services = $serviceRepository->findBy(['actif' => true], ['nom' => 'ASC']);
        
        // Charger les risques pour chaque service
        $servicesWithRisques = [];
        foreach ($services as $service) {
            $risques = $goudurixRepository->findBy(['service' => $service]);
            
            // Générer les URLs avec AdminUrlGenerator
            $voirUrl = $this->adminUrlGenerator
                ->setController(GoudurixCrudController::class)
                ->setAction('index')
                ->set('filters[service]', $service->getId())
                ->generateUrl();
            
            $ajouterUrl = $this->adminUrlGenerator
                ->setController(GoudurixCrudController::class)
                ->setAction('new')
                ->set('filters[service]', $service->getId())
                ->generateUrl();
            
            $servicesWithRisques[] = [
                'service' => $service,
                'risques' => $risques,
                'voirUrl' => $voirUrl,
                'ajouterUrl' => $ajouterUrl
            ];
        }
        
        // URLs pour les boutons de navigation
        $retourUrl = $this->adminUrlGenerator
            ->setController(GoudurixCrudController::class)
            ->setAction('index')
            ->generateUrl();
        
        $ajouterUrl = $this->adminUrlGenerator
            ->setController(GoudurixCrudController::class)
            ->setAction('new')
            ->generateUrl();
        
        return $this->render('admin/goudurix_dashboard.html.twig', [
            'services' => $servicesWithRisques,
            'retourUrl' => $retourUrl,
            'ajouterUrl' => $ajouterUrl,
        ]);
    }
}
