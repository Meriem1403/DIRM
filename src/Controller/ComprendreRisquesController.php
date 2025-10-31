<?php

namespace App\Controller;

use App\Entity\Goudurix;
use App\Repository\GoudurixRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ComprendreRisquesController extends AbstractController
{
    #[Route('/comprendre-risques', name: 'comprendre_risques')]
    #[IsGranted('ROLE_USER')]
    public function index(GoudurixRepository $goudurixRepository): Response
    {
        // Récupérer tous les risques, triés par date de création (plus récents en premier)
        $risques = $goudurixRepository->findBy([], ['createdAt' => 'DESC']);

        return $this->render('comprendre_risques/index.html.twig', [
            'risques' => $risques,
        ]);
    }

    #[Route('/comprendre-risques/{id}', name: 'comprendre_risques_detail')]
    #[IsGranted('ROLE_USER')]
    public function detail(Goudurix $risque): Response
    {
        return $this->render('comprendre_risques/detail.html.twig', [
            'risque' => $risque,
        ]);
    }
}
