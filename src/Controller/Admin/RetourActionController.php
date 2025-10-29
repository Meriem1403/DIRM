<?php

namespace App\Controller\Admin;

use App\Entity\RetourAction;
use App\Repository\RetourActionRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[Route('/admin/retours-action', name: 'admin_retours_action')]
class RetourActionController extends AbstractController
{
    public function __construct(
        private readonly RetourActionRepository $retourActionRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly AdminUrlGenerator $adminUrlGenerator
    ) {
    }

    #[Route('', name: '_index')]
    public function index(Request $request): Response
    {
        $statut = $request->query->get('statut', 'en_attente');
        $retours = $this->retourActionRepository->findBy(
            ['statut' => $statut],
            ['dateCreation' => 'DESC']
        );

        // Si aucun statut spécifique, afficher tous les retours
        if (empty($statut) || $statut === 'tous') {
            $retours = $this->retourActionRepository->findBy(
                [],
                ['dateCreation' => 'DESC']
            );
        }

        $stats = [
            'en_attente' => $this->retourActionRepository->count(['statut' => 'en_attente']),
            'validé' => $this->retourActionRepository->count(['statut' => 'validé']),
            'refusé' => $this->retourActionRepository->count(['statut' => 'refusé']),
            'tous' => $this->retourActionRepository->count([]),
        ];

        return $this->render('admin/retours_action/index.html.twig', [
            'retours' => $retours,
            'statut_filtre' => $statut,
            'stats' => $stats,
        ]);
    }

    #[Route('/valider/{id}', name: '_valider', methods: ['POST'])]
    public function valider(Request $request, RetourAction $retourAction): Response
    {
        $action = $request->request->get('action');
        $commentaire = $request->request->get('commentaire', '');

        if ($action === 'remplacer' || $action === 'completer') {
            $retourAction->setStatut('validé');
            $retourAction->setValidateur($this->getUser());
            $retourAction->setDateValidation(new \DateTime());
            $retourAction->setCommentaireValidation($commentaire);

            // Si on remplace, mettre à jour la mesure du risque
            if ($action === 'remplacer' && $retourAction->getRisque()) {
                $risque = $retourAction->getRisque();
                $risque->setMesureEnCours($retourAction->getDescription());
                $risque->setMesureMiseEnPlace(true);
                $risque->setRetourAction('');
                $risque->setUpdatedAt(new \DateTime());
                $this->entityManager->persist($risque);
            }

            $this->entityManager->persist($retourAction);
            $this->entityManager->flush();

            $this->addFlash('success', 'Le retour d\'action a été validé avec succès.');
        }

        return $this->redirectToRoute('admin_retours_action_index');
    }

    #[Route('/refuser/{id}', name: '_refuser', methods: ['POST'])]
    public function refuser(Request $request, RetourAction $retourAction): Response
    {
        $commentaire = $request->request->get('commentaire', '');

        $retourAction->setStatut('refusé');
        $retourAction->setValidateur($this->getUser());
        $retourAction->setDateValidation(new \DateTime());
        $retourAction->setCommentaireValidation($commentaire);

        $this->entityManager->persist($retourAction);
        $this->entityManager->flush();

        $this->addFlash('warning', 'Le retour d\'action a été refusé.');

        return $this->redirectToRoute('admin_retours_action_index');
    }
}

