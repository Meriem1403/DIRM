<?php

namespace App\Controller\Admin;

use App\Entity\DemandeMobilite;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use DateTimeImmutable;

class DemandeMobiliteCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return DemandeMobilite::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('une demande de mobilité')
            ->setEntityLabelInPlural('des demandes de mobilité')
            ->setPageTitle(Crud::PAGE_INDEX, '📤 Liste des demandes de mobilités')
            ->setPageTitle(Crud::PAGE_DETAIL, '🔎 Détails des demandes')
            ->setPageTitle(Crud::PAGE_NEW, '➕ Nouvelle demande')
            ->setPageTitle(Crud::PAGE_EDIT, '✏️ Modifier la demande')
            ->setDefaultSort(['createdAt' => 'DESC']);
    }

    public function configureFields(string $pageName): array
    {
        return [

            TextField::new('nom', 'Nom')->setColumns(6),
            TextField::new('prenom', 'Prénom')->setColumns(6),
            TextField::new('statutAgent', 'Statut')->setColumns(6),
            TextField::new('corps', 'Corps')->setColumns(6),
            TextField::new('grade', 'Grade')->setColumns(6),

            ChoiceField::new('statut', 'Statut de la demande')
                ->setChoices([
                    'En attente' => 'en_attente',
                    'Validée' => 'Validee',
                    'Refusée' => 'refusee',
                ])
                ->setColumns(6),

            ChoiceField::new('typeDemande', 'Type de demande')
                ->setChoices([
                    'Arrivée' => 'arrivee',
                    'Départ' => 'depart',
                ])
                ->setColumns(6),

            TextField::new('ministereOrigine', 'Ministère d’origine')->onlyOnForms(),
            TextField::new('directionOrigine', 'Direction d’origine')->onlyOnForms(),
            TextField::new('serviceOrigine', 'Service d’origine')->onlyOnForms(),
            TextField::new('serviceActuel', 'Service actuel')->onlyOnForms(),

            DateField::new('dateDepart', 'Date de départ')->onlyOnForms(),
            TextField::new('motifDepart', 'Motif')->onlyOnForms(),
            TextField::new('natureMutation', 'Nature de mutation')->onlyOnForms(),

            DateField::new('datePrisePoste', 'Date de prise de poste')->onlyOnForms(),
            TextField::new('serviceAffectation', 'Service d’affectation')->onlyOnForms(),
            TextField::new('siteGeographique', 'Site')->onlyOnForms(),
            TextField::new('bureau', 'Bureau')->onlyOnForms(),
            TextField::new('fonction', 'Fonction')->onlyOnForms(),

            BooleanField::new('posteRemplacement', 'Poste de remplacement')->onlyOnForms(),
            BooleanField::new('posteCreation', 'Poste en création')->onlyOnForms(),
            TextField::new('prenomRemplace', 'Prénom remplacé')->onlyOnForms(),
            TextField::new('nomRemplace', 'Nom remplacé')->onlyOnForms(),

            BooleanField::new('besoinMobilier', 'Mobilier')->onlyOnForms(),
            BooleanField::new('besoinFournitures', 'Fournitures')->onlyOnForms(),
            BooleanField::new('besoinInformatique', 'Informatique')->onlyOnForms(),

            ChoiceField::new('carteANTS', 'Carte ANTS')
                ->setChoices(['Oui' => 'oui', 'Non' => 'non'])
                ->onlyOnForms(),

            ChoiceField::new('carteAchats', 'Carte Achats')
                ->setChoices(['Oui' => 'oui', 'Non' => 'non'])
                ->onlyOnForms(),

            ChoiceField::new('chargeVoyages', 'Chargé voyages')
                ->setChoices(['Oui' => 'oui', 'Non' => 'non'])
                ->onlyOnForms(),

            ChoiceField::new('correspondantBudgetaire', 'Correspondant budgétaire')
                ->setChoices(['Oui' => 'oui', 'Non' => 'non'])
                ->onlyOnForms(),

            ChoiceField::new('encadreAgents', 'Encadre des agents')
                ->setChoices(['Oui' => 'oui', 'Non' => 'non'])
                ->onlyOnForms(),

            ChoiceField::new('utiliseChorus', 'Utilise Chorus')
                ->setChoices(['Oui' => 'oui', 'Non' => 'non'])
                ->onlyOnForms(),

            TextareaField::new('commentaire', 'Commentaire admin')->hideOnIndex(),

            DateTimeField::new('createdAt')->hideOnForm(),
            AssociationField::new('createdBy')->hideOnForm(),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions;
    }

    public function __construct(private Security $security) {}

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof DemandeMobilite) {
            return;
        }

        // Automatiquement remplir createdAt et createdBy
        $entityInstance->setCreatedAt(new DateTimeImmutable());
        $entityInstance->setCreatedBy($this->security->getUser());

        parent::persistEntity($entityManager, $entityInstance);
    }


}
