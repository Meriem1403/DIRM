<?php

namespace App\Controller;

use App\Entity\DeclarationChantier;
use App\Entity\PersonneABord;
use App\Form\DeclarationChantierType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DeclarationChantierController extends AbstractController
{
    #[Route('admin/declaration/chantier/nouveau', name: 'declaration_chantier_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $declaration = new DeclarationChantier();

        // Initialise la collection avec une entrée vide
        if ($declaration->getPersonnesABord()->isEmpty()) {
            $personne = new PersonneABord();
            $personne->setDeclarationChantier($declaration);
            $declaration->addPersonneABord($personne);
        }

        $form = $this->createForm(DeclarationChantierType::class, $declaration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion du fichier joint
            $file = $form->get('noteExplicativeFile')->getData();

            if ($file) {
                $filename = uniqid() . '.' . $file->guessExtension();
                $file->move(
                    $this->getParameter('notes_directory'),
                    $filename
                );
                $declaration->setNoteExplicativePath('/uploads/notes/' . $filename);
            }

            $em->persist($declaration);
            $em->flush();

            return $this->redirectToRoute('declaration_chantier_confirmation');
        }

        return $this->render('admin/declaration_chantier/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/declaration/chantier/confirmation', name: 'declaration_chantier_confirmation')]
    public function confirmation(): Response
    {
        return $this->render('admin/declaration_chantier/confirmation.html.twig');
    }
}
