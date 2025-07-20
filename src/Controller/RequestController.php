<?php
// src/Controller/RequestController.php

namespace App\Controller;

use App\Entity\DemandeHabilitationCerbere;
use App\Entity\DemandeMobilite;
use App\Entity\DeclarationChantier;
use App\Form\DemandeHabilitationCerbereType;
use App\Form\DemandeMobiliteType;
use App\Form\DeclarationChantierType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use DateTimeImmutable;

class RequestController extends AbstractController
{
    /**
     * Page de choix : on affiche simplement les 3 cards/liens.
     */
    #[Route('/demandes', name: 'app_demandes')]
    public function index(): Response
    {
        $requests = [
            [
                'path'  => $this->generateUrl('demande_habilitation_new'),
                'icon'  => 'fa-user-shield',
                'label' => 'Demande d’habilitation',
            ],
            [
                'path'  => $this->generateUrl('demande_mobilite_new'),
                'icon'  => 'fa-exchange-alt',
                'label' => 'Demande de mobilité',
            ],
            [
                'path'  => $this->generateUrl('declaration_chantier_new'),
                'icon'  => 'fa-anchor',
                'label' => 'Déclaration de chantier',
            ],
        ];

        return $this->render('requests/index.html.twig', [
            'requests' => $requests,
        ]);
    }

    /**
     * Formulaire de création d’une demande d’habilitation.
     */
    #[Route('/demandes/habilitation/new', name: 'demande_habilitation_new')]
    public function newHabilitation(Request $request, EntityManagerInterface $em): Response
    {
        $demande = new DemandeHabilitationCerbere();
        $form    = $this->createForm(DemandeHabilitationCerbereType::class, $demande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($demande);
            $em->flush();
            $this->addFlash('success', 'Votre demande d’habilitation a bien été envoyée.');
            return $this->redirectToRoute('homepage');
        }

        return $this->render('requests/form/new_habilitation.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Formulaire de création d’une demande de mobilité.
     */
    #[Route('/demandes/mobilite/new', name: 'demande_mobilite_new')]
    public function newMobilite(Request $request, EntityManagerInterface $em): Response
    {
        $demande = new DemandeMobilite();
        $form    = $this->createForm(DemandeMobiliteType::class, $demande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On renseigne automatiquement la date, l'utilisateur et le statut
            $demande->setCreatedAt(new DateTimeImmutable());
            $demande->setCreatedBy($this->getUser());
            $demande->setStatut('en_attente');

            $em->persist($demande);
            $em->flush();
            $this->addFlash('success', 'Votre demande de mobilité a bien été envoyée.');
            return $this->redirectToRoute('homepage');
        }

        return $this->render('requests/form/new_mobilite.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Formulaire de déclaration de chantier.
     */
    #[Route('/demandes/chantier/new', name: 'declaration_chantier_new')]
    public function newChantier(Request $request, EntityManagerInterface $em): Response
    {
        $demande = new DeclarationChantier();
        $form    = $this->createForm(DeclarationChantierType::class, $demande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($demande);
            $em->flush();
            $this->addFlash('success', 'Votre déclaration de chantier a bien été enregistrée.');
            return $this->redirectToRoute('homepage');
        }

        return $this->render('requests/form/new_chantier.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
