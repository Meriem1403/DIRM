<?php

namespace App\Controller\Admin;

use App\Entity\Lieu;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class LieuCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Lieu::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('un lieu')
            ->setEntityLabelInPlural('des lieux')
            ->setPageTitle(Crud::PAGE_INDEX, '📍Gestion des lieux géographiques')
            ->setPageTitle(Crud::PAGE_EDIT, '📍 Modifier un lieu')
            ->setPageTitle(Crud::PAGE_NEW, '📍Créer un lieu')
            ->setPageTitle(Crud::PAGE_DETAIL, '📍 Détails du lieu')
            ->setDefaultSort(['nom' => 'ASC']);
    }

    public function configureFields(string $pageName): array
    {
        return [
            TextField::new('nom', '🏷️ Nom du lieu')
                ->setHelp('Exemple : Marseille, Toulon, Ajaccio...'),
            AssociationField::new('services', '🏢 Services implantés')
                ->setFormTypeOption('by_reference', false)
                ->setHelp('Services liés à ce lieu géographique'),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions;
    }
}
