<?php

namespace App\Controller\Admin;

use App\Entity\DeclarationChantier;
use App\Form\PersonneABordType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Doctrine\ORM\EntityManagerInterface;
use DateTimeImmutable;
use EasyCorp\Bundle\EasyAdminBundle\Field\{CollectionField,
    IdField,
    TelephoneField,
    TextField,
    EmailField,
    DateField,
    DateTimeField,
    BooleanField,
    ChoiceField,
    AssociationField,
    Field,
    FormField};

class DeclarationChantierCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return DeclarationChantier::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('des déclarations de chantier')
            ->setEntityLabelInSingular('une éclaration de chantier')
            ->setPageTitle(Crud::PAGE_INDEX, '📋 Liste des déclarations')
            ->setPageTitle(Crud::PAGE_DETAIL, '🔎 Détails de la déclaration')
            ->setPageTitle(Crud::PAGE_NEW, '➕ Nouvelle déclaration')
            ->setPageTitle(Crud::PAGE_EDIT, '✏️ Modifier la déclaration')
            ->setDefaultSort(['dateSoumission' => 'DESC']);
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),

            FormField::addTab('🧍‍♂️ Exploitant'),
            TextField::new('nom'),
            TextField::new('prenom'),
            TextField::new('adresse'),
            EmailField::new('email'),
            TextField::new('telephone'),

            FormField::addTab('📄 Déclaration'),
            ChoiceField::new('typeDemande', 'Déclare vouloir')
                ->setChoices([
                    'Mettre en chantier un navire de commerce ou de pêche' => 'mise_en_chantier',
                    'Apporter des modifications importantes sur un navire existant' => 'modification',
                    'Importer un navire de commerce ou de pêche existant' => 'importation',
                    'Utiliser un navire de conception plaisance pour un usage professionnel' => 'mise_en_service',
                ])
                ->renderExpanded()
                ->setRequired(true)
                ->setHelp('* Une modification est considérée comme importante si elle modifie les conditions d’exploitation (puissance, port en lourd, type de navire, surface de pont ou de cale, etc.) ou les éléments de stabilité.'),


            ChoiceField::new('activites')
                ->setLabel('Ce projet vise une activité de')
                ->setChoices([
                    'Pêche/Conchyliculture' => 'peche',
                    'Transport de +12 passagers' => 'transport',
                    'Services côtiers' => 'services',
                    'Sport et tourisme (NUC)' => 'sport',
                    'Chargement' => 'chargement',
                ])
                ->allowMultipleChoices()
                ->renderExpanded()
                ->setHelp('* Navires à utilisation collective de 12 à 36 passagers pour le sport, le tourisme ou la promenade.'),

            FormField::addTab('🚢 Caractéristiques'),

            TextField::new('nomNavire', 'Nom du navire'),
            TextField::new('pavillonOrigine', 'Pavillon antérieur si importation'),
            TextField::new('quartierImmatriculation', 'Quartier d\'immatriculation prévu'),

            Field::new('jauge', 'Jauge brute prévisionnelle (UMS)'),
            Field::new('longueur', 'Longueur HT (m)'),
            Field::new('largeur', 'Largeur HT (m)'),

            ChoiceField::new('propulsion', 'Type de propulsion')
                ->setChoices([
                    'Thermique' => 'thermique',
                    'Électrique' => 'electrique',
                    'Hybride' => 'hybride',
                    'Voile' => 'voile',
                    'Autre' => 'autre',
                ])
                ->renderExpanded(),

            Field::new('puissanceKw', 'Puissance totale en kW'),

            ChoiceField::new('vitesse', 'Vitesse')
                ->setChoices([
                    '0 à 12 nds' => '0_12',
                    '12 à 20 nds' => '12_20',
                    'Plus de 20 nds' => '20_plus',
                ])
                ->renderExpanded(),

            ChoiceField::new('materiau', 'Matériau de construction')
                ->setChoices([
                    'Acier' => 'acier',
                    'Aluminium' => 'aluminium',
                    'PRVT' => 'prvt',
                    'Polyéthylène' => 'polyethylene',
                    'Bois' => 'bois',
                ])
                ->renderExpanded(),

            DateField::new('datePoseQuille', 'Date de pose de quille'),

            FormField::addTab('⚓ Exploitation du navire'),

            TextField::new('portDepart', 'Port de Départ'),

            CollectionField::new('personnesABord')
                ->setEntryType(PersonneABordType::class)
                ->allowAdd()
                ->allowDelete()
                ->setLabel('Personnes à bord')
                ->onlyOnForms()
                ->setEntryIsComplex(),

            ChoiceField::new('eloignementCote', 'Éloignement de la côte')
                ->setChoices([
                    'Plus de 20 milles' => '>20',
                    '20 à 12 milles' => '20_12',
                    '12 à 5 milles' => '12_5',
                    '5 à 2 milles' => '5_2',
                    'Moins de 2 milles' => '<2',
                ])
                ->renderExpanded()
                ->setFormTypeOption('placeholder', false)
                ->setRequired(true)
                ->onlyOnForms(),


            ChoiceField::new('dureeSejourMer', 'Durée du séjour à la mer')
                ->setChoices([
                    'Moins de 6 heures' => 'moins_6h',
                    'Entre 6 et 12 heures' => '6_12h',
                    'Plus de 12 heures' => 'plus_12h',
                ])
                ->renderExpanded(),

            Field::new('noteExplicativePath', 'Note explicative')
                ->setTemplatePath('admin/field/file_link.html.twig')
                ->onlyOnDetail(),

            FormField::addTab('📋 Détails complémentaires'),
            BooleanField::new('chantierAvecContrat', 'En cas d\'intervention d\'un chantier naval avec contrat'),
            TextField::new('nomChantier', 'Nom du chantier'),
            TextField::new('adresseChantier', 'Adresse du chantier'),
            TelephoneField::new('autreTelephone', 'Téléphone'),

            FormField::addTab('📎 Architecte'),
            TextField::new('architecteNaval', 'Nom de l\'architecte ou du BE'),
            TelephoneField::new('contactArchitecte','Telephone'),
            EmailField::new('architecteMail','Adresse mail'),

            FormField::addTab('📎 Intervention SCH'),
            TextField::new('organismeClasse', 'Nom de la société de la classification habilitée (Franc-Bord et/ou structure'),
            TelephoneField::new('contactOrganismeClasse','Telephone'),
            EmailField::new('emailOrganisme','Adresse mail'),

            FormField::addTab('📎 Navire de conception'),
            TextField::new('nomChantierConception', 'Nom du chantier'),
            TextField::new('numeroSerie', 'Numéro de serie'),
            TextField::new('numeroCoque', 'Numéro de coque'),

            ChoiceField::new('categorieConception', 'Categorie de conception')
            ->setChoices([
                'A' => 'A',
                'B' => 'B',
                'C' => 'C',
                'D' => 'D',]),

            ChoiceField::new('modulesEvaluation', 'Module de evaluation')
            ->setChoices([
                'Abis' => 'Abis (Aa)',
                'B+C' => 'B+C',
                'B+D' => 'B+D',
                'B+E' => 'B+E',
                'B+F' => 'B+F',
                'G' => 'G',
                'H' => 'H',
                ]),
            TextField::new('organismeNotifie','Organisme de notifie'),
            TextField::new('numeroExamenCe','Numero d\'examen CE de type')->setHelp('(1) Fournir impérativement la DEC
            (2) Fournir l\'attestation délivrée par l\'organisme notifié'),

            FormField::addTab('📎 Interlocuteur'),
            TextField::new('nomMandataireIa', 'Nom du mandataire'),
            TextField::new('prenomMandataireIa', 'Prenom du mandataire'),
            ChoiceField::new('qualiteMandataireIa', 'Qualité du mandataire')
            ->setChoices([
                'Chantier' => 'Chantier',
                'AUTRE' => 'AUTRE',
                'NC' => 'NC',
                ]),

            TextField::new('telephoneMandataireIa', 'Telephone'),
            TextField::new('emailMandataireIa', 'Adresse mail'),

            FormField::addTab('📎 Mandataire'),
            TextField::new('nomMandataire'),
            TextField::new('prenomMandataire'),
            ChoiceField::new('qualiteMandataire', 'Qualit<UNK> du mandataire')
                ->setChoices([
                    'Chantier' => 'Chantier',
                    'AUTRE' => 'AUTRE',
                    'NC' => 'NC',
                ]),
            TextField::new('telephoneMandataire'),
            TextField::new('emailMandataire', 'Adresse mail'),


            FormField::addTab('📍 Suivi'),
            ChoiceField::new('statut')->setChoices([
                'En attente' => 'en attente',
                'Validée' => 'validee',
                'Refusée' => 'refusee',
            ]),
            DateTimeField::new('dateSoumission')->hideOnForm(),
            DateTimeField::new('dateValidation')->hideOnForm(),
            AssociationField::new('validePar')->hideOnForm(),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions;
    }
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof DeclarationChantier) return;


        if ($entityInstance->getDateSoumission() === null) {
            $entityInstance->setDateSoumission(new DateTimeImmutable());
        }

        parent::persistEntity($entityManager, $entityInstance);
    }

}
