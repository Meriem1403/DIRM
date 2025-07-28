<?php

namespace App\Controller\Admin;

use App\Entity\ApplicationCerbere;
use App\Form\ProfilCerbereType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ApplicationCerbereCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ApplicationCerbere::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('des applications')
            ->setEntityLabelInSingular('d\'application')
            ->setPageTitle(Crud::PAGE_INDEX, '📦 Gestion des applications Cerbère')
            ->setPageTitle(Crud::PAGE_EDIT, '📦 Modifier l\'application')
            ->setPageTitle(Crud::PAGE_NEW, '📦 Ajouter une application')
            ->setPageTitle(Crud::PAGE_DETAIL, '📦 Détails de l\'application')
            ->setDefaultSort(['nom' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('code', 'Code'),
            TextField::new('nom', 'Nom'),
            TextareaField::new('description', 'Description'),


        CollectionField::new('profils')
                ->setEntryType(ProfilCerbereType::class)
                ->allowAdd()
                ->allowDelete()
                ->setFormTypeOption('by_reference', false)
                ->setEntryIsComplex(),
        ];
    }
}
