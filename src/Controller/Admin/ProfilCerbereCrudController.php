<?php

namespace App\Controller\Admin;

use App\Entity\ProfilCerbere;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;

class ProfilCerbereCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ProfilCerbere::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('🔐 Profil Cerbère')
            ->setEntityLabelInPlural('🔐 Profils Cerbère')
            ->setPageTitle(Crud::PAGE_INDEX, 'Liste des profils Cerbère')
            ->setDefaultSort(['nom' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addPanel('🧾 Informations du profil'),
            AssociationField::new('application', 'Application liée')->setColumns(6),
            TextField::new('nom', 'Identifiant du profil')->setColumns(6),
            TextareaField::new('description', 'Description détaillée')->setColumns(12),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions;
    }
}
