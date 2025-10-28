<?php

namespace App\Controller\Admin;

use App\Entity\Service;
use App\Entity\Goudurix;
use App\Repository\ServiceRepository;
use App\Repository\GoudurixRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GoudurixDashboardController extends AbstractController
{
    #[Route('/goudurix-dashboard', name: 'goudurix_dashboard')]
    public function dashboard(ServiceRepository $serviceRepository, GoudurixRepository $goudurixRepository): Response
    {
        $services = $serviceRepository->findBy(['actif' => true], ['nom' => 'ASC']);
        
        // Charger les risques pour chaque service
        $servicesWithRisques = [];
        foreach ($services as $service) {
            $risques = $goudurixRepository->findBy(['service' => $service]);
            $servicesWithRisques[] = [
                'service' => $service,
                'risques' => $risques
            ];
        }
        
        return $this->render('admin/goudurix_dashboard.html.twig', [
            'services' => $servicesWithRisques,
        ]);
    }
}
