<?php

namespace App\Controller\Admin;

use App\Entity\Service;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use Doctrine\ORM\EntityManagerInterface;

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
            BooleanField::new('actif', '✅ Service actif')
                ->setHelp('Décochez pour archiver le service (au lieu de le supprimer)'),
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
        return $actions
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                return $action->setIcon('fa fa-plus')->setLabel('Nouveau Service');
            })
            ->update(Crud::PAGE_INDEX, Action::EDIT, function (Action $action) {
                return $action->setIcon('fa fa-edit');
            })
            ->update(Crud::PAGE_INDEX, Action::DELETE, function (Action $action) {
                return $action->setIcon('fa fa-archive')->setLabel('Archiver');
            })
            ->update(Crud::PAGE_INDEX, Action::DETAIL, function (Action $action) {
                return $action->setIcon('fa fa-eye');
            });
    }

    public function deleteEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        // Au lieu de supprimer, marquer comme inactif
        $entityInstance->setActif(false);
        $entityManager->persist($entityInstance);
        $entityManager->flush();
        
        // Ajouter un message flash
        $this->addFlash('success', 'Le service "' . $entityInstance->getNom() . '" a été archivé avec succès.');
    }

}
