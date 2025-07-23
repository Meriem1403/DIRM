<?php
// src/Controller/RequestController.php

namespace App\Controller;

use App\Entity\DemandeHabilitationCerbere;
use App\Entity\DemandeMobilite;
use App\Entity\DeclarationChantier;
use App\Form\DemandeHabilitationCerbereType;
use App\Form\DemandeMobiliteType;
use App\Form\DeclarationChantierType;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

class RequestController extends AbstractController
{
    private string $notesDirectory;

    public function __construct(string $notesDirectory)
    {
        $this->notesDirectory = $notesDirectory;
    }

    #[Route('/demandes', name: 'app_demandes')]
    public function index(): Response
    {
        $requests = [
            ['path' => $this->generateUrl('demande_habilitation_new'), 'icon' => 'fa-user-shield',  'label' => 'Demande d’habilitation'],
            ['path' => $this->generateUrl('demande_mobilite_new'),      'icon' => 'fa-exchange-alt', 'label' => 'Demande de mobilité'],
            ['path' => $this->generateUrl('declaration_chantier_new'),  'icon' => 'fa-anchor',       'label' => 'Déclaration de chantier'],
        ];

        return $this->render('requests/index.html.twig', [
            'requests' => $requests,
        ]);
    }

    // --- Habilitation ---

    #[Route('/demandes/habilitation/new', name: 'demande_habilitation_new')]
    public function newHabilitation(Request $request, EntityManagerInterface $em, MailerInterface $mailer): Response
    {
        $demande = new DemandeHabilitationCerbere();
        $demande->setStatut('en_attente');
        $demande->setDateSoumission(new DateTimeImmutable());

        $form = $this->createForm(DemandeHabilitationCerbereType::class, $demande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $em->persist($demande);
                $em->flush();

                // Envoi de l'email de notification
                $pdfOptions = (new Options())->set('defaultFont', 'Helvetica');
                $dompdf     = new Dompdf($pdfOptions);
                $html       = $this->renderView('requests/form/recap_habilitation.html.twig', [
                    'demande' => $demande,
                    'pdfMode' => true,
                ]);
                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4');
                $dompdf->render();
                $pdfContent = $dompdf->output();
                $filename   = sprintf('habilitation_%d.pdf', $demande->getId());

                $email = (new Email())
                    ->from('no-reply@votre-domaine.fr')
                    // vous pouvez remplacer par une adresse de responsable
                    ->to('responsable@votre-domaine.fr')
                    ->subject('Nouvelle demande d’habilitation Cerbère')
                    ->html($this->renderView('emails/nouvelle_habilitation.html.twig', [
                        'demande' => $demande,
                    ]))
                    ->attach($pdfContent, $filename, 'application/pdf')
                ;
                $mailer->send($email);

                $this->addFlash('success', 'Votre demande d’habilitation a bien été envoyée.');
                return $this->redirectToRoute('demande_habilitation_recap', ['id' => $demande->getId()]);
            } catch (Throwable) {
                $this->addFlash('error', 'Impossible d’enregistrer votre demande. Veuillez réessayer.');
            }
        } elseif ($form->isSubmitted()) {
            $this->addFlash('error', 'Le formulaire contient des erreurs, veuillez les corriger.');
        }

