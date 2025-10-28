<?php

namespace App\Controller\Admin;

use App\Entity\Goudurix;
use App\Entity\RetourAction;
use App\Entity\Notification;
use App\Repository\GoudurixRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

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

    #[Route('/risque/{id}/retour-action', name: 'risque_add_retour_action', methods: ['POST'])]
    #[IsGranted('ROLE_CHEF')]
    public function addRetourAction(Request $request, Goudurix $risque, UserRepository $userRepository): Response
    {
        $description = $request->request->get('description');

        if ($description) {
            // Créer le retour d'action
            $retourAction = new RetourAction();
            $retourAction->setDescription($description);
            $retourAction->setAuteur($this->getUser());
            $retourAction->setRisque($risque);
            $retourAction->setDateCreation(new \DateTime());

            $this->entityManager->persist($retourAction);

            // Créer une notification pour tous les admins
            $admins = $userRepository->findByRole('ROLE_ADMIN');
            foreach ($admins as $admin) {
                $notification = new Notification();
                $notification->setTitre('Nouveau retour d\'action');
                $notification->setMessage(sprintf(
                    'Le chef de service %s %s a fait un retour d\'action sur le risque "%s"',
                    $this->getUser()->getPrenom(),
                    $this->getUser()->getNom(),
                    $risque->getTitre()
                ));
                $notification->setType('retour_action');
                $notification->setDestinataire($admin);
                $notification->setExpediteur($this->getUser());
                $notification->setRetourAction($retourAction);
                $notification->setRisque($risque);

                $this->entityManager->persist($notification);
            }

            $this->entityManager->flush();

            $this->addFlash('success', 'Retour d\'action envoyé avec succès ! Les administrateurs ont été notifiés.');
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

    #[Route('/admin/retour-action/{id}/valider', name: 'admin_valider_retour', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function validerRetour(Request $request, RetourAction $retourAction, UserRepository $userRepository): Response
    {
        $action = $request->request->get('action'); // 'valider' ou 'refuser'
        $commentaire = $request->request->get('commentaire');

        if ($action === 'valider') {
            $retourAction->setStatut('validé');
            $message = 'Retour d\'action validé avec succès !';
        } elseif ($action === 'refuser') {
            $retourAction->setStatut('refusé');
            $message = 'Retour d\'action refusé.';
        }

        $retourAction->setValidateur($this->getUser());
        $retourAction->setDateValidation(new \DateTime());
        $retourAction->setCommentaireValidation($commentaire);

        $this->entityManager->persist($retourAction);

        // Créer une notification pour le chef de service
        $notification = new Notification();
        $notification->setTitre($action === 'valider' ? 'Retour d\'action validé' : 'Retour d\'action refusé');
        $notification->setMessage(sprintf(
            'Votre retour d\'action sur le risque "%s" a été %s par %s %s.',
            $retourAction->getRisque()->getTitre(),
            $action === 'valider' ? 'validé' : 'refusé',
            $this->getUser()->getPrenom(),
            $this->getUser()->getNom()
        ));
        if ($commentaire) {
            $notification->setMessage($notification->getMessage() . "\n\nCommentaire : " . $commentaire);
        }
        $notification->setType('validation_retour');
        $notification->setDestinataire($retourAction->getAuteur());
        $notification->setExpediteur($this->getUser());
        $notification->setRetourAction($retourAction);
        $notification->setRisque($retourAction->getRisque());

        $this->entityManager->persist($notification);
        $this->entityManager->flush();

        $this->addFlash('success', $message . ' Le chef de service a été notifié.');

        return $this->redirectToRoute('risque_detail', ['id' => $retourAction->getRisque()->getId()]);
    }
}
