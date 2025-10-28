<?php

namespace App\Controller\Admin;

use App\Entity\Categorie;
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
use App\Entity\Goudurix;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private readonly EntityManagerInterface $em
    ) {}

    public function index(): Response
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw new LogicException('L\'utilisateur connecté n\'est pas une instance de App\Entity\User.');
        }

        if ($this->isGranted('ROLE_ADMIN')) {
            // Habilitations
            $repoHabi = $this->em->getRepository(DemandeHabilitationCerbere::class);
            $nbDemandesHabilitation = $repoHabi->count();
            $nbHabilitationsEnAttente = $repoHabi->count(['statut' => 'en_attente']);
            $nbHabilitationsValidees  = $repoHabi->count(['statut' => 'validee']);
            $nbHabilitationsRefusees  = $repoHabi->count(['statut' => 'refusee']);

            // Mobilités
            $repoMob = $this->em->getRepository(DemandeMobilite::class);
            $nbDemandesMobilite = $repoMob->count();
            $nbMobilitesEnAttente = $repoMob->count(['statut' => 'en_attente']);
            $nbMobilitesValidees  = $repoMob->count(['statut' => 'validee']);
            $nbMobilitesRefusees  = $repoMob->count(['statut' => 'refusee']);

            // Chantiers
            $repoChantier = $this->em->getRepository(DeclarationChantier::class);
            $nbChantiers = $repoChantier->count();
            $nbChantiersEnAttente = $repoChantier->count(['statut' => 'en_attente']);
            $nbChantiersValidees  = $repoChantier->count(['statut' => 'validee']);
            $nbChantiersRefusees  = $repoChantier->count(['statut' => 'refusee']);

            // Risques Goudurix
            $repoGoudurix = $this->em->getRepository(Goudurix::class);
            $nbRisques = $repoGoudurix->count();
            $nbRisquesEnCours = $repoGoudurix->count(['statut' => 'en_cours']);
            $nbRisquesCritiques = $repoGoudurix->count(['niveauRisque' => 'critique']);
            $nbRisquesTraites = $repoGoudurix->count(['statut' => 'traité']);

            return $this->render('dashboard/dashboard_admin.html.twig', [
                'nbDemandesHabilitation'     => $nbDemandesHabilitation,
                'nbHabilitationsEnAttente'   => $nbHabilitationsEnAttente,
                'nbHabilitationsValidees'    => $nbHabilitationsValidees,
                'nbHabilitationsRefusees'    => $nbHabilitationsRefusees,

                'nbDemandesMobilite'         => $nbDemandesMobilite,
                'nbMobilitesEnAttente'       => $nbMobilitesEnAttente,
                'nbMobilitesValidees'        => $nbMobilitesValidees,
                'nbMobilitesRefusees'        => $nbMobilitesRefusees,

                'nbChantiers'                => $nbChantiers,
                'nbChantiersEnAttente'       => $nbChantiersEnAttente,
                'nbChantiersValidees'        => $nbChantiersValidees,
                'nbChantiersRefusees'        => $nbChantiersRefusees,

                'nbRisques'                  => $nbRisques,
                'nbRisquesEnCours'           => $nbRisquesEnCours,
                'nbRisquesCritiques'         => $nbRisquesCritiques,
                'nbRisquesTraites'           => $nbRisquesTraites,
            ]);
        }

        if ($this->isGranted('ROLE_CHEF')) {
            $service = $user->getService();

            $repoMob = $this->em->getRepository(DemandeMobilite::class);

            $nbDemandesService = $repoMob->count(['service' => $service]);
            $nbEnCours         = $repoMob->count(['service' => $service, 'statut' => 'en_cours']);
            $nbValidees        = $repoMob->count(['service' => $service, 'statut' => 'validee']);
            $nbRefusees        = $repoMob->count(['service' => $service, 'statut' => 'refusee']);

            return $this->render('dashboard/dashboard_chef.html.twig', [
                'nbDemandesService' => $nbDemandesService,
                'nbEnCours'         => $nbEnCours,
                'nbValidees'        => $nbValidees,
                'nbRefusees'        => $nbRefusees,
            ]);
        }

        return $this->render('dashboard/dashboard_agent.html.twig');
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

            yield MenuItem::section('Gestion des Risques');
            yield MenuItem::linkToUrl('Dashboard Risques', 'fas fa-tachometer-alt', '/goudurix-dashboard')->setLinkTarget('_blank');
            yield MenuItem::linkToCrud('Risques Goudurix', 'fas fa-exclamation-triangle', Goudurix::class);
            yield MenuItem::linkToUrl('Retours d\'Action', 'fas fa-comments', '/admin/retours-action')->setLinkTarget('_blank');
            yield MenuItem::linkToUrl('Cartes des Services', 'fas fa-th-large', '/services')->setLinkTarget('_blank');

        yield MenuItem::section('Accès rapide');
        yield MenuItem::linkToUrl('Retour au site', 'fas fa-home', '/')->setLinkTarget('_blank');
    }

    //public function configureUserMenu(UserInterface $user): UserMenu
    //{
        //return parent::configureUserMenu($user)
            //->addMenuItems([
                //MenuItem::linkToLogout('Se déconnecter', 'fas fa-sign-out-alt'),
            //]);
    //}

    public function configureActions(): Actions
    {
        return parent::configureActions()
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->update(Crud::PAGE_DETAIL, Action::INDEX, fn(Action $action) => $action);
    }

    public function configureAssets(): Assets
    {
        return parent::configureAssets()
            ->addWebpackEncoreEntry('admin');
    }
    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('DIRM Gestion') // ✅ Ton vrai titre ici
            ->renderContentMaximized();
    }


    public function configureCrud(): Crud
    {
        return parent::configureCrud()
            ->setDefaultSort(['id' => 'DESC']);
    }
}
