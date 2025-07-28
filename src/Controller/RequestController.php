<?php

namespace App\Controller;

use App\Entity\DemandeHabilitationCerbere;
use App\Entity\DemandeMobilite;
use App\Entity\DeclarationChantier;
use App\Entity\PersonneABord;
use App\Form\DemandeHabilitationCerbereType;
use App\Form\DemandeMobiliteType;
use App\Form\DeclarationChantierType;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

class RequestController extends AbstractController
{
    private string $notesDirectory;
    private LoggerInterface $logger;

    public function __construct(string $notesDirectory, LoggerInterface $logger)
    {
        $this->notesDirectory = $notesDirectory;
        $this->logger = $logger;
    }

    #[Route('/demandes', name: 'app_demandes')]
    public function index(EntityManagerInterface $em): Response
    {
        $requests = [
            ['path' => $this->generateUrl('demande_habilitation_new'), 'icon' => 'fa-user-shield',  'label' => 'Demande d’habilitation'],
            ['path' => $this->generateUrl('demande_mobilite_new'),      'icon' => 'fa-exchange-alt', 'label' => 'Demande de mobilité'],
            ['path' => $this->generateUrl('declaration_chantier_new'),  'icon' => 'fa-anchor',       'label' => 'Déclaration de chantier'],
        ];

        $user = $this->getUser();
        $habilitations = [];
        $mobilites = [];
        $chantiers = [];

        if ($user) {
            $habilitations = $em->getRepository(DemandeHabilitationCerbere::class)
                ->findBy(['demandeur' => $user], ['dateSoumission' => 'DESC']);

            $mobilites = $em->getRepository(DemandeMobilite::class)
                ->findBy(['createdBy' => $user], ['createdAt' => 'DESC']);
        }

        return $this->render('requests/index.html.twig', [
            'requests'      => $requests,
            'habilitations' => $habilitations,
            'mobilites'     => $mobilites,
            'chantiers'     => $chantiers,
        ]);
    }

    #[Route('/demandes/habilitation/new', name: 'demande_habilitation_new')]
    public function newHabilitation(Request $request, EntityManagerInterface $em, MailerInterface $mailer): Response
    {
        $demande = (new DemandeHabilitationCerbere())
            ->setStatut('en_attente')
            ->setDateSoumission(new DateTimeImmutable());

        $form = $this->createForm(DemandeHabilitationCerbereType::class, $demande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $em->persist($demande);
                $em->flush();

                $this->sendDemandeNotification(
                    $mailer,
                    $demande,
                    'Nouvelle demande d’habilitation Cerbère',
                    'requests/form/recap_habilitation.html.twig',
                    'emails/nouvelle_habilitation.html.twig',
                    'habilitation'
                );

                $this->addFlash('success', 'Votre demande d’habilitation a bien été envoyée.');
                return $this->redirectToRoute('demande_habilitation_recap', ['id' => $demande->getId()]);
            } catch (Throwable $e) {
                $this->logger->error('Erreur enregistrement habilitation', ['exception' => $e]);
                $this->addFlash('error', 'Impossible d’enregistrer votre demande. Veuillez réessayer.');
            }
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
        return $this->renderPdfResponse(
            'requests/form/recap_habilitation.html.twig',
            ['demande' => $demande, 'pdfMode' => true],
            sprintf('habilitation_%d.pdf', $demande->getId())
        );
    }

