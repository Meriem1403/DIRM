<?php

namespace App\Controller\Admin;

use App\Entity\DemandeHabilitationCerbere;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;

class DemandeHabilitationCerbereCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return DemandeHabilitationCerbere::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('une demande d\'abilitation')
            ->setEntityLabelInPlural('des demandes d\'abilitation')
            ->setPageTitle(Crud::PAGE_INDEX, 'Liste des demandes d\'habilitation Cerbère')
            ->setPageTitle(Crud::PAGE_DETAIL, 'Détails des demandes')
            ->setPageTitle(Crud::PAGE_NEW, 'Nouvelle demande')
            ->setPageTitle(Crud::PAGE_EDIT, 'Modifier la demande')
            ->setDefaultSort(['dateSoumission' => 'DESC']);
    }

    public function configureFields(string $pageName): array
    {
        return [
            AssociationField::new('agent', 'Agent concerné')->setColumns(6),
            AssociationField::new('demandeur', 'Demandeur')->setColumns(6),

            AssociationField::new('applications', 'Applications')
                ->setFormTypeOption('by_reference', false)
                ->onlyOnForms(),

            ArrayField::new('applications', 'Applications')->onlyOnIndex(),

            AssociationField::new('profils', 'Profils')
                ->setFormTypeOption('by_reference', false)
                ->onlyOnForms(),

            ArrayField::new('profils', 'Profils')->onlyOnIndex(),

            TextField::new('reglePortee', 'Règle de portée')->setColumns(6),
            TextareaField::new('restrictions', 'Restrictions éventuelles')->setColumns(12),

            DateTimeField::new('dateSoumission', 'Soumise le')->setColumns(6)->hideOnForm(),

            ChoiceField::new('statut', 'Statut')->setChoices([
                'En attente' => 'en_attente',
                'Validée' => 'validee',
                'Refusée' => 'refusee',
            ])->setColumns(6),

            AssociationField::new('validePar', 'Validée par')->hideWhenCreating(),
            DateTimeField::new('dateValidation', 'Date validation')->hideWhenCreating(),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions;
    }
}
