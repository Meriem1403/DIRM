<?php
// src/Controller/HomeController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(): Response
    {
        // Liste des slides du hero (chemins relatifs vers public/)
        $heroImages = [
            'uploads/hero/slide1.jpg',
            'uploads/hero/slide2.jpg',
            'uploads/hero/slide3.jpg',
        ];

        return $this->render('home/index.html.twig', [
            'heroImages' => $heroImages,
        ]);
    }

    #[Route('/aide-contact', name: 'aide_contact')]
    public function aideContact(): Response
    {
        return $this->render('home/aide_contact.html.twig');
    }
}
