<?php

namespace App\Controller;

use App\Entity\Goudurix;
use App\Repository\GoudurixRepository;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class FicheRisquesController extends AbstractController
{
    #[Route('/fiche-risques', name: 'fiche_risques')]
    #[IsGranted('ROLE_USER')]
    public function index(GoudurixRepository $goudurixRepository): Response
    {
        // Récupérer tous les risques, triés par date de création (plus récents en premier)
        $risques = $goudurixRepository->findBy([], ['createdAt' => 'DESC']);

        return $this->render('fiche_risques/index.html.twig', [
            'risques' => $risques,
        ]);
    }

    #[Route('/fiche-risques/{id}', name: 'fiche_risques_detail')]
    #[IsGranted('ROLE_USER')]
    public function detail(Goudurix $risque): Response
    {
        return $this->render('fiche_risques/detail.html.twig', [
            'risque' => $risque,
        ]);
    }

    #[Route('/fiche-risques/{id}/pdf', name: 'fiche_risques_pdf')]
    #[IsGranted('ROLE_USER')]
    public function pdf(Goudurix $risque): Response
    {
        // Configuration de Dompdf
        $options = new Options();
        $options->set('defaultFont', 'Helvetica');
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        
        $dompdf = new Dompdf($options);
        
        // Génération du HTML pour le PDF
        $html = $this->renderView('fiche_risques/pdf.html.twig', [
            'risque' => $risque,
        ]);
        
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        // Nom du fichier PDF
        $filename = sprintf('fiche_risque_%d_%s.pdf', 
            $risque->getId(), 
            date('Y-m-d')
        );
        
        // Génération de la réponse PDF
        return new Response($dompdf->output(), Response::HTTP_OK, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => sprintf('attachment; filename="%s"', $filename),
        ]);
    }
}
