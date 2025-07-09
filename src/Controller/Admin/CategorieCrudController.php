<?php

namespace App\Controller\Admin;

use App\Entity\Categorie;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CategorieCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Categorie::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('📂 Catégorie')
            ->setEntityLabelInPlural('📂 Catégories')
            ->setPageTitle(Crud::PAGE_INDEX, 'Gestion des catégories de personnel')
            ->setDefaultSort(['nom' => 'ASC']);
    }

    public function configureFields(string $pageName): array
    {
        return [
            TextField::new('nom', '📂 Nom')
                ->setHelp('Exemple : A, B, C, Contractuel, Vacataire...'),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions;
    }
}
