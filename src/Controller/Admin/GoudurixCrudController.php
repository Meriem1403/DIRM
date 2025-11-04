<?php

namespace App\Controller\Admin;

use App\Entity\Goudurix;
use App\Entity\User;
use App\Entity\Service;
use App\Entity\Lieu;
use App\Entity\CsvGoudurixRow;
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
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\EntityCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\ActionCollection;
use EasyCorp\Bundle\EasyAdminBundle\Factory\FilterFactory;
use Symfony\Component\HttpFoundation\RequestStack;

class GoudurixCrudController extends AbstractCrudController
{
    public function __construct(
        private readonly GoudurixRepository $repository,
        private readonly EntityFactory $entityFactory,
        private readonly AdminUrlGenerator $adminUrlGenerator,
        private readonly ServiceRepository $serviceRepository,
        private readonly UserRepository $userRepository,
        private readonly LieuRepository $lieuRepository,
        private readonly FilterFactory $filterFactory,
        private readonly RequestStack $requestStack
    ) {
    }

    public static function getEntityFqcn(): string
    {
        return Goudurix::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        $request = $this->requestStack->getCurrentRequest();
        $view = $request?->query->get('view', 'cards');
        
        $crudConfig = $crud
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
        
        // Pour la vue table CSV, utiliser un template personnalisé avec recherche
        if ($view === 'table') {
            $crudConfig->overrideTemplate('crud/index', 'admin/goudurix_table_index.html.twig');
        }
        
        return $crudConfig;
    }

