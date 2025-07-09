<?php

namespace App\Controller\Admin;

use App\Entity\DomaineService;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class DomaineServiceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return DomaineService::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('📚 Domaine')
            ->setEntityLabelInPlural('📚 Domaines')
            ->setPageTitle(Crud::PAGE_INDEX, 'Gestion des domaines fonctionnels')
            ->setDefaultSort(['nom' => 'ASC']);
    }

    public function configureFields(string $pageName): array
    {
        return [
            TextField::new('nom', '🏷️ Nom du domaine')
                ->setHelp('Exemple : RH, Informatique, Moyens généraux...'),
            AssociationField::new('services', '🏢 Services concernés')
                ->setFormTypeOption('by_reference', false)
                ->setHelp('Services liés à ce domaine'),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions;
    }
}
