<?php

namespace App\Controller\Admin;

use App\Entity\Goudurix;
use App\Repository\GoudurixRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GoudurixActionController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/risque/{id}/mesure', name: 'risque_add_mesure', methods: ['POST'])]
    public function addMesure(Request $request, Goudurix $risque): Response
    {
        $mesureEnCours = $request->request->get('mesure_en_cours');
        $mesureMiseEnPlace = $request->request->get('mesure_mise_en_place') === 'on';

        if ($mesureEnCours) {
            $risque->setMesureEnCours($mesureEnCours);
            $risque->setMesureMiseEnPlace($mesureMiseEnPlace);
            $risque->setUpdatedAt(new \DateTime());
            
            $this->entityManager->persist($risque);
            $this->entityManager->flush();

            $this->addFlash('success', 'Mesure ajoutée avec succès !');
        }

        return $this->redirectToRoute('risque_detail', ['id' => $risque->getId()]);
    }

    #[Route('/risque/{id}/retour', name: 'risque_add_retour', methods: ['POST'])]
    public function addRetour(Request $request, Goudurix $risque): Response
    {
        $retourAction = $request->request->get('retour_action');

        if ($retourAction) {
            $risque->setRetourAction($retourAction);
            $risque->setAuteurRetour($this->getUser());
            $risque->setDateRetour(new \DateTime());
            $risque->setUpdatedAt(new \DateTime());
            
            $this->entityManager->persist($risque);
            $this->entityManager->flush();

            $this->addFlash('success', 'Retour d\'action ajouté avec succès !');
        }

        return $this->redirectToRoute('risque_detail', ['id' => $risque->getId()]);
    }

    #[Route('/risque/{id}/statut', name: 'risque_update_statut', methods: ['POST'])]
    public function updateStatut(Request $request, Goudurix $risque): Response
    {
        $nouveauStatut = $request->request->get('statut');
        $dateResolution = $request->request->get('date_resolution');

        if ($nouveauStatut) {
            $risque->setStatut($nouveauStatut);
            
            if ($nouveauStatut === 'traité' && $dateResolution) {
                $risque->setDateResolution(new \DateTime($dateResolution));
            }
            
            $risque->setUpdatedAt(new \DateTime());
            
            $this->entityManager->persist($risque);
            $this->entityManager->flush();

            $this->addFlash('success', 'Statut mis à jour avec succès !');
        }

        return $this->redirectToRoute('risque_detail', ['id' => $risque->getId()]);
    }
}
