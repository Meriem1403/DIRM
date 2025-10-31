<?php

namespace App\Controller\Admin;

use App\Entity\Goudurix;
use App\Entity\User;
use App\Entity\Service;
use App\Entity\Lieu;
use App\Repository\GoudurixRepository;
use App\Repository\ServiceRepository;
use App\Repository\UserRepository;
use App\Repository\LieuRepository;
use EasyCorp\Bundle\EasyAdminBundle\Factory\EntityFactory;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
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
use Symfony\Component\HttpFoundation\Response;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;

class GoudurixCrudController extends AbstractCrudController
{
    public function __construct(
        private readonly GoudurixRepository $repository,
        private readonly EntityFactory $entityFactory,
        private readonly AdminUrlGenerator $adminUrlGenerator,
        private readonly ServiceRepository $serviceRepository,
        private readonly UserRepository $userRepository,
        private readonly LieuRepository $lieuRepository
    ) {
    }

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
            ->overrideTemplate('crud/detail', 'admin/goudurix_detail.html.twig');
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->onlyOnDetail();
        
        // === INFORMATIONS GÉNÉRALES ===
        yield TextField::new('titre', 'Nom du risque')
            ->setRequired(true)
            ->setHelp('Titre descriptif du risque identifié (ex: Risque électrique, Risque de chute, etc.)');
        
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
        
        yield TextField::new('categorie', 'Catégorie')
            ->setHelp('Catégorie du risque (ex: Sécurité, Santé, Environnement, Ergonomie)')
            ->hideOnIndex();
        
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
        
        yield AssociationField::new('service', 'Service / activité concernée')
            ->setRequired(true)
            ->setHelp('Service concerné par le risque')
            ->autocomplete();
        
        yield AssociationField::new('lieu', 'Lieu')
            ->setHelp('Lieu où le risque a été identifié')
            ->autocomplete()
            ->hideOnIndex();
        
        yield DateField::new('dateDetection', 'Date')
            ->setRequired(true)
            ->setHelp('Date à laquelle le risque a été identifié');
        
        yield AssociationField::new('createur', 'Rédigée par')
            ->setHelp('Personne ayant créé cette fiche de risque')
            ->autocomplete()
            ->hideOnIndex();
        
        // === SITUATION À RISQUE ===
        yield TextareaField::new('description', 'Situation à risque')
            ->setRequired(true)
            ->setHelp('Décris simplement dans quel contexte le risque apparaît. Exemples : Lors de la manipulation du robot branché au secteur, Lors de la maintenance du bras motorisé, En présence d\'enfants pendant les tests.')
            ->setNumOfRows(4);
        
        // === ORIGINE DU RISQUE ===
        yield TextareaField::new('source', 'Origine du risque')
            ->setHelp('Qu\'est-ce qui cause le danger ? Exemples : câble dénudé, surface glissante, moteur chaud, petites pièces détachables…')
            ->setNumOfRows(3)
            ->hideOnIndex();
        
        // === PERSONNES EXPOSÉES ===
        yield AssociationField::new('responsable', 'Responsable du risque')
            ->setRequired(true)
            ->setHelp('Personne responsable du suivi du risque')
            ->autocomplete();
        
        yield AssociationField::new('observateurs', 'Personnes exposées')
            ->setHelp('Qui pourrait être touché ? Enfants, enseignants, techniciens, visiteurs, etc.')
            ->autocomplete()
            ->hideOnIndex();
        
        // === CONSÉQUENCES POSSIBLES ===
        yield IntegerField::new('probabilite', 'Probabilité (1-5)')
            ->setHelp('Probabilité d\'occurrence du risque (1=très faible, 5=très élevée)')
            ->setFormTypeOption('attr', ['min' => 1, 'max' => 5])
            ->hideOnIndex();
        
        yield IntegerField::new('gravite', 'Gravité / Conséquences possibles (1-5)')
            ->setHelp('Gravité des conséquences (1=très faible, 5=très élevée). Que peut-il arriver si rien n\'est fait ? Exemples : choc électrique, brûlure, chute, coupure, stress, panne, blessure légère/grave…')
            ->setFormTypeOption('attr', ['min' => 1, 'max' => 5])
            ->hideOnIndex();
        
        yield IntegerField::new('scoreRisque', 'Score de Risque')
            ->setHelp('Score calculé automatiquement (Probabilité × Gravité)')
            ->onlyOnDetail();
        
