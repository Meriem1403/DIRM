<?php
// src/Form/DemandeMobiliteType.php

namespace App\Form;

use App\Entity\DemandeMobilite;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

// Types de champs
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class DemandeMobiliteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // === Étape 1 : identité & statut agent ===
            ->add('prenom', TextType::class, ['label' => 'Prénom'])
            ->add('nom', TextType::class, ['label' => 'Nom'])
            ->add('statutAgent', TextType::class, ['label' => 'Statut agent'])
            ->add('corps', TextType::class, ['label' => 'Corps'])
            ->add('grade', TextType::class, ['label' => 'Grade'])

            // === Statut de la demande (par défaut en attente) ===
            ->add('statut', ChoiceType::class, [
                'label'   => 'Statut de la demande',
                'choices' => [
                    'En attente' => 'en_attente',
                    'Validée'    => 'validee',
                    'Refusée'    => 'refusee',
                ],
                'data'    => 'en_attente',
            ])

            // === Étape 2 : objet de la demande ===
            ->add('typeDemande', ChoiceType::class, [
                'label'       => 'Type de demande',
                'choices'     => ['Arrivée' => 'arrivee', 'Départ' => 'depart'],
                'placeholder' => 'Choisir…',
            ])
            ->add('ministereOrigine', TextType::class, [
                'label'    => 'Ministère d’origine',
                'required' => false,
            ])
            ->add('directionOrigine', TextType::class, [
                'label'    => 'Direction d’origine',
                'required' => false,
            ])
            ->add('serviceOrigine', TextType::class, [
                'label'    => 'Service d’origine',
                'required' => false,
            ])
            ->add('serviceActuel', TextType::class, [
                'label'    => 'Service actuel',
                'required' => false,
            ])

            // === Étape 3 : dates & motifs ===
            ->add('dateDepart', DateType::class, [
                'label'    => 'Date de départ',
                'widget'   => 'single_text',
                'required' => false,
            ])
            ->add('motifDepart', TextType::class, [
                'label'    => 'Motif de départ',
                'required' => false,
            ])
            ->add('natureMutation', TextType::class, [
                'label'    => 'Nature de mutation',
                'required' => false,
            ])
            ->add('datePrisePoste', DateType::class, [
                'label'    => 'Date de prise de poste',
                'widget'   => 'single_text',
                'required' => false,
            ])

            // === Étape 4 : affectation ===
            ->add('serviceAffectation', TextType::class, [
                'label'    => 'Service d’affectation',
                'required' => false,
            ])
            ->add('siteGeographique', TextType::class, [
                'label'    => 'Site géographique',
                'required' => false,
            ])
            ->add('bureau', TextType::class, [
                'label'    => 'Bureau',
                'required' => false,
            ])
            ->add('fonction', TextType::class, [
                'label'    => 'Fonction',
                'required' => false,
            ])

            // === Étape 5 : poste & remplacement ===
            ->add('posteRemplacement', CheckboxType::class, [
                'label'    => 'Poste de remplacement',
                'required' => false,
            ])
            ->add('posteCreation', CheckboxType::class, [
                'label'    => 'Poste en création',
                'required' => false,
            ])
            ->add('prenomRemplace', TextType::class, [
                'label'    => 'Prénom remplacé',
                'required' => false,
            ])
            ->add('nomRemplace', TextType::class, [
                'label'    => 'Nom remplacé',
                'required' => false,
            ])

            // === Étape 6 : besoins particuliers ===
            ->add('besoinMobilier', CheckboxType::class, [
                'label'    => 'Mobilier',
                'required' => false,
            ])
            ->add('besoinFournitures', CheckboxType::class, [
                'label'    => 'Fournitures',
                'required' => false,
            ])
            ->add('besoinInformatique', CheckboxType::class, [
                'label'    => 'Informatique',
                'required' => false,
            ])

            // === Étape 7 : cartes & accès ===
            ->add('carteANTS', ChoiceType::class, [
                'label'       => 'Carte ANTS',
                'choices'     => ['Oui' => 'oui', 'Non' => 'non'],
                'placeholder' => 'Choisir…',
                'required'    => false,
            ])
            ->add('carteAchats', ChoiceType::class, [
                'label'       => 'Carte Achats',
                'choices'     => ['Oui' => 'oui', 'Non' => 'non'],
                'placeholder' => 'Choisir…',
                'required'    => false,
            ])
            ->add('chargeVoyages', ChoiceType::class, [
                'label'       => 'Chargé voyages',
                'choices'     => ['Oui' => 'oui', 'Non' => 'non'],
                'placeholder' => 'Choisir…',
                'required'    => false,
            ])
            ->add('correspondantBudgetaire', ChoiceType::class, [
                'label'       => 'Correspondant budgétaire',
                'choices'     => ['Oui' => 'oui', 'Non' => 'non'],
                'placeholder' => 'Choisir…',
                'required'    => false,
            ])
            ->add('encadreAgents', ChoiceType::class, [
                'label'       => 'Encadre des agents',
                'choices'     => ['Oui' => 'oui', 'Non' => 'non'],
                'placeholder' => 'Choisir…',
                'required'    => false,
            ])
            ->add('utiliseChorus', ChoiceType::class, [
                'label'       => 'Utilise Chorus',
                'choices'     => ['Oui' => 'oui', 'Non' => 'non'],
                'placeholder' => 'Choisir…',
                'required'    => false,
            ])

            // === Étape 8 : commentaire admin ===
            ->add('commentaire', TextareaType::class, [
                'label'    => 'Commentaire admin',
                'required' => false,
                'attr'     => ['rows' => 4],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DemandeMobilite::class,
        ]);
    }
}