    #[Route('/demandes/mobilite/new', name: 'demande_mobilite_new')]
    public function newMobilite(Request $request, EntityManagerInterface $em, MailerInterface $mailer): Response
    {
        $demande = new DemandeMobilite();
        $form = $this->createForm(DemandeMobiliteType::class, $demande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $demande
                ->setCreatedAt(new DateTimeImmutable())
                ->setCreatedBy($this->getUser())
                ->setStatut('en_attente');

            try {
                $em->persist($demande);
                $em->flush();

                $this->sendDemandeNotification(
                    $mailer,
                    $demande,
                    'Nouvelle demande de mobilité',
                    'requests/form/recap_mobilite.html.twig',
                    'emails/nouvelle_mobilite.html.twig',
                    'mobilite'
                );

                $this->addFlash('success', 'Votre demande de mobilité a bien été envoyée.');
                return $this->redirectToRoute('demande_mobilite_recap', ['id' => $demande->getId()]);
            } catch (Throwable $e) {
                $this->logger->error('Erreur enregistrement mobilite', ['exception' => $e]);
                $this->addFlash('error', 'Impossible d’enregistrer votre demande. Veuillez réessayer.');
            }
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
        return $this->renderPdfResponse(
            'requests/form/recap_mobilite.html.twig',
            ['demande' => $demande, 'pdfMode' => true],
            sprintf('mobilite_%d.pdf', $demande->getId())
        );
    }

    #[Route('/demandes/chantier/new', name: 'declaration_chantier_new')]
    public function newChantier(Request $request, EntityManagerInterface $em, MailerInterface $mailer): Response
    {
        $demande = new DeclarationChantier();

        // ✅ Ajout d’un bloc PersonneABord pour que le formulaire soit valide
        $demande->addPersonnesABord(new PersonneABord());

        $form = $this->createForm(DeclarationChantierType::class, $demande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $demande
                ->setStatut('en_attente')
                ->setDateSoumission(new DateTimeImmutable());

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

                $this->sendDemandeNotification(
                    $mailer,
                    $demande,
                    'Nouvelle déclaration de chantier',
                    'requests/form/recap_chantier.html.twig',
                    'emails/nouvelle_chantier.html.twig',
                    'chantier'
                );

                $this->addFlash('success', 'Votre déclaration de chantier a bien été enregistrée.');
                return $this->redirectToRoute('declaration_chantier_recap', ['id' => $demande->getId()]);
            } catch (Throwable $e) {
                $this->logger->error('Erreur enregistrement chantier', ['exception' => $e]);
                $this->addFlash('error', 'Impossible d’enregistrer la déclaration. Veuillez réessayer.');
            }
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
        return $this->renderPdfResponse(
            'requests/form/recap_chantier.html.twig',
            ['demande' => $demande, 'pdfMode' => true],
            sprintf('chantier_%d.pdf', $demande->getId())
        );
    }

    private function generatePdfContent(string $twig, array $context): string
    {
        $options = (new Options())->set('defaultFont', 'Helvetica');
        $dompdf = new Dompdf($options);
        $html = $this->renderView($twig, $context);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4');
        $dompdf->render();

        return $dompdf->output();
    }

    private function renderPdfResponse(string $twig, array $context, string $filename): Response
    {
        $pdf = $this->generatePdfContent($twig, $context);

        return new Response($pdf, Response::HTTP_OK, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => sprintf('attachment; filename="%s"', $filename),
        ]);
    }

    private function sendDemandeNotification(
        MailerInterface $mailer,
        object $demande,
        string $subject,
        string $recapTemplate,
        string $emailTemplate,
        string $filenamePrefix
    ): void {
        $pdfContent = $this->generatePdfContent($recapTemplate, [
            'demande' => $demande,
            'pdfMode' => true,
        ]);

        $filename = sprintf('%s_%d.pdf', $filenamePrefix, $demande->getId());

        $email = (new Email())
            ->from('no-reply@votre-domaine.fr')
            ->to('responsable@votre-domaine.fr')
            ->subject($subject)
            ->html($this->renderView($emailTemplate, ['demande' => $demande]))
            ->attach($pdfContent, $filename, 'application/pdf');

        try {
            $mailer->send($email);
        } catch (TransportExceptionInterface $ex) {
            $this->logger->warning('Notification email non envoyée', ['error' => $ex->getMessage()]);
            $this->addFlash('warning', 'La notification par e‑mail n’a pas pu être envoyée.');
        }
    }
}
