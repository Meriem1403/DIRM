<?php

namespace App\Controller\Admin;

use App\Entity\Service;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class ServiceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Service::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('un service')
            ->setEntityLabelInPlural('des services')
            ->setPageTitle(Crud::PAGE_INDEX, '🏢 Gestion des services')
            ->setPageTitle(Crud::PAGE_DETAIL, '🏢 Détail du service')
            ->setPageTitle(Crud::PAGE_EDIT, '🏢 Modifier le service')
            ->setPageTitle(Crud::PAGE_NEW, '🏢 Création d\'un service')

            ->setDefaultSort(['nom' => 'ASC']);
    }

    public function configureFields(string $pageName): array
    {
        return [
            TextField::new('nom', '🏷️ Nom du service'),
            AssociationField::new('domaines', '🌐 Domaines')
                ->setFormTypeOption('by_reference', false)
                ->setHelp('Lié aux domaines fonctionnels comme RH, Informatique...'),
            AssociationField::new('lieux', '📍 Lieux')
                ->setFormTypeOption('by_reference', false)
                ->setHelp('Sites géographiques concernés par ce service'),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions;
    }

}
