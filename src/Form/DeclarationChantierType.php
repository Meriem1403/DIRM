<?php
// src/Form/DeclarationChantierType.php

namespace App\Form;

use App\Entity\DeclarationChantier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeclarationChantierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // — Exploitant
            ->add('nom')
            ->add('prenom')
            ->add('adresse')
            ->add('email')
            ->add('telephone')

            // — Déclaration
            ->add('typeDemande')
            ->add('activites')

            // — Caractéristiques du navire
            ->add('nomNavire')
            ->add('pavillonOrigine')
            ->add('quartierImmatriculation')
            ->add('jauge')
            ->add('longueur')
            ->add('largeur')
            ->add('propulsion')
            ->add('puissanceKw')
            ->add('vitesse')
            ->add('materiau')
            ->add('datePoseQuille')

            // — Exploitation
            ->add('portDepart')

            // ** Collection de personnes à bord **
            ->add('personnesABord', CollectionType::class, [
                'entry_type'    => PersonneABordType::class,
                'allow_add'     => true,
                'allow_delete'  => true,
                'by_reference'  => false,
                'label'         => 'Personnes à bord',
            ])

            ->add('eloignementCote')
            ->add('dureeSejourMer')

            // — Détails complémentaires
            ->add('chantierAvecContrat')
            ->add('nomChantier')
            ->add('adresseChantier')
            ->add('contactChantier')

            // — Architecte naval
            ->add('architecteNaval')
            ->add('contactArchitecte')
            ->add('architecteMail')

            // — Organisme habilité
            ->add('organismeClasse')
            ->add('contactOrganismeClasse')
            ->add('emailOrganisme')

            // — Navire de conception
            ->add('nomChantierConception')
            ->add('numeroSerie')
            ->add('numeroCoque')
            ->add('categorieConception')
            ->add('modulesEvaluation')

            // — Mandataire IA
            ->add('nomMandataireIa')
            ->add('prenomMandataireIa')
            ->add('qualiteMandataireIa')
            ->add('telephoneMandataireIa')
            ->add('emailMandataireIa')

            // — Mandataire
            ->add('nomMandataire')
            ->add('prenomMandataire')
            ->add('qualiteMandataire')
            ->add('telephoneMandataire')
            ->add('emailMandataire')

            // — Suivi interne (si vous le souhaitez en front)
            // ->add('statut')
            // ->add('dateSoumission', null, ['widget' => 'single_text'])
            // ->add('dateValidation', null, ['widget' => 'single_text'])
            // ->add('validePar', EntityType::class, ['class'=>User::class,'choice_label'=>'fullName'])

            // — Fichiers
            ->add('recepissePath')
            ->add('noteExplicativePath')

            // — Optionnel : upload de fichier non mappé
            ->add('noteExplicativeFile', FileType::class, [
                'label'    => 'Note explicative (PDF ou image)',
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
