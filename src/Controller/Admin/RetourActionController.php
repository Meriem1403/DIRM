<?php

namespace App\Controller\Admin;

use App\Entity\RetourAction;
use App\Entity\Goudurix;
use App\Service\NotificationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class RetourActionController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private NotificationService $notificationService;

    public function __construct(EntityManagerInterface $entityManager, NotificationService $notificationService)
    {
        $this->entityManager = $entityManager;
        $this->notificationService = $notificationService;
    }

    #[Route('/risque/{id}/retour-action', name: 'risque_add_retour_action', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function addRetourAction(Request $request, Goudurix $risque): Response
    {
        $description = $request->request->get('description');

        if (!$description) {
            $this->addFlash('error', 'La description du retour d\'action est obligatoire.');
            return $this->redirectToRoute('risque_detail', ['id' => $risque->getId()]);
        }

        $retourAction = new RetourAction();
        $retourAction->setDescription($description);
        $retourAction->setRisque($risque);
        $retourAction->setAuteur($this->getUser());

        $this->entityManager->persist($retourAction);
        $this->entityManager->flush();

        // Notifier les administrateurs
        $this->notificationService->notifierRetourActionCree($retourAction);

        $this->addFlash('success', 'Retour d\'action ajouté avec succès ! Les administrateurs ont été notifiés.');

        return $this->redirectToRoute('risque_detail', ['id' => $risque->getId()]);
    }

    #[Route('/admin/retour-action/{id}/valider', name: 'admin_retour_action_valider', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function validerRetourAction(Request $request, RetourAction $retourAction): Response
    {
        $commentaire = $request->request->get('commentaire', '');

        $retourAction->setStatut('validé');
        $retourAction->setValidateur($this->getUser());
        $retourAction->setDateValidation(new \DateTime());
        $retourAction->setCommentaireValidation($commentaire);

        // Mettre à jour la mesure du risque si validé
        $risque = $retourAction->getRisque();
        $risque->setMesureEnCours($retourAction->getDescription());
        $risque->setMesureMiseEnPlace(true);
        $risque->setAuteurRetour($retourAction->getAuteur());
        $risque->setDateRetour(new \DateTime());

        $this->entityManager->persist($retourAction);
        $this->entityManager->persist($risque);
        $this->entityManager->flush();

        // Notifier le chef de service
        $this->notificationService->notifierValidationRetourAction($retourAction, true);

        $this->addFlash('success', 'Retour d\'action validé avec succès ! Le chef de service a été notifié.');

        return $this->redirectToRoute('admin_retours_action');
    }

    #[Route('/admin/retour-action/{id}/refuser', name: 'admin_retour_action_refuser', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function refuserRetourAction(Request $request, RetourAction $retourAction): Response
    {
        $commentaire = $request->request->get('commentaire', '');

        $retourAction->setStatut('refusé');
        $retourAction->setValidateur($this->getUser());
        $retourAction->setDateValidation(new \DateTime());
        $retourAction->setCommentaireValidation($commentaire);

        $this->entityManager->persist($retourAction);
        $this->entityManager->flush();

        // Notifier le chef de service
        $this->notificationService->notifierValidationRetourAction($retourAction, false);

        $this->addFlash('success', 'Retour d\'action refusé. Le chef de service a été notifié.');

        return $this->redirectToRoute('admin_retours_action');
    }

    #[Route('/admin/retours-action', name: 'admin_retours_action')]
    #[IsGranted('ROLE_ADMIN')]
    public function listeRetoursAction(): Response
    {
        $retoursEnAttente = $this->entityManager->getRepository(RetourAction::class)
            ->findEnAttente();

        return $this->render('admin/retours_action.html.twig', [
            'retoursEnAttente' => $retoursEnAttente,
        ]);
    }
}
