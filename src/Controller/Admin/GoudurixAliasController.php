<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GoudurixAliasController extends AbstractController
{
    public function __construct(private readonly AdminUrlGenerator $adminUrlGenerator)
    {
    }

    // Alias propre pour la vue "cartes"
    #[Route('/admin/goudurix', name: 'admin_goudurix_cards')]
    public function goudurixCards(): Response
    {
        $url = $this->adminUrlGenerator
            ->setController(GoudurixCrudController::class)
            ->setAction('index')
            ->set('view', 'cards')
            ->generateUrl();

        return $this->redirect($url);
    }

    // Alias pour la vue "table" (index standard EasyAdmin)
    #[Route('/admin/goudurix-table', name: 'admin_goudurix_table')]
    public function goudurixTable(): Response
    {
        $url = $this->adminUrlGenerator
            ->setController(GoudurixCrudController::class)
            ->setAction('index')
            ->set('view', 'table')
            ->generateUrl();

        return $this->redirect($url);
    }
}