    public function configureFields(string $pageName): iterable
    {
        // Afficher l'ID uniquement dans la vue index (table)
        yield IdField::new('id', 'Numéro')
            ->onlyOnIndex();
        
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
            ->setHelp('Catégorie du risque (ex: Sécurité, Santé, Environnement, Ergonomie)');
        
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
            ->autocomplete();
        
        yield DateField::new('dateDetection', 'Date')
            ->setRequired(true)
            ->setHelp('Date à laquelle le risque a été identifié');
        
        yield AssociationField::new('createur', 'Rédigée par')
            ->setHelp('Personne ayant créé cette fiche de risque')
            ->autocomplete()
            ->hideOnIndex();
        
        // === SITUATION À RISQUE ===
        yield TextareaField::new('description', 'Situation dangereuse')
            ->setRequired(true)
            ->setHelp('Décris simplement dans quel contexte le risque apparaît. Exemples : Lors de la manipulation du robot branché au secteur, Lors de la maintenance du bras motorisé, En présence d\'enfants pendant les tests.')
            ->setNumOfRows(4)
            ->hideOnIndex();
        
        // === ORIGINE DU RISQUE ===
        yield TextareaField::new('source', 'Origine du risque')
            ->setHelp('Qu\'est-ce qui cause le danger ? Exemples : câble dénudé, surface glissante, moteur chaud, petites pièces détachables…')
            ->setNumOfRows(3)
            ->hideOnIndex();
        
        // === PERSONNES EXPOSÉES ===
        yield AssociationField::new('responsable', 'Responsable')
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

        // === COLONNES ADDITIONNELLES DU CSV ===
        yield TextField::new('idaction', 'ID Action');
        
        yield TextField::new('idSituD', 'ID SituD');
        
        yield TextField::new('idDommage', 'ID Dommage');
        
        yield TextField::new('idMesure', 'ID Mesure');
        
        yield TextField::new('unite', 'Unité');
        
        yield TextField::new('numero', 'Numéro');
        
        yield TextareaField::new('dommage', 'Dommage')
            ->hideOnIndex()
            ->setNumOfRows(3);
        
        yield TextField::new('etat', 'État');
        
        yield TextField::new('periodicite', 'Périodicité');
        
        yield DateField::new('prochainControle', 'Prochain Contrôle');
        
        yield TextField::new('nPdf', 'N° PDF');
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
        $request = $this->requestStack->getCurrentRequest();
        $view = $request?->query->get('view', 'cards');
        
        // Si on est en vue table (CSV), ne configurer que les filtres simples qui fonctionnent
        // Les filtres Service, Responsable et Lieu seront gérés via une recherche personnalisée
        if ($view === 'table') {
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
                    ]));
            // Note: Les filtres Service, Responsable et Lieu sont désactivés car ils causent
            // des problèmes avec les données CSV. Une recherche texte manuelle est disponible
            // dans le code de filtrage (via les paramètres de requête).
        }
        
        // Pour la vue normale (cartes), utiliser les filtres EntityFilter standard
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
    
    /**
     * Extrait les valeurs uniques depuis le CSV pour les filtres
     * Utilise un cache statique pour éviter de relire le CSV à chaque fois
     */
    private function getCsvDataForFilters(): array
    {
        static $cache = null;
        
        if ($cache !== null) {
            return $cache;
        }
        
        $csvPath = __DIR__ . '/../../Controller/BD/extraction goudurix copie.csv';
        
        if (!file_exists($csvPath)) {
            $cache = ['services' => [], 'responsables' => [], 'lieux' => []];
            return $cache;
        }
        
        $handle = fopen($csvPath, 'r');
        if (!$handle) {
            $cache = ['services' => [], 'responsables' => [], 'lieux' => []];
            return $cache;
        }
        
        // Lire l'en-tête
        $headers = fgetcsv($handle, 0, ';');
        if (!$headers) {
            fclose($handle);
            $cache = ['services' => [], 'responsables' => [], 'lieux' => []];
            return $cache;
        }
        
        $headers = array_map('trim', $headers);
        if (!empty($headers[0])) {
            $headers[0] = preg_replace('/^\xEF\xBB\xBF/', '', $headers[0]);
        }
        
        $services = [];
        $responsables = [];
        $lieux = [];
        
        // Lire les données
        while (($row = fgetcsv($handle, 0, ';')) !== false) {
            if (count($headers) !== count($row)) {
                continue;
            }
            
            $data = array_combine($headers, $row);
            
            // Extraire les valeurs uniques (utiliser array_unique à la fin serait plus efficace)
            $service = trim($data['Service'] ?? '');
            if ($service && $service !== '') {
                $services[$service] = $service;
            }
            
            $responsable = trim($data['Responsable'] ?? '');
            if ($responsable && $responsable !== '') {
                $responsables[$responsable] = $responsable;
            }
            
            $lieu = trim($data['Site'] ?? '');
            if ($lieu && $lieu !== '') {
                $lieux[$lieu] = $lieu;
            }
        }
        
        fclose($handle);
        
        // Trier les tableaux
        ksort($services);
        ksort($responsables);
        ksort($lieux);
        
        // Ne pas limiter le nombre de choix - laisser toutes les valeurs disponibles
        // Limitation supprimée car elle peut causer des problèmes si les utilisateurs ne voient pas toutes les options
        
        $cache = [
            'services' => $services,
            'responsables' => $responsables,
            'lieux' => $lieux,
        ];
        
        // Debug: vérifier que les données sont bien présentes
        if (empty($cache['services']) && empty($cache['responsables']) && empty($cache['lieux'])) {
            error_log('GoudurixCrudController: Aucune donnée extraite du CSV pour les filtres');
        }
        
        return $cache;
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
        $request = $context->getRequest();
        $crudAction = $request->query->get('crudAction');
        $entityId = $request->query->get('entityId');
        
        // Détecter si on est sur l'index : pas d'entityId ET (action est 'index' ou null/vide)
        $isIndex = empty($entityId) && ($crudAction === 'index' || $crudAction === null || $crudAction === '');
        $view = $request->query->get('view', 'cards'); // 'cards' (par défaut) ou 'table'
        
        // Si vue table, charger depuis CSV et afficher dans un tableau simple
        if ($isIndex && $view === 'table') {
            return $this->indexFromCsvAsEntities($context);
        }
        
        // Si on est sur la page index en vue cards, gérer nous-mêmes
        if ($isIndex && $view !== 'table') {
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

    /**
     * Charge les données depuis le CSV et les affiche dans le tableau EasyAdmin standard
     */
    private function indexFromCsvAsEntities(AdminContext $context): KeyValueStore
    {
        $request = $context->getRequest();
        $csvPath = __DIR__ . '/../../Controller/BD/extraction goudurix copie.csv';
        
        if (!file_exists($csvPath)) {
            throw new \RuntimeException('Fichier CSV non trouvé: ' . $csvPath);
        }

        // Lire le CSV
        $handle = fopen($csvPath, 'r');
        if (!$handle) {
            throw new \RuntimeException('Impossible d\'ouvrir le fichier CSV');
        }

        // Lire l'en-tête
        $headers = fgetcsv($handle, 0, ';');
        if (!$headers) {
            fclose($handle);
            throw new \RuntimeException('Impossible de lire l\'en-tête du CSV');
        }
        $headers = array_map('trim', $headers);
        if (!empty($headers[0])) {
            $headers[0] = preg_replace('/^\xEF\xBB\xBF/', '', $headers[0]);
        }

        // Lire toutes les lignes et créer des objets Goudurix temporaires
        $entities = [];
        $rowIndex = 0;
        while (($row = fgetcsv($handle, 0, ';')) !== false) {
            if (count($headers) !== count($row)) {
                continue;
            }
            
            $data = array_combine($headers, $row);
            
            // Créer une entité Goudurix temporaire avec les données du CSV
            $entity = new Goudurix();
            // Utiliser ReflectionClass pour définir l'ID privé
            $reflection = new \ReflectionClass($entity);
            $idProperty = $reflection->getProperty('id');
            $idProperty->setAccessible(true);
            $idProperty->setValue($entity, $rowIndex + 1);
            $entity->setIdaction(trim($data['idaction'] ?? ''));
            $entity->setIdSituD(trim($data['ID_SituD'] ?? ''));
            $entity->setIdDommage(trim($data['ID_Dommage'] ?? ''));
            $entity->setIdMesure(trim($data['ID_Mesure'] ?? ''));
            $entity->setUnite(trim($data['Unité'] ?? ''));
            $entity->setTitre(trim($data['Risque'] ?? ''));
            $entity->setDescription(trim($data['Situation dangereuse'] ?? ''));
            $entity->setDommage(trim($data['Dommage'] ?? ''));
            $entity->setStatut(trim($data['Statut'] ?? ''));
            $entity->setNumero(trim($data['Numéro'] ?? ''));
            $entity->setEtat(trim($data['État'] ?? ''));
            $entity->setMesureEnCours(trim($data['Mesure'] ?? ''));
            $entity->setCommentaires(trim($data['Mention Spéciale'] ?? ''));
            $entity->setPeriodicite(trim($data['Périodicité'] ?? ''));
            $entity->setNPdf(trim($data['N_pdf'] ?? ''));
            
            if (!empty($data['Prochain contrôle'])) {
                try {
                    $prochainControle = \DateTime::createFromFormat('d/m/Y', trim($data['Prochain contrôle']));
                    if ($prochainControle) {
                        $entity->setProchainControle($prochainControle);
                    }
                } catch (\Exception $e) {
                }
            }
            
            // Stocker les données brutes pour les champs qui n'existent pas dans l'entité
            // Nettoyer les données : supprimer les retours à la ligne, espaces multiples, etc.
            $entity->_csvService = $this->cleanCsvValue($data['Service'] ?? '');
            $entity->_csvLieu = $this->cleanCsvValue($data['Site'] ?? '');
            $entity->_csvResponsable = $this->cleanCsvValue($data['Responsable'] ?? '');
            $entity->_csvPrioriteFinale = $this->cleanCsvValue($data['Priorité finale'] ?? '');
            
            $entities[] = $entity;
            $rowIndex++;
        }
        fclose($handle);
        
        // Vérifier qu'on a bien des entités
        if (empty($entities)) {
            throw new \RuntimeException('Aucune donnée trouvée dans le fichier CSV');
        }

        $totalBeforeFilter = count($entities);
        
        // Appliquer les filtres EasyAdmin
        $filtersConfig = $context->getCrud()->getFiltersConfig();
        
        // Récupérer les filtres appliqués depuis la requête EasyAdmin
        $appliedFilters = [];
        $filtersParam = $request->query->all()['filters'] ?? [];
        
        // Récupérer aussi les paramètres de recherche personnalisés (service, responsable, lieu)
        $searchService = $request->query->get('search_service', '');
        $searchResponsable = $request->query->get('search_responsable', '');
        $searchLieu = $request->query->get('search_lieu', '');
        
        // Nettoyer les valeurs de recherche AVANT de les utiliser dans la fermeture
        $searchServiceClean = $this->cleanCsvValue($searchService);
        $searchResponsableClean = $this->cleanCsvValue($searchResponsable);
        $searchLieuClean = $this->cleanCsvValue($searchLieu);
        
        // Filtrer les entités selon les filtres appliqués
        $hasFilters = !empty($filtersParam) || !empty($searchServiceClean) || !empty($searchResponsableClean) || !empty($searchLieuClean);
        
        if ($hasFilters) {
            // Debug: loguer les paramètres de recherche
            if (!empty($searchServiceClean)) {
                error_log("DEBUG FILTER START: Recherche Service='{$searchServiceClean}' | Total avant filtrage={$totalBeforeFilter}");
            }
            // Créer une copie des valeurs de recherche pour la fermeture
            $searchServiceForFilter = $searchServiceClean;
            $searchResponsableForFilter = $searchResponsableClean;
            $searchLieuForFilter = $searchLieuClean;
            
            $entities = array_filter($entities, function($entity) use ($filtersParam, $searchServiceForFilter, $searchResponsableForFilter, $searchLieuForFilter) {
                // Filtres EasyAdmin standards (niveauRisque, statut)
                if (!empty($filtersParam) && is_array($filtersParam)) {
                    foreach ($filtersParam as $propertyName => $value) {
                        if ($value === null || $value === '') {
                            continue; // Ignorer les filtres vides
                        }
                        
                        // Récupérer la valeur de l'entité pour cette propriété
                        $entityValue = $this->getEntityPropertyValue($entity, $propertyName);
                        
                        // Appliquer le filtre
                        if (!$this->matchesFilter($entityValue, $value)) {
                            return false; // L'entité ne correspond pas au filtre
                        }
                    }
                }
                
                // Filtres de recherche personnalisés (service, responsable, lieu)
                // Utiliser une recherche plus précise avec correspondance exacte
                if (!empty($searchServiceForFilter)) {
                    // Récupérer directement la propriété CSV _csvService au lieu d'utiliser getEntityPropertyValue
                    // pour éviter tout problème de mapping
                    $serviceValueStr = '';
                    if (property_exists($entity, '_csvService')) {
                        $serviceValueStr = (string) ($entity->_csvService ?? '');
                    } else {
                        // Fallback sur getEntityPropertyValue si la propriété n'existe pas
                        $serviceValue = $this->getEntityPropertyValue($entity, 'service');
                        $serviceValueStr = (string) ($serviceValue ?? '');
                    }
                    
                    // Nettoyer la valeur de l'entité
                    $serviceValueStr = $this->cleanCsvValue($serviceValueStr);
                    
                    // Si la valeur est vide après nettoyage, exclure cette entité
                    if (empty($serviceValueStr)) {
                        return false;
                    }
                    
                    // Recherche stricte : correspondance exacte uniquement (insensible à la casse)
                    $valueNormalized = mb_strtolower($serviceValueStr, 'UTF-8');
                    $searchNormalized = mb_strtolower($searchServiceForFilter, 'UTF-8');
                    
                    // Correspondance exacte uniquement - on compare les chaînes normalisées
                    // Si elles ne sont pas identiques, exclure cette entité
                    // Utiliser une comparaison stricte === pour éviter les problèmes de type
                    if ($valueNormalized !== $searchNormalized) {
                        return false;
                    }
                }
                
                if (!empty($searchResponsableForFilter)) {
                    // Récupérer directement la propriété CSV _csvResponsable
                    $responsableValueStr = '';
                    if (property_exists($entity, '_csvResponsable')) {
                        $responsableValueStr = (string) ($entity->_csvResponsable ?? '');
                    } else {
                        $responsableValue = $this->getEntityPropertyValue($entity, 'responsable');
                        $responsableValueStr = (string) ($responsableValue ?? '');
                    }
                    
                    $responsableValueStr = $this->cleanCsvValue($responsableValueStr);
                    
                    $valueNormalized = mb_strtolower($responsableValueStr, 'UTF-8');
                    $searchNormalized = mb_strtolower($searchResponsableForFilter, 'UTF-8');
                    
                    if ($valueNormalized !== $searchNormalized) {
                        return false;
                    }
                }
                
                if (!empty($searchLieuForFilter)) {
                    // Récupérer directement la propriété CSV _csvLieu
                    $lieuValueStr = '';
                    if (property_exists($entity, '_csvLieu')) {
                        $lieuValueStr = (string) ($entity->_csvLieu ?? '');
                    } else {
                        $lieuValue = $this->getEntityPropertyValue($entity, 'lieu');
                        $lieuValueStr = (string) ($lieuValue ?? '');
                    }
                    
                    $lieuValueStr = $this->cleanCsvValue($lieuValueStr);
                    
                    $valueNormalized = mb_strtolower($lieuValueStr, 'UTF-8');
                    $searchNormalized = mb_strtolower($searchLieuForFilter, 'UTF-8');
                    
                    if ($valueNormalized !== $searchNormalized) {
                        return false;
                    }
                }
                
                return true; // L'entité correspond à tous les filtres
            });
            
            // Réindexer le tableau après le filtrage
            $entities = array_values($entities);
            
            $totalAfterFilter = count($entities);
            
            // Debug : compter les entités après filtrage
            if (!empty($searchServiceClean)) {
                $countByService = [];
                foreach ($entities as $e) {
                    $svc = property_exists($e, '_csvService') ? (string)($e->_csvService ?? '') : '';
                    $svc = $this->cleanCsvValue($svc);
                    $countByService[$svc] = ($countByService[$svc] ?? 0) + 1;
                }
                // Loguer les services trouvés après filtrage
                error_log("DEBUG FILTER END: Recherche Service='{$searchServiceClean}' | Total après filtrage={$totalAfterFilter} | Services trouvés: " . json_encode($countByService));
            }
        }

        // Pagination
        $page = $request->query->getInt('page', 1);
        $perPage = $context->getCrud()->getPaginator()->getPageSize();
        $total = count($entities);
        $offset = ($page - 1) * $perPage;
        $paginatedEntities = array_slice($entities, $offset, $perPage);

        // Obtenir le résultat parent pour avoir tous les paramètres EasyAdmin traités (champs, actions, etc.)
        $parentResult = parent::index($context);
        $parentParams = $parentResult->all();
        
        // Récupérer une EntityDto du parent pour voir comment elle est configurée
        $parentEntities = $parentParams['entities'] ?? null;
        $sampleEntityDto = null;
        $processedFields = null;
        if ($parentEntities instanceof EntityCollection && $parentEntities->count() > 0) {
            $sampleEntityDto = $parentEntities->first();
            $processedFields = $sampleEntityDto->getFields();
        }
        
        // Si on n'a pas de champs traités, créer une entité temporaire pour obtenir les champs
        if (!$processedFields instanceof FieldCollection && !empty($paginatedEntities)) {
            $tempEntity = $paginatedEntities[0];
            $tempEntityDto = $this->entityFactory->createForEntityInstance($tempEntity);
            // Les champs seront traités automatiquement par EasyAdmin lors de l'affichage
            // On peut aussi récupérer les champs depuis le contexte
            $processedFields = $parentParams['fields'] ?? null;
        }
        
        // Créer des EntityDto pour chaque entité en utilisant EntityFactory
        $entityDtos = [];
        foreach ($paginatedEntities as $entity) {
            $entityDto = $this->entityFactory->createForEntityInstance($entity);
            
            // Attacher les champs traités à l'EntityDto si disponibles
            // Si on a des champs traités depuis le parent, les utiliser
            if ($processedFields instanceof FieldCollection) {
                $entityDto->setFields($processedFields);
            } else {
                // Sinon, traiter les champs pour cette entité spécifique
                // Cela garantit que les champs sont correctement configurés
                $entityDto = $this->processFields($entityDto, $context);
            }
            
            // Si on a un exemple du parent, copier ses actions
            if ($sampleEntityDto && $sampleEntityDto->getActions()) {
                $entityDto->setActions($sampleEntityDto->getActions());
            } else {
                // Sinon, créer une ActionCollection vide (sera remplie par EasyAdmin)
                $entityDto->setActions(ActionCollection::new([]));
            }
            $entityDtos[] = $entityDto;
        }
        
        // Créer une EntityCollection avec les EntityDto
        $entityCollection = EntityCollection::new($entityDtos);
        
        // Créer un paginator qui correspond exactement au format EasyAdmin
        $request = $context->getRequest();
        $adminUrlGenerator = $this->adminUrlGenerator;
        $paginator = new class($paginatedEntities, $total, $perPage, $page, $request, $adminUrlGenerator) implements \IteratorAggregate, \Countable {
            private $items;
            private $total;
            private $perPage;
            private $page;
            private $request;
            private $adminUrlGenerator;
            
            public function __construct($items, $total, $perPage, $page, $request, $adminUrlGenerator) {
                $this->items = $items;
                $this->total = $total;
                $this->perPage = $perPage;
                $this->page = $page;
                $this->request = $request;
                $this->adminUrlGenerator = $adminUrlGenerator;
            }
            
            public function getIterator(): \Traversable {
                return new \ArrayIterator($this->items);
            }
            
            public function count(): int {
                return $this->total;
            }
            
            public function getCurrentPage(): int {
                return $this->page;
            }
            
            public function getLastPage(): int {
                return (int) ceil($this->total / $this->perPage);
            }
            
            public function getPageSize(): int {
                return $this->perPage;
            }
            
            public function getTotalItems(): int {
                return $this->total;
            }
            
            public function numResults(): int {
                return $this->total;
            }
            
            public function hasPreviousPage(): bool {
                return $this->page > 1;
            }
            
            public function hasNextPage(): bool {
                return $this->page < $this->getLastPage();
            }
            
            public function getPreviousPage(): int {
                return max(1, $this->page - 1);
            }
            
            public function getNextPage(): int {
                return min($this->getLastPage(), $this->page + 1);
            }
            
            public function previousPage(): int {
                return $this->getPreviousPage();
            }
            
            public function nextPage(): int {
                return $this->getNextPage();
            }
            
            public function generateUrlForPage(int $page): string {
                $url = $this->adminUrlGenerator
                    ->setController(\App\Controller\Admin\GoudurixCrudController::class)
                    ->setAction('index')
                    ->set('view', 'table')
                    ->set('page', $page)
                    ->generateUrl();
                return $url;
            }
            
            public function getPageRange(?int $pagesOnEachSide = null, ?int $pagesOnEdges = null): iterable {
                // Retourner un générateur des numéros de page à afficher
                $pagesOnEachSide = $pagesOnEachSide ?? 2;
                $pagesOnEdges = $pagesOnEdges ?? 1;
                $lastPage = $this->getLastPage();
                $currentPage = $this->page;
                
                if ($lastPage <= ($pagesOnEachSide + $pagesOnEdges) * 2) {
                    // Si peu de pages, toutes les afficher
                    for ($i = 1; $i <= $lastPage; $i++) {
                        yield $i;
                    }
                } else {
                    // Afficher les premières pages
                    for ($i = 1; $i <= $pagesOnEdges; $i++) {
                        yield $i;
                    }
                    
                    // Afficher les pages autour de la page courante
                    $start = max($pagesOnEdges + 1, $currentPage - $pagesOnEachSide);
                    $end = min($lastPage - $pagesOnEdges, $currentPage + $pagesOnEachSide);
                    
                    if ($start > $pagesOnEdges + 1) {
                        yield null; // Ellipse
                    }
                    
                    for ($i = $start; $i <= $end; $i++) {
                        yield $i;
                    }
                    
                    if ($end < $lastPage - $pagesOnEdges) {
                        yield null; // Ellipse
                    }
                    
                    // Afficher les dernières pages
                    for ($i = $lastPage - $pagesOnEdges + 1; $i <= $lastPage; $i++) {
                        yield $i;
                    }
                }
            }
            
            // Alias pour Twig qui accède à pageRange comme propriété
            public function pageRange(): iterable {
                return $this->getPageRange();
            }
        };

        // Remplacer les entités et le paginator dans les paramètres du parent
        $parentParams['entities'] = $entityCollection;
        $parentParams['paginator'] = $paginator;
        
        // Ajouter les données CSV pour les filtres personnalisés
        $csvData = $this->getCsvDataForFilters();
        $parentParams['csvData'] = $csvData;
        
        // Ajouter les valeurs de recherche actuelles pour les afficher dans le template
        $parentParams['searchService'] = $request->query->get('search_service', '');
        $parentParams['searchResponsable'] = $request->query->get('search_responsable', '');
        $parentParams['searchLieu'] = $request->query->get('search_lieu', '');
        
        // S'assurer que les filtres sont présents dans les paramètres
        // Les filtres seront traités par EasyAdmin via le template
        
        return KeyValueStore::new($parentParams);
    }
    
    /**
     * Récupère la valeur d'une propriété d'une entité (peut être une propriété directe ou CSV)
     */
    private function getEntityPropertyValue($entity, string $propertyName): mixed
    {
        // Mapping pour les propriétés qui sont stockées dans le CSV
        $csvMapping = [
            'service' => '_csvService',
            'responsable' => '_csvResponsable',
            'lieu' => '_csvLieu',
        ];
        
        // Si c'est une propriété CSV, l'utiliser directement
        if (isset($csvMapping[$propertyName])) {
            $csvProperty = $csvMapping[$propertyName];
            if (property_exists($entity, $csvProperty)) {
                return $entity->$csvProperty;
            }
        }
        
        // Propriétés directes de l'entité
        $getter = 'get' . ucfirst($propertyName);
        if (method_exists($entity, $getter)) {
            return $entity->$getter();
        }
        
        // Propriétés CSV temporaires (avec différentes conventions de nommage)
        $csvProperty = '_csv' . ucfirst($propertyName);
        if (property_exists($entity, $csvProperty)) {
            return $entity->$csvProperty;
        }
        
        // Essayer aussi avec la première lettre en minuscule
        $csvPropertyLower = '_csv' . lcfirst($propertyName);
        if (property_exists($entity, $csvPropertyLower)) {
            return $entity->$csvPropertyLower;
        }
        
        // Propriété directe (si publique)
        if (property_exists($entity, $propertyName)) {
            return $entity->$propertyName;
        }
        
        return null;
    }
    
    /**
     * Mappe une propriété de filtre vers la propriété CSV correspondante
     */
    private function mapPropertyToCsv(string $propertyName): string
    {
        // Mapping pour les propriétés qui sont stockées dans le CSV
        $mapping = [
            'service' => 'Service',
            'responsable' => 'Responsable',
            'lieu' => 'Site',
        ];
        
        return $mapping[$propertyName] ?? $propertyName;
    }
    
    /**
     * Vérifie si une valeur d'entité correspond à un filtre
     */
    private function matchesFilter($entityValue, $filterValue): bool
    {
        if ($entityValue === null) {
            return false;
        }
        
        // Convertir en string pour la comparaison
        $entityValueStr = (string) $entityValue;
        $filterValueStr = (string) $filterValue;
        
        // Comparaison simple (peut être améliorée selon le type de filtre)
        // Support des comparaisons partielles (contient)
        if (stripos($entityValueStr, $filterValueStr) !== false) {
            return true;
        }
        
        // Comparaison exacte (insensible à la casse)
        return strcasecmp($entityValueStr, $filterValueStr) === 0;
    }
    
    /**
     * Vérifie si une valeur correspond à un terme de recherche
     * Utilise une recherche plus précise pour éviter les correspondances partielles indésirables
     */
    private function matchesSearchTerm(?string $value, string $searchTerm): bool
    {
        if ($value === null || $value === '') {
            return false;
        }
        
        $valueTrimmed = trim($value);
        $searchTrimmed = trim($searchTerm);
        
        if (empty($searchTrimmed)) {
            return true;
        }
        
        // Normaliser les espaces multiples et les caractères spéciaux
        $valueNormalized = $this->normalizeForSearch($valueTrimmed);
        $searchNormalized = $this->normalizeForSearch($searchTrimmed);
        
        // D'abord, essayer une correspondance exacte (insensible à la casse)
        if (strcasecmp($valueNormalized, $searchNormalized) === 0) {
            return true;
        }
        
        // Ensuite, essayer une correspondance "contient" pour la phrase complète
        // C'est la méthode la plus fiable pour éviter les faux positifs
        if (stripos($valueNormalized, $searchNormalized) !== false) {
            return true;
        }
        
        // Si le terme de recherche contient plusieurs mots et que la phrase complète n'a pas été trouvée,
        // on ne fait PAS de recherche par mots séparés pour éviter les faux positifs
        // (ex: "CROSS MÉDITERRANÉE" ne doit pas matcher si seulement "CROSS" est présent)
        
        // Pour un seul mot, faire une recherche "contient" simple
        $searchWords = preg_split('/\s+/', $searchNormalized);
        if (count($searchWords) === 1) {
            return stripos($valueNormalized, $searchNormalized) !== false;
        }
        
        // Pour plusieurs mots, si la phrase complète n'est pas trouvée, on retourne false
        // Cela évite les correspondances partielles indésirables
        return false;
    }
    
    /**
     * Nettoie une valeur CSV (supprime retours à la ligne, espaces multiples, etc.)
     */
    private function cleanCsvValue(string $value): string
    {
        if (empty($value)) {
            return '';
        }
        
        // Supprimer les retours à la ligne et les caractères de contrôle
        $value = str_replace(["\r", "\n", "\t"], ' ', $value);
        // Supprimer les caractères non imprimables
        $value = preg_replace('/[\x00-\x1F\x7F]/', '', $value);
        // Normaliser les espaces multiples
        $value = preg_replace('/\s+/', ' ', $value);
        // Trim
        $value = trim($value);
        return $value;
    }
    
    /**
     * Normalise une chaîne pour la recherche (supprime les espaces multiples, normalise les accents)
     */
    private function normalizeForSearch(string $str): string
    {
        // Normaliser les espaces multiples
        $str = preg_replace('/\s+/', ' ', trim($str));
        // Convertir en minuscules pour la comparaison
        $str = mb_strtolower($str, 'UTF-8');
        return $str;
    }
    
    /**
     * Normalise une chaîne pour la comparaison (supprime les accents, espaces multiples, etc.)
     */
    private function normalizeString(string $str): string
    {
        // Supprimer les accents
        $str = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $str);
        // Convertir en minuscules
        $str = mb_strtolower($str);
        // Normaliser les espaces
        $str = preg_replace('/\s+/', ' ', trim($str));
        return $str;
    }
}
