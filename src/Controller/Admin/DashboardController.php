<?php

namespace App\Controller\Admin;

use App\Entity\Categorie;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use App\Entity\Role;
use App\Entity\Service;
use App\Entity\DomaineService;
use App\Entity\Lieu;
use App\Entity\ApplicationCerbere;
use App\Entity\ProfilCerbere;
use App\Entity\DemandeHabilitationCerbere;
use App\Entity\DemandeMobilite;
use App\Entity\DeclarationChantier;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use Symfony\Component\Security\Core\User\UserInterface;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('DIRM Méditerranée')
            ->renderContentMaximized();
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Tableau de bord', 'fa fa-home');

        yield MenuItem::section('Utilisateurs & Structure');
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-users', User::class);
        yield MenuItem::linkToCrud('Rôles', 'fas fa-user-tag', Role::class);
        yield MenuItem::linkToCrud('Services', 'fas fa-building', Service::class);
        yield MenuItem::linkToCrud('Domaines', 'fas fa-briefcase', DomaineService::class);
        yield MenuItem::linkToCrud('Lieux', 'fas fa-map-marker-alt', Lieu::class);
        yield MenuItem::linkToCrud('Categories', 'fas fa-layer-group', Categorie::class);

        yield MenuItem::section('Applications & Habilitations');
        yield MenuItem::linkToCrud('Applications Cerbère', 'fas fa-cogs', ApplicationCerbere::class);
        yield MenuItem::linkToCrud('Profils Cerbère', 'fas fa-id-badge', ProfilCerbere::class);
        yield MenuItem::linkToCrud('Demandes d\'habilitation', 'fas fa-shield-alt', DemandeHabilitationCerbere::class);

        yield MenuItem::section('Mobilité & Chantier');
        yield MenuItem::linkToCrud('Demandes de mobilité', 'fas fa-exchange-alt', DemandeMobilite::class);
        yield MenuItem::linkToCrud('Déclarations chantier', 'fas fa-anchor', DeclarationChantier::class);

        yield MenuItem::section('Accès rapide');
        yield MenuItem::linkToUrl('Retour au site', 'fas fa-home', '/')->setLinkTarget('_blank');
    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        return parent::configureUserMenu($user)
            ->addMenuItems([
                MenuItem::linkToLogout('Se déconnecter', 'fas fa-sign-out-alt'),
            ]);
    }

    public function configureActions(): Actions
    {
        return parent::configureActions()
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->update(Crud::PAGE_DETAIL, Action::INDEX, fn(Action $action) => $action);
    }

    public function configureAssets(): Assets
    {
        return parent::configureAssets()
            ->addWebpackEncoreEntry('admin'); // si tu as un fichier admin.js/css
    }

    public function configureCrud(): Crud
    {
        return parent::configureCrud()
            ->setDefaultSort(['id' => 'DESC']);
    }
}
