<?php
// src/Form/DeclarationChantierType.php

namespace App\Form;

use App\Entity\DeclarationChantier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\{
    TextType,
    EmailType,
    TelType,
    ChoiceType,
    CollectionType,
    CheckboxType,
    DateType,
    FileType,
    NumberType
};

class DeclarationChantierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // 🧍‍♂️ Exploitant
            ->add('nom', TextType::class, ['label' => 'Nom'])
            ->add('prenom', TextType::class, ['label' => 'Prénom'])
            ->add('adresse', TextType::class, ['label' => 'Adresse'])
            ->add('email', EmailType::class, ['label' => 'Email'])
            ->add('telephone', TelType::class, ['label' => 'Téléphone'])

            // 📄 Déclaration
            ->add('typeDemande', ChoiceType::class, [
                'label'   => 'Type de demande',
                'choices' => [
                    'Mise en chantier'        => 'mise_en_chantier',
                    'Modification importante' => 'modification',
                    'Importation'             => 'importation',
                    'Usage pro plaisance'     => 'mise_en_service',
                ],
                'expanded' => true,
            ])
            ->add('activites', ChoiceType::class, [
                'label'    => 'Activités',
                'choices'  => [
                    'Pêche/Conchyliculture'   => 'peche',
                    'Transport +12 passagers' => 'transport',
                    'Services côtiers'        => 'services',
                    'Sport & tourisme (NUC)'  => 'sport',
                    'Chargement'              => 'chargement',
                ],
                'expanded' => true,
                'multiple' => true,
            ])

            // 🚢 Caractéristiques
            ->add('nomNavire', TextType::class, ['label' => 'Nom du navire'])
            ->add('pavillonOrigine', TextType::class, [
                'label'    => 'Pavillon d’origine',
                'required' => false,
            ])
            ->add('quartierImmatriculation', TextType::class, [
                'label' => 'Quartier d’immatriculation',
            ])
            ->add('jauge', NumberType::class, ['label' => 'Jauge (tonnes)'])
            ->add('longueur', NumberType::class, ['label' => 'Longueur (m)'])
            ->add('largeur', NumberType::class, ['label' => 'Largeur (m)'])
            ->add('propulsion', ChoiceType::class, [
                'label'   => 'Propulsion',
                'choices' => [
                    'Thermique'  => 'thermique',
                    'Électrique' => 'electrique',
                    'Hybride'    => 'hybride',
                    'Voile'      => 'voile',
                    'Autre'      => 'autre',
                ],
                'expanded' => true,
            ])
            ->add('puissanceKw', NumberType::class, ['label' => 'Puissance (kW)'])
            ->add('vitesse', ChoiceType::class, [
                'label'   => 'Vitesse',
                'choices' => [
                    '0–12 nds'  => '0_12',
                    '12–20 nds' => '12_20',
                    '> 20 nds'  => '20_plus',
                ],
                'expanded' => true,
            ])
            ->add('materiau', ChoiceType::class, [
                'label'   => 'Matériau',
                'choices' => [
                    'Acier'        => 'acier',
                    'Aluminium'    => 'aluminium',
                    'PRVT'         => 'prvt',
                    'Polyéthylène' => 'polyethylene',
                    'Bois'         => 'bois',
                ],
                'expanded' => true,
            ])
            ->add('datePoseQuille', DateType::class, [
                'label'  => 'Date de pose de quille',
                'widget' => 'single_text',
                'required' => false,
            ])

            // ⚓ Exploitation
            ->add('portDepart', TextType::class, ['label' => 'Port de départ'])
            ->add('eloignementCote', ChoiceType::class, [
                'label'   => 'Éloignement de la côte',
                'choices' => [
                    '>20 milles'   => '>20',
                    '12–20 milles' => '20_12',
                    '5–12 milles'  => '12_5',
                    '2–5 milles'   => '5_2',
                    '<2 milles'    => '<2',
                ],
                'expanded' => true,
            ])
            ->add('dureeSejourMer', ChoiceType::class, [
                'label'   => 'Durée du séjour en mer',
                'choices' => [
                    '< 6 h'  => 'moins_6h',
                    '6–12 h' => '6_12h',
                    '> 12 h' => 'plus_12h',
                ],
                'expanded' => true,
            ])
            ->add('personnesABord', CollectionType::class, [
                'entry_type'   => PersonneABordType::class,
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype'    => true,
                'label'        => false,
            ])

            // 📋 Détails complémentaires
            ->add('chantierAvecContrat', CheckboxType::class, [
                'label'    => 'Chantier naval sous contrat',
                'required' => false,
            ])
            ->add('nomChantier', TextType::class, [
                'label'    => 'Nom du chantier',
                'required' => false,
            ])
            ->add('adresseChantier', TextType::class, [
                'label'    => 'Adresse du chantier',
                'required' => false,
            ])
            ->add('contactChantier', TelType::class, [
                'label'    => 'Contact chantier',
                'required' => false,
            ])

            // 🏗 Architecte naval
            ->add('architecteNaval', TextType::class, [
                'label'    => 'Architecte naval',
                'required' => false,
            ])
            ->add('contactArchitecte', TelType::class, [
                'label'    => 'Contact architecte',
                'required' => false,
            ])
            ->add('architecteMail', EmailType::class, [
                'label'    => 'Email architecte',
                'required' => false,
            ])

            // 🏭 Organisme habilité
            ->add('organismeClasse', TextType::class, [
                'label'    => 'Organisme habilité',
                'required' => false,
            ])
            ->add('contactOrganismeClasse', TelType::class, [
                'label'    => 'Contact organisme',
                'required' => false,
            ])
            ->add('emailOrganisme', EmailType::class, [
                'label'    => 'Email organisme',
                'required' => false,
            ])

            // 🚢 Navire de conception
            ->add('nomChantierConception', TextType::class, [
                'label'    => 'Nom chantier conception',
                'required' => false,
            ])
            ->add('categorieConception', ChoiceType::class, [
                'label'   => 'Catégorie',
                'choices' => ['A'=>'A','B'=>'B','C'=>'C','D'=>'D'],
                'required' => false,
            ])
            ->add('numeroSerie', TextType::class, [
                'label'    => 'Numéro de série',
                'required' => false,
            ])
            ->add('numeroCoque', TextType::class, [
                'label'    => 'Numéro de coque',
                'required' => false,
            ])
            ->add('modulesEvaluation', TextType::class, [
                'label'    => 'Modules d’évaluation',
                'required' => false,
            ])

            // 🏭 Organisme notifié
            ->add('organismeNotifie', TextType::class, [
                'label'    => 'Organisme notifié',
                'required' => false,
            ])
            ->add('numeroExamenCe', TextType::class, [
                'label'    => 'N° examen CE',
                'required' => false,
            ])

            // 👤 Mandataire IA
            ->add('nomMandataireIa', TextType::class, [
                'label'    => 'Nom mandataire IA',
                'required' => false,
            ])
            ->add('prenomMandataireIa', TextType::class, [
                'label'    => 'Prénom mandataire IA',
                'required' => false,
            ])
            ->add('qualiteMandataireIa', ChoiceType::class, [
                'label'   => 'Qualité mandataire IA',
                'choices' => ['chantier'=>'Chantier','autre'=>'AUTRE','nc'=>'NC'],
                'expanded' => true,
                'required' => false,
            ])
            ->add('telephoneMandataireIa', TelType::class, [
                'label'    => 'Téléphone IA',
                'required' => false,
            ])
            ->add('emailMandataireIa', EmailType::class, [
                'label'    => 'Email IA',
                'required' => false,
            ])

            // 👥 Mandataire
            ->add('nomMandataire', TextType::class, [
                'label'    => 'Nom mandataire',
                'required' => false,
            ])
            ->add('prenomMandataire', TextType::class, [
                'label'    => 'Prénom mandataire',
                'required' => false,
            ])
            ->add('qualiteMandataire', ChoiceType::class, [
                'label'    => 'Qualité mandataire',
                'choices'  => ['chantier'=>'Chantier','autre'=>'AUTRE','nc'=>'NC'],
                'expanded' => true,
                'required' => false,
            ])
            ->add('telephoneMandataire', TelType::class, [
                'label'    => 'Téléphone mandataire',
                'required' => false,
            ])
            ->add('emailMandataire', EmailType::class, [
                'label'    => 'Email mandataire',
                'required' => false,
            ])

            // 📎 Pièces jointes
            ->add('recepisseFile', FileType::class, [
                'label'    => 'Récépissé (PDF, JPG...)',
                'mapped'   => false,
                'required' => false,
            ])
            ->add('noteExplicativeFile', FileType::class, [
                'label'    => 'Note explicative (PDF, JPG...)',
                'mapped'   => false,
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DeclarationChantier::class,
        ]);
    }
}
