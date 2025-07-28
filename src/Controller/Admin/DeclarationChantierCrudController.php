<?php
// src/Controller/Admin/DeclarationChantierCrudController.php

namespace App\Controller\Admin;

use App\Entity\DeclarationChantier;
use App\Form\PersonneABordType;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\{
    FormField,
    IdField,
    TextField,
    EmailField,
    TelephoneField,
    NumberField,
    ChoiceField,
    DateField,
    DateTimeField,
    BooleanField,
    AssociationField,
    CollectionField,
    Field
};
use Symfony\Component\Form\Extension\Core\Type\FileType;

class DeclarationChantierCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return DeclarationChantier::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('déclarations de chantier')
            ->setEntityLabelInSingular('déclaration de chantier')
            ->setPageTitle(Crud::PAGE_INDEX, '📋 Liste des déclarations')
            ->setPageTitle(Crud::PAGE_DETAIL, '🔎 Détails de la déclaration')
            ->setPageTitle(Crud::PAGE_NEW, '➕ Nouvelle déclaration')
            ->setPageTitle(Crud::PAGE_EDIT, '✏️ Modifier la déclaration')
            ->setDefaultSort(['dateSoumission' => 'DESC']);
    }

    public function configureFields(string $pageName): iterable
    {
        // ── PAGE INDEX ──
        if (Crud::PAGE_INDEX === $pageName) {
            return [
                IdField::new('id'),
                TextField::new('nom', 'Nom'),
                TextField::new('prenom', 'Prénom'),
                EmailField::new('email', 'Email'),
                ChoiceField::new('typeDemande', 'Type de demande')
                    ->setChoices([
                        'Mise en chantier'        => 'mise_en_chantier',
                        'Modification importante' => 'modification',
                        'Importation'             => 'importation',
                        'Usage pro plaisance'     => 'mise_en_service',
                    ]),
                DateTimeField::new('dateSoumission', 'Soumis le'),
                ChoiceField::new('statut', 'Statut')
                    ->setChoices([
                        'En attente' => 'en_attente',
                        'Validée'     => 'validee',
                        'Refusée'     => 'refusee',
                    ]),
            ];
        }

        // ── PAGE DETAIL / NEW / EDIT ──
        return [
            // 🧍‍♂️ Exploitant
            FormField::addTab('🧍‍♂️ Exploitant'),
            TextField::new('nom'),
            TextField::new('prenom'),
            TextField::new('adresse'),
            EmailField::new('email'),
            TelephoneField::new('telephone'),

            // 📄 Déclaration
            FormField::addTab('📄 Déclaration'),
            ChoiceField::new('typeDemande', 'Type de demande')
                ->setChoices([
                    'Mise en chantier'        => 'mise_en_chantier',
                    'Modification importante' => 'modification',
                    'Importation'             => 'importation',
                    'Usage pro plaisance'     => 'mise_en_service',
                ])
                ->renderExpanded(),
            ChoiceField::new('activites', 'Activités')
                ->setChoices([
                    'Pêche/Conchyliculture'   => 'peche',
                    'Transport +12 passagers' => 'transport',
                    'Services côtiers'        => 'services',
                    'Sport & tourisme (NUC)'  => 'sport',
                    'Chargement'              => 'chargement',
                ])
                ->allowMultipleChoices()
                ->renderExpanded(),

            // 🚢 Caractéristiques
            FormField::addTab('🚢 Caractéristiques'),
            TextField::new('nomNavire', 'Nom du navire'),
            TextField::new('pavillonOrigine', 'Pavillon d’origine'),
            TextField::new('quartierImmatriculation', 'Quartier d’immatriculation'),
            NumberField::new('jauge', 'Jauge (tonnes)'),
            NumberField::new('longueur', 'Longueur (m)'),
            NumberField::new('largeur', 'Largeur (m)'),
            ChoiceField::new('propulsion', 'Propulsion')
                ->setChoices([
                    'Thermique'  => 'thermique',
                    'Électrique' => 'electrique',
                    'Hybride'    => 'hybride',
                    'Voile'      => 'voile',
                    'Autre'      => 'autre',
                ])
                ->renderExpanded(),
            NumberField::new('puissanceKw', 'Puissance (kW)'),
            ChoiceField::new('vitesse', 'Vitesse')
                ->setChoices([
                    '0–12 nds'  => '0_12',
                    '12–20 nds' => '12_20',
                    '> 20 nds'  => '20_plus',
                ])
                ->renderExpanded(),
            ChoiceField::new('materiau', 'Matériau')
                ->setChoices([
                    'Acier'        => 'acier',
                    'Aluminium'    => 'aluminium',
                    'PRVT'         => 'prvt',
                    'Polyéthylène' => 'polyethylene',
                    'Bois'         => 'bois',
                ])
                ->renderExpanded(),
            DateField::new('datePoseQuille', 'Date de pose de quille')
                ->setFormTypeOptions(['widget' => 'single_text']),

            // ⚓ Exploitation
            FormField::addTab('⚓ Exploitation'),
            TextField::new('portDepart', 'Port de départ'),
            ChoiceField::new('eloignementCote', 'Éloignement de la côte')
                ->setChoices([
                    '>20 milles'   => '>20',
                    '12–20 milles' => '20_12',
                    '5–12 milles'  => '12_5',
                    '2–5 milles'   => '5_2',
                    '<2 milles'    => '<2',
                ])
                ->renderExpanded(),
            ChoiceField::new('dureeSejourMer', 'Durée de séjour en mer')
                ->setChoices([
                    '< 6 h'  => 'moins_6h',
                    '6–12 h' => '6_12h',
                    '> 12 h' => 'plus_12h',
                ])
                ->renderExpanded(),
            CollectionField::new('personnesABord', 'Personnes à bord')
                ->setEntryType(PersonneABordType::class)
                ->allowAdd()
                ->allowDelete()
                ->onlyOnForms()
                ->setEntryIsComplex(),

            // 📋 Détails complémentaires
            FormField::addTab('📋 Détails complémentaires'),
            BooleanField::new('chantierAvecContrat', 'Chantier sous contrat'),
            TextField::new('nomChantier', 'Nom chantier'),
            TextField::new('adresseChantier', 'Adresse chantier'),
            TelephoneField::new('contactChantier', 'Contact chantier'),

            // 🏗 Architecte naval
            FormField::addTab('🏗 Architecte naval'),
            TextField::new('architecteNaval', 'Architecte naval'),
            TelephoneField::new('contactArchitecte', 'Contact architecte'),
            EmailField::new('architecteMail', 'Email architecte'),

            // 📎 Organisme habilité
            FormField::addTab('📎 Organisme habilité'),
            TextField::new('organismeClasse', 'Organisme habilité'),
            TelephoneField::new('contactOrganismeClasse', 'Contact organisme'),
            EmailField::new('emailOrganisme', 'Email organisme'),

            // 🚢 Navire de conception
            FormField::addTab('🚢 Navire de conception'),
            TextField::new('nomChantierConception', 'Nom chantier conception'),
            ChoiceField::new('categorieConception', 'Catégorie')
                ->setChoices(['A' => 'A', 'B' => 'B', 'C' => 'C', 'D' => 'D']),
            TextField::new('numeroSerie', 'Numéro de série'),
            TextField::new('numeroCoque', 'Numéro de coque'),
            TextField::new('modulesEvaluation', 'Modules d’évaluation'),

            // 🏭 Organisme notifié (optionnel)
            FormField::addTab('🏭 Organisme notifié'),
            TextField::new('organismeNotifie', 'Organisme notifié'),
            TextField::new('numeroExamenCe', 'N° examen CE'),

            // 👤 Mandataire IA
            FormField::addTab('👤 Mandataire IA'),
            TextField::new('nomMandataireIa', 'Nom IA'),
            TextField::new('prenomMandataireIa', 'Prénom IA'),
            ChoiceField::new('qualiteMandataireIa', 'Qualité IA')
                ->setChoices(['chantier' => 'Chantier', 'autre' => 'AUTRE', 'nc' => 'NC'])
                ->renderExpanded(),
            TelephoneField::new('telephoneMandataireIa', 'Téléphone IA'),
            EmailField::new('emailMandataireIa', 'Email IA'),

            // 👥 Mandataire
            FormField::addTab('👥 Mandataire'),
            TextField::new('nomMandataire', 'Nom'),
            TextField::new('prenomMandataire', 'Prénom'),
            TextField::new('qualiteMandataire', 'Qualité'),
            TelephoneField::new('telephoneMandataire', 'Téléphone'),
            EmailField::new('emailMandataire', 'Email'),

            // 📎 Pièces jointes
            FormField::addTab('📎 Pièces jointes'),
            Field::new('recepisseFile', 'Récépissé')
                ->setFormType(FileType::class)
                ->setFormTypeOptions([
                    'mapped' => false,
                    'required' => false,
                ])
                ->onlyOnForms(),
            Field::new('recepissePath', 'Récépissé')
                ->setTemplatePath('admin/field/file_link.html.twig')
                ->onlyOnDetail(),
            Field::new('noteExplicativeFile', 'Note explicative')
                ->setFormType(FileType::class)
                ->setFormTypeOptions([
                    'mapped' => false,
                    'required' => false,
                ])
                ->onlyOnForms(),
            Field::new('noteExplicativePath', 'Note explicative')
                ->setTemplatePath('admin/field/file_link.html.twig')
                ->onlyOnDetail(),

            // 📍 Suivi
            FormField::addTab('📍 Suivi'),
            ChoiceField::new('statut', 'Statut')
                ->setChoices([
                    'En attente' => 'en_attente',
                    'Validée'     => 'validee',
                    'Refusée'     => 'refusee',
                ]),
            DateTimeField::new('dateSoumission', 'Soumis le')->hideOnForm(),
            DateTimeField::new('dateValidation', 'Validé le')->hideOnForm(),
            AssociationField::new('validePar', 'Validé par')->hideOnForm(),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions;
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof DeclarationChantier) {
            return;
        }

        // Handle file uploads
        $request = $this->getContext()->getRequest();
        $files   = $request->files->get('DeclarationChantier', []);
        if (!empty($files['recepisseFile'])) {
            $file     = $files['recepisseFile'];
            $filename = uniqid('recp_') . '.' . $file->guessExtension();
            $file->move($this->getParameter('notes_directory'), $filename);
            $entityInstance->setRecepissePath($filename);
        }
        if (!empty($files['noteExplicativeFile'])) {
            $file     = $files['noteExplicativeFile'];
            $filename = uniqid('note_') . '.' . $file->guessExtension();
            $file->move($this->getParameter('notes_directory'), $filename);
            $entityInstance->setNoteExplicativePath($filename);
        }

        // Set submission date if not already set
        if (null === $entityInstance->getDateSoumission()) {
            $entityInstance->setDateSoumission(new DateTimeImmutable());
        }

        parent::persistEntity($entityManager, $entityInstance);
    }
}
