<?php

namespace App\Form;

use App\Entity\ApplicationCerbere;
use App\Entity\DemandeHabilitationCerbere;
use App\Entity\ProfilCerbere;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DemandeHabilitationCerbereType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Règle de portée
            ->add('reglePortee', TextType::class, [
                'label' => 'Règle de portée',
                'attr'  => ['class' => 'form-input'],
            ])

            // Restrictions éventuelles
            ->add('restrictions', TextareaType::class, [
                'label'    => 'Restrictions éventuelles',
                'required' => false,
                'attr'     => ['class' => 'form-input h-32'],
            ])

            // Agent concerné
            ->add('agent', EntityType::class, [
                'class'         => User::class,
                'choice_label'  => fn(User $u) => $u->getPrenom() . ' ' . $u->getNom(),
                'placeholder'   => 'Sélectionnez l’agent',
                'label'         => '👤 Agent concerné',
                'attr'          => ['class' => 'form-select'],
            ])

            // Demandeur
            ->add('demandeur', EntityType::class, [
                'class'         => User::class,
                'choice_label'  => fn(User $u) => $u->getPrenom() . ' ' . $u->getNom(),
                'placeholder'   => 'Sélectionnez le demandeur',
                'label'         => '👤 Demandeur',
                'attr'          => ['class' => 'form-select'],
            ])

            // Applications (multiple + infobulle description)
            ->add('applications', EntityType::class, [
                'class'         => ApplicationCerbere::class,
                'choice_label'  => fn(ApplicationCerbere $a) => $a->getNom() . ' (' . $a->getCode() . ')',
                'placeholder'   => 'Sélectionnez les applications',
                'multiple'      => true,
                'expanded'      => false,
                'by_reference'  => false,
                'label'         => '📦 Applications',
                'attr'          => ['class' => 'form-select', 'size' => 5],
                'choice_attr'   => function(ApplicationCerbere $a) {
                    return ['title' => $a->getDescription() ?: ''];
                },
            ])

            // Profils (multiple + infobulle description)
            ->add('profils', EntityType::class, [
                'class'         => ProfilCerbere::class,
                'choice_label'  => fn(ProfilCerbere $p) => $p->getApplication()->getNom() . ' / ' . $p->getNom(),
                'placeholder'   => 'Sélectionnez les profils',
                'multiple'      => true,
                'expanded'      => false,
                'by_reference'  => false,
                'label'         => '🔐 Profils',
                'attr'          => ['class' => 'form-select', 'size' => 5],
                'choice_attr'   => function(ProfilCerbere $p) {
                    return ['title' => $p->getDescription() ?: ''];
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DemandeHabilitationCerbere::class,
        ]);
    }
}
