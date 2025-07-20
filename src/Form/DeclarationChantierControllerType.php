<?php
// src/Form/DeclarationChantierType.php

namespace App\Form;

use App\Entity\DeclarationChantier;
use App\Entity\User;
use Doctrine\DBAL\Types\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\{TextType, EmailType, TelType, ChoiceType, CollectionType, CheckboxType};
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeclarationChantierControllerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // 🧍‍♂️ Exploitant
            ->add('nom',           TextType::class)
            ->add('prenom',        TextType::class)
            ->add('adresse',       TextType::class)
            ->add('email',         EmailType::class)
            ->add('telephone',     TelType::class)

            // 📄 Déclaration
            ->add('typeDemande',   ChoiceType::class, [
                'choices'  => [
                    'Mise en chantier'        => 'mise_en_chantier',
                    'Modification importante' => 'modification',
                    'Importation'             => 'importation',
                    'Usage pro plaisance'     => 'mise_en_service',
                ],
                'expanded' => true,
                'multiple' => false,
            ])
            ->add('activites',     ChoiceType::class, [
                'choices'  => [
                    'Pêche/Conchyliculture'     => 'peche',
                    'Transport +12 passagers'   => 'transport',
                    'Services côtiers'          => 'services',
                    'Sport & tourisme (NUC)'    => 'sport',
                    'Chargement'                => 'chargement',
                ],
                'expanded' => true,
                'multiple' => true,
            ])

            // 🚢 Caractéristiques
            ->add('nomNavire',             TextType::class)
            ->add('pavillonOrigine',       TextType::class, ['required' => false])
            ->add('quartierImmatriculation', TextType::class)
            ->add('jauge')  // type float autodétecté
            ->add('longueur')
            ->add('largeur')
            ->add('propulsion',            ChoiceType::class, [
                'choices'  => [
                    'Thermique'  => 'thermique',
                    'Électrique' => 'electrique',
                    'Hybride'    => 'hybride',
                    'Voile'      => 'voile',
                    'Autre'      => 'autre',
                ],
                'expanded' => true,
            ])
            ->add('puissanceKw')
            ->add('vitesse',               ChoiceType::class, [
                'choices'  => [
                    '0-12 nds'   => '0_12',
                    '12-20 nds'  => '12_20',
                    '>20 nds'    => '20_plus',
                ],
                'expanded' => true,
            ])
            ->add('materiau',              ChoiceType::class, [
                'choices'  => [
                    'Acier'        => 'acier',
                    'Aluminium'    => 'aluminium',
                    'PRVT'         => 'prvt',
                    'Polyéthylène' => 'polyethylene',
                    'Bois'         => 'bois',
                ],
                'expanded' => true,
            ])
            ->add('datePoseQuille',        DateType::class, [
                'widget' => 'single_text'
            ])

            // ⚓ Exploitation
            ->add('portDepart',            TextType::class)
            ->add('personnesABord',        CollectionType::class, [
                'entry_type'   => PersonneABordType::class,
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype'    => true,
            ])
            ->add('eloignementCote',       ChoiceType::class, [
                'choices'  => [
                    '>20 milles'  => '>20',
                    '12–20 milles'=> '20_12',
                    '5–12 milles' => '12_5',
                    '2–5 milles'  => '5_2',
                    '<2 milles'   => '<2',
                ],
                'expanded' => true,
            ])
            ->add('dureeSejourMer',        ChoiceType::class, [
                'choices'  => [
                    '<6h'   => 'moins_6h',
                    '6–12h' => '6_12h',
                    '>12h'  => 'plus_12h',
                ],
                'expanded' => true,
            ])

            // 📋 Détails complémentaires
            ->add('chantierAvecContrat',   CheckboxType::class, [
                'required' => false,
                'label'    => 'Chantier naval sous contrat'
            ])
            ->add('nomChantier',           TextType::class, ['required'=> false])
            ->add('adresseChantier',       TextType::class, ['required'=> false])
            ->add('contactChantier',       TelType::class,  ['required'=> false])

            // 🏗 Architecte
            ->add('architecteNaval',       TextType::class, ['required'=> false])
            ->add('contactArchitecte',     TelType::class,  ['required'=> false])
            ->add('architecteMail',        EmailType::class,['required'=> false])

            // 🏭 Organisme habilité
            ->add('organismeClasse',       TextType::class, ['required'=> false])
            ->add('contactOrganismeClasse',TelType::class,  ['required'=> false])
            ->add('emailOrganisme',        EmailType::class,['required'=> false])

            // 🏗 Navire de conception
            ->add('nomChantierConception', TextType::class, ['required'=> false])
            ->add('numeroSerie',           TextType::class, ['required'=> false])
            ->add('numeroCoque',           TextType::class, ['required'=> false])
            ->add('categorieConception',   ChoiceType::class, [
                'choices' => ['A'=>'A','B'=>'B','C'=>'C','D'=>'D'],
                'required'=> false,
            ])
            ->add('modulesEvaluation',     ChoiceType::class, [
                'choices'=>[
                    'Abis (Aa)'=>'abis','B+C'=>'b+c','B+D'=>'b+d','B+E'=>'b+e','B+F'=>'b+f','G'=>'g','H'=>'h'
                ],
                'required'=> false,
            ])

            // 📎 Mandataire IA
            ->add('nomMandataireIa',       TextType::class, ['required'=> false])
            ->add('prenomMandataireIa',    TextType::class, ['required'=> false])
            ->add('qualiteMandataireIa',   ChoiceType::class, [
                'choices'=>['Chantier'=>'chantier','AUTRE'=>'autre','NC'=>'nc'],
                'required'=> false,
            ])
            ->add('telephoneMandataireIa', TelType::class,  ['required'=> false])
            ->add('emailMandataireIa',     EmailType::class,['required'=> false])

            // 📎 Mandataire
            ->add('nomMandataire',         TextType::class, ['required'=> false])
            ->add('prenomMandataire',      TextType::class, ['required'=> false])
            ->add('qualiteMandataire',     ChoiceType::class, [
                'choices'=>['Chantier'=>'chantier','AUTRE'=>'autre','NC'=>'nc'],
                'required'=> false,
            ])
            ->add('telephoneMandataire',   TelType::class,  ['required'=> false])
            ->add('emailMandataire',       EmailType::class,['required'=> false])

            // 📍 Suivi (backend seulement)
            ->add('statut',                ChoiceType::class, [
                'choices'=>['En attente'=>'en_attente','Validée'=>'validee','Refusée'=>'refusee'],
                'required'=> false,
            ])
            ->add('validePar',             EntityType::class, [
                'class'       => User::class,
                'choice_label'=> 'email',
                'required'    => false,
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