        return $this->render('requests/form/new_habilitation.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/demandes/habilitation/{id}/recap', name: 'demande_habilitation_recap')]
    public function recapHabilitation(DemandeHabilitationCerbere $demande): Response
    {
        return $this->render('requests/form/recap_habilitation.html.twig', [
            'demande' => $demande,
        ]);
    }

    #[Route('/demandes/habilitation/{id}/pdf', name: 'demande_habilitation_pdf')]
    public function pdfHabilitation(DemandeHabilitationCerbere $demande): Response
    {
        $options = new Options();
        $options->set('defaultFont', 'Helvetica');
        $dompdf = new Dompdf($options);

        $html = $this->renderView('requests/form/recap_habilitation.html.twig', [
            'demande' => $demande,
            'pdfMode' => true,
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4');
        $dompdf->render();

        $pdfContent = $dompdf->output();
        $filename   = sprintf('habilitation_%d.pdf', $demande->getId());

        return new Response(
            $pdfContent,
            Response::HTTP_OK,
            [
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => sprintf('attachment; filename="%s"', $filename),
            ]
        );
    }

    // --- Mobilité ---

    #[Route('/demandes/mobilite/new', name: 'demande_mobilite_new')]
    public function newMobilite(Request $request, EntityManagerInterface $em, MailerInterface $mailer): Response
    {
        $demande = new DemandeMobilite();
        $form    = $this->createForm(DemandeMobiliteType::class, $demande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $demande->setCreatedAt(new DateTimeImmutable());
            $demande->setCreatedBy($this->getUser());
            $demande->setStatut('en_attente');

            try {
                $em->persist($demande);
                $em->flush();

                // Génération et envoi du PDF par email
                $options = (new Options())->set('defaultFont', 'Helvetica');
                $dompdf  = new Dompdf($options);
                $html    = $this->renderView('requests/form/recap_mobilite.html.twig', [
                    'demande' => $demande,
                    'pdfMode' => true,
                ]);
                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4');
                $dompdf->render();
                $pdfContent = $dompdf->output();
                $filename   = sprintf('mobilite_%d.pdf', $demande->getId());

                $email = (new Email())
                    ->from('no-reply@votre-domaine.fr')
                    // ici l’agent ou le responsable
                    ->to('responsable@votre-domaine.fr')
                    ->subject('Nouvelle demande de mobilité')
                    ->html($this->renderView('emails/nouvelle_mobilite.html.twig', [
                        'demande' => $demande,
                    ]))
                    ->attach($pdfContent, $filename, 'application/pdf')
                ;
                $mailer->send($email);

                $this->addFlash('success', 'Votre demande de mobilité a bien été envoyée.');
                return $this->redirectToRoute('demande_mobilite_recap', ['id' => $demande->getId()]);
            } catch (Throwable) {
                $this->addFlash('error', 'Impossible d’enregistrer votre demande. Veuillez réessayer.');
            }
        } elseif ($form->isSubmitted()) {
            $this->addFlash('error', 'Le formulaire contient des erreurs, veuillez les corriger.');
        }

        return $this->render('requests/form/new_mobilite.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/demandes/mobilite/{id}/recap', name: 'demande_mobilite_recap')]
    public function recapMobilite(DemandeMobilite $demande): Response
    {
        return $this->render('requests/form/recap_mobilite.html.twig', [
            'demande' => $demande,
        ]);
    }

    #[Route('/demandes/mobilite/{id}/pdf', name: 'demande_mobilite_pdf')]
    public function pdfMobilite(DemandeMobilite $demande): Response
    {
        $options = new Options();
        $options->set('defaultFont', 'Helvetica');
        $dompdf = new Dompdf($options);

        $html = $this->renderView('requests/form/recap_mobilite.html.twig', [
            'demande' => $demande,
            'pdfMode' => true,
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4');
        $dompdf->render();

        $pdfContent = $dompdf->output();
        $filename   = sprintf('mobilite_%d.pdf', $demande->getId());

        return new Response(
            $pdfContent,
            Response::HTTP_OK,
            [
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => sprintf('attachment; filename="%s"', $filename),
            ]
        );
    }

    // --- Chantier ---

    #[Route('/demandes/chantier/new', name: 'declaration_chantier_new')]
    public function newChantier(Request $request, EntityManagerInterface $em, MailerInterface $mailer): Response
    {
        $demande = new DeclarationChantier();
        $form    = $this->createForm(DeclarationChantierType::class, $demande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $demande->setStatut('en_attente');
            $demande->setDateSoumission(new DateTimeImmutable());

            if ($rec = $form->get('recepisseFile')->getData()) {
                $fn = uniqid('recp_').'.'.$rec->guessExtension();
                $rec->move($this->notesDirectory, $fn);
                $demande->setRecepissePath($fn);
            }
            if ($note = $form->get('noteExplicativeFile')->getData()) {
                $fn = uniqid('note_').'.'.$note->guessExtension();
                $note->move($this->notesDirectory, $fn);
                $demande->setNoteExplicativePath($fn);
            }

            try {
                $em->persist($demande);
                $em->flush();

                // Envoi du PDF par email
                $options = (new Options())->set('defaultFont', 'Helvetica');
                $dompdf  = new Dompdf($options);
                $html    = $this->renderView('requests/form/recap_chantier.html.twig', [
                    'demande' => $demande,
                    'pdfMode' => true,
                ]);
                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4');
                $dompdf->render();
                $pdfContent = $dompdf->output();
                $filename   = sprintf('chantier_%d.pdf', $demande->getId());

                $email = (new Email())
                    ->from('no-reply@votre-domaine.fr')
                    ->to('responsable@votre-domaine.fr')
                    ->subject('Nouvelle déclaration de chantier')
                    ->html($this->renderView('emails/nouvelle_chantier.html.twig', [
                        'demande' => $demande,
                    ]))
                    ->attach($pdfContent, $filename, 'application/pdf')
                ;
                $mailer->send($email);

                $this->addFlash('success', 'Votre déclaration de chantier a bien été enregistrée.');
                return $this->redirectToRoute('declaration_chantier_recap', ['id' => $demande->getId()]);
            } catch (Throwable) {
                $this->addFlash('error', 'Impossible d’enregistrer la déclaration. Veuillez réessayer.');
            }
        } elseif ($form->isSubmitted()) {
            $this->addFlash('error', 'Le formulaire contient des erreurs, veuillez les corriger.');
        }

        return $this->render('requests/form/new_chantier.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/demandes/chantier/{id}/recap', name: 'declaration_chantier_recap')]
    public function recapChantier(DeclarationChantier $demande): Response
    {
        return $this->render('requests/form/recap_chantier.html.twig', [
            'demande' => $demande,
        ]);
    }

    #[Route('/demandes/chantier/{id}/pdf', name: 'declaration_chantier_pdf')]
    public function pdfChantier(DeclarationChantier $demande): Response
    {
        $options = new Options();
        $options->set('defaultFont', 'Helvetica');
        $dompdf = new Dompdf($options);

        $html = $this->renderView('requests/form/recap_chantier.html.twig', [
            'demande' => $demande,
            'pdfMode' => true,
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4');
        $dompdf->render();

        $pdfContent = $dompdf->output();
        $filename   = sprintf('chantier_%d.pdf', $demande->getId());

        return new Response(
            $pdfContent,
            Response::HTTP_OK,
            [
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => sprintf('attachment; filename="%s"', $filename),
            ]
        );
    }
}