        // === MESURES DE PRÉVENTION ===
        yield TextareaField::new('mesuresPreventives', 'Mesures préventives')
            ->setHelp('Comment réduire ou éviter ce risque ? Exemples : Débrancher avant manipulation, Mettre un carter de protection, Porter des gants / lunettes, Éloigner les enfants pendant la phase de test, Vérifier les branchements avant allumage')
            ->setNumOfRows(4)
            ->hideOnIndex();
        
        yield TextareaField::new('mesuresCorrectives', 'Mesures correctives')
            ->setHelp('Actions correctives mises en place')
            ->setNumOfRows(3)
            ->hideOnIndex();
        
        yield TextareaField::new('mesureEnCours', 'Mesure en cours')
            ->setHelp('Décrivez la mesure préventive ou corrective mise en place ou proposée')
            ->setNumOfRows(3)
            ->hideOnIndex();

        yield BooleanField::new('mesureMiseEnPlace', 'Mesure mise en place')
            ->setHelp('Cochez si la mesure est effectivement mise en place')
            ->hideOnIndex();
        
        // === REMARQUES / SUIVI ===
        yield TextareaField::new('commentaires', 'Remarques / suivi')
            ->setHelp('Notes complémentaires, observations, améliorations possibles.')
            ->setNumOfRows(3)
            ->hideOnIndex();
        
        yield TextareaField::new('retourAction', 'Retour d\'Action')
            ->setHelp('Retour du chef de service sur les actions mises en place')
            ->setNumOfRows(3)
            ->hideOnIndex();

        yield AssociationField::new('auteurRetour', 'Auteur du Retour')
            ->setCrudController(UserCrudController::class)
            ->hideOnIndex();

        yield DateTimeField::new('dateRetour', 'Date du Retour')
            ->hideOnIndex();
        
        yield DateField::new('dateResolution', 'Date de Résolution')
            ->setHelp('Date de résolution du risque (si applicable)')
            ->hideOnIndex();
        
        // === INFORMATIONS SYSTÈME ===
        yield DateTimeField::new('createdAt', 'Créé le')
            ->onlyOnDetail();
        
