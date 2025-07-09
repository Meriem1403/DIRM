<?php

namespace App\Controller\Admin;

use App\Entity\Role;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class RoleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Role::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('🔐 Rôle')
            ->setEntityLabelInPlural('🔐 Rôles')
            ->setDefaultSort(['code' => 'ASC'])
            ->setPageTitle(Crud::PAGE_INDEX, '🔐 Gestion des rôles')
            ->setPageTitle(Crud::PAGE_EDIT, 'Modifier un rôle')
            ->setPageTitle(Crud::PAGE_NEW, 'Créer un rôle');
    }

    public function configureFields(string $pageName): array
    {
        return [
            IdField::new('id', 'ID')->hideOnForm(),
            TextField::new('code', '🆔 Code')
                ->setHelp('Ex : ROLE_ADMIN, ROLE_AGENT...'),

            TextField::new('label', '🔖 Nom affiché')
                ->setHelp('Nom lisible dans les menus, ex : Administrateur, Agent...'),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable(Action::DELETE);
    }
}
