<?php

namespace App\Controller\Admin;

use App\Entity\NomRisque;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Doctrine\ORM\EntityManagerInterface;

class NomRisqueCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return NomRisque::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('un nom de risque')
            ->setEntityLabelInPlural('des noms de risques')
            ->setPageTitle(Crud::PAGE_INDEX, '📋 Gestion des noms de risques')
            ->setPageTitle(Crud::PAGE_DETAIL, '📋 Détail du nom de risque')
            ->setPageTitle(Crud::PAGE_EDIT, '📋 Modifier le nom de risque')
            ->setPageTitle(Crud::PAGE_NEW, '📋 Créer un nom de risque')
            ->setDefaultSort(['nom' => 'ASC']);
    }

    public function configureFields(string $pageName): array
    {
        return [
            IdField::new('id', 'Numéro')
                ->onlyOnIndex(),
            TextField::new('nom', 'Nom du risque')
                ->setRequired(true)
                ->setHelp('Exemple : Risque Incendie, Risque Amiante, Risque Électrique...'),
            TextareaField::new('description', 'Description')
                ->hideOnIndex()
                ->setNumOfRows(3)
                ->setHelp('Description optionnelle du type de risque'),
            BooleanField::new('actif', 'Actif')
                ->setHelp('Décochez pour désactiver ce nom de risque'),
            AssociationField::new('risques', 'Risques associés')
                ->onlyOnDetail(),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                return $action->setIcon('fa fa-plus')->setLabel('Nouveau Nom de Risque');
            })
            ->update(Crud::PAGE_INDEX, Action::EDIT, function (Action $action) {
                return $action->setIcon('fa fa-edit');
            })
            ->update(Crud::PAGE_INDEX, Action::DELETE, function (Action $action) {
                return $action->setIcon('fa fa-archive')->setLabel('Désactiver');
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
        $this->addFlash('success', 'Le nom de risque "' . $entityInstance->getNom() . '" a été désactivé avec succès.');
    }
}

