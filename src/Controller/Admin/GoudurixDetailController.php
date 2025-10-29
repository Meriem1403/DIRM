<?php

namespace App\Controller\Admin;

use App\Entity\Goudurix;
use App\Repository\GoudurixRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class GoudurixDetailController extends AbstractController
{
    #[Route('/risque-detail/{id}', name: 'risque_detail')]
    #[IsGranted('ROLE_USER')]
    public function detail(Goudurix $risque): Response
    {
        return $this->render('admin/risque_detail.html.twig', [
            'risque' => $risque,
        ]);
    }
}
