<?php

namespace App\Controller\Admin;

use App\Entity\Goudurix;
use App\Entity\User;
use App\Entity\Service;
use App\Entity\Lieu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use Doctrine\ORM\EntityManagerInterface;

class GoudurixCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Goudurix::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Risque')
            ->setEntityLabelInPlural('Risques Goudurix')
            ->setPageTitle('index', 'Gestion des Risques - DUERP')
            ->setPageTitle('new', 'Nouveau Risque')
            ->setPageTitle('edit', 'Modifier le Risque')
            ->setPageTitle('detail', 'Détail du Risque')
            ->setDefaultSort(['createdAt' => 'DESC'])
            ->setPaginatorPageSize(12)
            ->setHelp('index', 'Gestion des risques professionnels et DUERP (Document Unique d\'Évaluation des Risques Professionnels)')
            ->overrideTemplate('crud/index', 'admin/goudurix_cards.html.twig')
            ->overrideTemplate('crud/detail', 'admin/goudurix_detail.html.twig');
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->onlyOnDetail();
        
        yield TextField::new('titre', 'Titre du Risque')
            ->setRequired(true)
            ->setHelp('Titre descriptif du risque identifié');
        
        yield TextareaField::new('description', 'Description')
            ->setRequired(true)
            ->setHelp('Description détaillée du risque')
            ->setNumOfRows(4);
        
        yield ChoiceField::new('niveauRisque', 'Niveau de Risque')
            ->setRequired(true)
            ->setChoices([
                'Faible' => 'faible',
                'Moyen' => 'moyen',
                'Élevé' => 'élevé',
                'Critique' => 'critique'
            ])
            ->renderAsBadges([
                'faible' => 'success',
                'moyen' => 'warning',
                'élevé' => 'danger',
                'critique' => 'dark'
            ])
            ->setHelp('Niveau de criticité du risque');
        
        yield ChoiceField::new('statut', 'Statut')
            ->setRequired(true)
            ->setChoices([
                'En cours' => 'en_cours',
                'Traité' => 'traité',
                'Surveillé' => 'surveillé',
                'Archivé' => 'archivé'
            ])
            ->renderAsBadges([
                'en_cours' => 'primary',
                'traité' => 'success',
                'surveillé' => 'warning',
                'archivé' => 'secondary'
            ])
            ->setHelp('État actuel du traitement du risque');
        
        yield TextField::new('categorie', 'Catégorie')
            ->setHelp('Catégorie du risque (ex: Sécurité, Santé, Environnement)')
            ->hideOnIndex();
        
        yield TextField::new('source', 'Source')
            ->setHelp('Source de détection du risque')
            ->hideOnIndex();
        
        yield IntegerField::new('probabilite', 'Probabilité (1-5)')
            ->setHelp('Probabilité d\'occurrence du risque (1=très faible, 5=très élevée)')
            ->setFormTypeOption('attr', ['min' => 1, 'max' => 5])
            ->hideOnIndex();
        
        yield IntegerField::new('gravite', 'Gravité (1-5)')
            ->setHelp('Gravité des conséquences (1=très faible, 5=très élevée)')
            ->setFormTypeOption('attr', ['min' => 1, 'max' => 5])
            ->hideOnIndex();
        
        yield IntegerField::new('scoreRisque', 'Score de Risque')
            ->setHelp('Score calculé automatiquement (Probabilité × Gravité)')
            ->onlyOnDetail();
        
        yield DateField::new('dateDetection', 'Date de Détection')
            ->setRequired(true)
            ->setHelp('Date à laquelle le risque a été identifié');
        
        yield DateField::new('dateResolution', 'Date de Résolution')
            ->setHelp('Date de résolution du risque (si applicable)')
            ->hideOnIndex();
        
        yield TextareaField::new('mesuresPreventives', 'Mesures Préventives')
            ->setHelp('Actions préventives mises en place')
            ->setNumOfRows(3)
            ->hideOnIndex();
        
        yield TextareaField::new('mesuresCorrectives', 'Mesures Correctives')
            ->setHelp('Actions correctives mises en place')
            ->setNumOfRows(3)
            ->hideOnIndex();
        
        yield TextareaField::new('commentaires', 'Commentaires')
            ->setHelp('Commentaires supplémentaires')
            ->setNumOfRows(2)
            ->hideOnIndex();
        
        yield AssociationField::new('responsable', 'Responsable')
            ->setRequired(true)
            ->setHelp('Personne responsable du suivi du risque')
            ->autocomplete();
        
        yield AssociationField::new('createur', 'Créateur')
            ->setHelp('Personne ayant créé l\'enregistrement')
            ->autocomplete()
            ->hideOnIndex();
        
        yield AssociationField::new('service', 'Service')
            ->setRequired(true)
            ->setHelp('Service concerné par le risque')
            ->autocomplete();
        
        yield AssociationField::new('lieu', 'Lieu')
            ->setHelp('Lieu où le risque a été identifié')
            ->autocomplete()
            ->hideOnIndex();
        
        yield AssociationField::new('observateurs', 'Observateurs')
            ->setHelp('Personnes chargées de surveiller le risque')
            ->autocomplete()
            ->hideOnIndex();
        
        yield DateTimeField::new('createdAt', 'Créé le')
            ->onlyOnDetail();
        
        yield DateTimeField::new('updatedAt', 'Modifié le')
            ->onlyOnDetail();

        // Section Mesures et Retours d'Action
        yield TextareaField::new('mesureEnCours', 'Mesure en Cours')
            ->setHelp('Décrivez la mesure préventive ou corrective mise en place ou proposée')
            ->hideOnIndex();

        yield BooleanField::new('mesureMiseEnPlace', 'Mesure Mise en Place')
            ->setHelp('Cochez si la mesure est effectivement mise en place')
            ->hideOnIndex();

        yield TextareaField::new('retourAction', 'Retour d\'Action')
            ->setHelp('Retour du chef de service sur les actions mises en place')
            ->hideOnIndex();

        yield AssociationField::new('auteurRetour', 'Auteur du Retour')
            ->setCrudController(UserCrudController::class)
            ->hideOnIndex();

        yield DateTimeField::new('dateRetour', 'Date du Retour')
            ->hideOnIndex();
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                return $action->setIcon('fa fa-plus')->setLabel('Nouveau Risque');
            })
            ->update(Crud::PAGE_INDEX, Action::EDIT, function (Action $action) {
                return $action->setIcon('fa fa-edit');
            })
            ->update(Crud::PAGE_INDEX, Action::DELETE, function (Action $action) {
                return $action->setIcon('fa fa-trash');
            })
            ->update(Crud::PAGE_INDEX, Action::DETAIL, function (Action $action) {
                return $action->setIcon('fa fa-eye');
            });
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(ChoiceFilter::new('niveauRisque', 'Niveau de Risque')
                ->setChoices([
                    'Faible' => 'faible',
                    'Moyen' => 'moyen',
                    'Élevé' => 'élevé',
                    'Critique' => 'critique'
                ]))
            ->add(ChoiceFilter::new('statut', 'Statut')
                ->setChoices([
                    'En cours' => 'en_cours',
                    'Traité' => 'traité',
                    'Surveillé' => 'surveillé',
                    'Archivé' => 'archivé'
                ]))
            ->add(EntityFilter::new('service', 'Service'))
            ->add(EntityFilter::new('responsable', 'Responsable'))
            ->add(EntityFilter::new('lieu', 'Lieu'))
            ->add(DateTimeFilter::new('dateDetection', 'Date de Détection'))
            ->add(DateTimeFilter::new('dateResolution', 'Date de Résolution'));
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        // Calculer automatiquement le score de risque
        if ($entityInstance instanceof Goudurix) {
            $entityInstance->calculateScoreRisque();
            $entityInstance->setUpdatedAt(new \DateTime());
        }
        
        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        // Recalculer le score de risque lors de la mise à jour
        if ($entityInstance instanceof Goudurix) {
            $entityInstance->calculateScoreRisque();
            $entityInstance->setUpdatedAt(new \DateTime());
        }
        
        parent::updateEntity($entityManager, $entityInstance);
    }

    public function configureAssets(Assets $assets): Assets
    {
        return $assets
            ->addCssFile('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css')
            ->addCssFile('https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css');
    }
}
