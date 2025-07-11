<?php

namespace App\Form;

use App\Entity\DeclarationChantier;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeclarationChantierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('adresse')
            ->add('email')
            ->add('telephone')
            ->add('qualiteDeclarant')
            ->add('typeDemande')
            ->add('typeProjet')
            ->add('activites')
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
            ->add('chantierNaval')
            ->add('architecteNaval')
            ->add('organismeClasse')
            ->add('categorieConception')
            ->add('numeroSerie')
            ->add('numeroCoque')
            ->add('modulesEvaluation')
            ->add('organismeNotifie')
            ->add('autreNom')
            ->add('autrePrenom')
            ->add('autreQualite')
            ->add('autreTelephone')
            ->add('chantierAvecContrat')
            ->add('adresseChantier')
            ->add('contactChantier')
            ->add('contactArchitecte')
            ->add('contactOrganismeClasse')
            ->add('numeroExamenCe')
            ->add('nomMandataire');
            $builder
                // ...
                ->add('noteExplicativeFile', FileType::class, [
                    'label' => 'Note explicative (PDF ou image)',
                    'mapped' => false, // ⚠️ important
                    'required' => false,
                ])
            ->add('prenomMandataire')
            ->add('qualiteMandataire')
            ->add('telephoneMandataire')
            ->add('portDepart')
            ->add('nbEquipage')
            ->add('nbPassagers')
            ->add('nbPersonnelSpecial')
            ->add('eloignementCote')
            ->add('dureeSejourMer')
            ->add('statut')
            ->add('dateSoumission', null, [
                'widget' => 'single_text',
            ])
            ->add('dateValidation', null, [
                'widget' => 'single_text',
            ])
            ->add('recepissePath')
            ->add('noteExplicativePath')
            ->add('noteExplicativeFile', FileType::class, [
                'label' => 'Note explicative (PDF ou image)',
                'mapped' => false,
                'required' => false,
                'help' => 'Permet de justifier certaines spécificités techniques ou d’exploitation.',
            ])
            ->add('validePar', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DeclarationChantier::class,
        ]);
    }
}