        yield DateTimeField::new('updatedAt', 'Modifié le')
            ->onlyOnDetail();
    }

    public function configureActions(Actions $actions): Actions
    {
        $indexTableUrl = $this->adminUrlGenerator
            ->setController(self::class)
            ->setAction('index')
            ->set('view', 'table')
            ->generateUrl();

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
            })
            // Forcer "Retour à la liste" à renvoyer vers la vue table
            ->update(Crud::PAGE_DETAIL, Action::INDEX, function (Action $action) use ($indexTableUrl) {
                return $action->linkToUrl($indexTableUrl);
            })
            // Sur les pages EDIT et NEW, l'action INDEX peut ne pas exister: on l'ajoute puis on la met à jour
            ->add(Crud::PAGE_EDIT, Action::INDEX)
            ->update(Crud::PAGE_EDIT, Action::INDEX, function (Action $action) use ($indexTableUrl) {
                return $action->linkToUrl($indexTableUrl);
            })
            ->add(Crud::PAGE_NEW, Action::INDEX)
            ->update(Crud::PAGE_EDIT, Action::INDEX, function (Action $action) use ($indexTableUrl) {
                return $action->linkToUrl($indexTableUrl);
            })
            ->update(Crud::PAGE_NEW, Action::INDEX, function (Action $action) use ($indexTableUrl) {
                return $action->linkToUrl($indexTableUrl);
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
            ->addCssFile('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css');
    }

    public function index(AdminContext $context): KeyValueStore
    {
        // Le template parent @EasyAdmin/crud/index.html.twig appelle getEntity() quelque part
        // On doit contourner en utilisant directement le template sans passer par le parent
        $request = $context->getRequest();
        $crudAction = $request->query->get('crudAction');
        $entityId = $request->query->get('entityId');
        
        // Détecter si on est sur l'index : pas d'entityId ET (action est 'index' ou null/vide)
        $isIndex = empty($entityId) && ($crudAction === 'index' || $crudAction === null || $crudAction === '');
        $view = $request->query->get('view', 'cards'); // 'cards' (par défaut) ou 'table'
        
        // Si on est sur la page index, on ne peut pas utiliser getEntity() - gérer nous-mêmes
        if ($isIndex) {
            // Si la vue demandée est la table standard, rendre le template EasyAdmin standard
            if ($view === 'table') {
                // Récupérer les données nécessaires pour les filtres
                $services = $this->serviceRepository->findBy(['actif' => true], ['nom' => 'ASC']);
                $responsables = $this->userRepository->findAll();
                $lieux = $this->lieuRepository->findAll();
                
                // Déléguer à EasyAdmin mais ajouter nos variables
                $result = parent::index($context);
                
                // Si c'est un KeyValueStore, ajouter nos variables
                if (method_exists($result, 'get')) {
                    $templateParameters = $result->get('templateParameters') ?? [];
                    $templateParameters['services'] = $services;
                    $templateParameters['responsables'] = $responsables;
                    $templateParameters['lieux'] = $lieux;
                    $result->set('templateParameters', $templateParameters);
                }
                
                return $result;
            }
            // Récupérer les paramètres de filtres depuis la requête
            $filters = $request->query->all()['filters'] ?? [];
            
            // Construire les critères de recherche
            $criteria = [];
            
            if (!empty($filters['niveauRisque'])) {
                $criteria['niveauRisque'] = $filters['niveauRisque'];
            }
            
            if (!empty($filters['statut'])) {
                $criteria['statut'] = $filters['statut'];
            }
            
            if (!empty($filters['service'])) {
                $serviceId = is_array($filters['service']) ? $filters['service'][0] : $filters['service'];
                $service = $this->serviceRepository->find($serviceId);
                if ($service) {
                    $criteria['service'] = $service;
                }
            }
            
            if (!empty($filters['responsable'])) {
                $responsableId = is_array($filters['responsable']) ? $filters['responsable'][0] : $filters['responsable'];
                $responsable = $this->userRepository->find($responsableId);
                if ($responsable) {
                    $criteria['responsable'] = $responsable;
                }
            }
            
            if (!empty($filters['lieu'])) {
                $lieuId = is_array($filters['lieu']) ? $filters['lieu'][0] : $filters['lieu'];
                $lieu = $this->lieuRepository->find($lieuId);
                if ($lieu) {
                    $criteria['lieu'] = $lieu;
                }
            }
            
            // Récupérer les entités avec les filtres appliqués
            $goudurixEntities = $this->repository->findBy($criteria, ['createdAt' => 'DESC']);
            
            // Créer des objets compatibles avec le template (qui attend entity.instance)
            $entities = [];
            foreach ($goudurixEntities as $entityInstance) {
                // Créer un objet simple avec une propriété instance
                $entityDto = new \stdClass();
                $entityDto->instance = $entityInstance;
                $entities[] = $entityDto;
            }
            
            $crud = $context->getCrud();
            
            // Générer les URLs pour chaque entité
            foreach ($entities as $entity) {
                $entity->detailUrl = $this->adminUrlGenerator
                    ->setController(self::class)
                    ->setAction('detail')
                    ->setEntityId($entity->instance->getId())
                    ->generateUrl();
                
                $entity->editUrl = $this->adminUrlGenerator
                    ->setController(self::class)
                    ->setAction('edit')
                    ->setEntityId($entity->instance->getId())
                    ->generateUrl();
                
                $entity->deleteUrl = $this->adminUrlGenerator
                    ->setController(self::class)
                    ->setAction('delete')
                    ->setEntityId($entity->instance->getId())
                    ->generateUrl();
            }
            
            // Récupérer les données pour les filtres
            $services = $this->serviceRepository->findBy(['actif' => true], ['nom' => 'ASC']);
            $responsables = $this->userRepository->findAll();
            $lieux = $this->lieuRepository->findAll();
            
            return KeyValueStore::new([
                'templateName' => 'crud/index',
                'templatePath' => 'admin/goudurix_cards.html.twig',
                'templateParameters' => [
                    'entities' => $entities,
                    'crud' => $crud,
                    'filters' => $filters ?? [],
                    'services' => $services,
                    'responsables' => $responsables,
                    'lieux' => $lieux,
                ],
            ]);
        }
        
        // Si ce n'est pas l'index, laisser EasyAdmin gérer normalement
        // (cela ne devrait normalement pas arriver car detail/edit ont leurs propres méthodes)
        // Autres pages (detail/edit/new...) : déléguer à EasyAdmin et rendre le template résultant
        return parent::index($context);
    }

    // Pas besoin de surcharger new(), detail() et edit() - EasyAdmin les gère automatiquement
}
