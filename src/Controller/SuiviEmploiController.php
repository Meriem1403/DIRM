<?php

namespace App\Controller;

use App\Repository\SuiviEmploiRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class SuiviEmploiController extends AbstractController
{
    #[Route('/suivi-emplois', name: 'suivi_emplois')]
    #[IsGranted('ROLE_AGENT')]
    public function index(SuiviEmploiRepository $suiviEmploiRepository): Response
    {
        try {
            // Récupérer tous les enregistrements (pour l'instant, on peut limiter ou paginer plus tard)
            $suiviEmplois = $suiviEmploiRepository->findAll();
        } catch (\Exception $e) {
            // Si la table n'existe pas encore, retourner un message
            $suiviEmplois = [];
            $this->addFlash('warning', 'La table suivi_emploi n\'existe pas encore. Veuillez exécuter la migration : php bin/console doctrine:migrations:migrate');
        }

        return $this->render('suivi_emploi/index.html.twig', [
            'suivi_emplois' => $suiviEmplois,
        ]);
    }
}

