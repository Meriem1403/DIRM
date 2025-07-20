<?php

namespace App\Form;

use App\Entity\ApplicationCerbere;
use App\Entity\DemandeHabilitationCerbere;
use App\Entity\ProfilCerbere;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DemandeHabilitationCerbereType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('reglePortee')
            ->add('restrictions')
            ->add('dateSoumission', null, [
                'widget' => 'single_text',
            ])
            ->add('statut')
            ->add('dateValidation', null, [
                'widget' => 'single_text',
            ])
            ->add('agent', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
            ->add('demandeur', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
            ->add('applications', EntityType::class, [
                'class' => ApplicationCerbere::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
            ->add('profils', EntityType::class, [
                'class' => ProfilCerbere::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
            ->add('validePar', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DemandeHabilitationCerbere::class,
        ]);
    }
}
