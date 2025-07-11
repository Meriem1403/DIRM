<?php

namespace App\Form;

use App\Entity\PersonneABord;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PersonneABordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('equipage', IntegerType::class, [
                'label' => 'Nombre d’équipage',
            ])
            ->add('passagers', IntegerType::class, [
                'label' => 'Nombre de passagers',
            ])
            ->add('personnes', IntegerType::class, [
                'label' => 'Nombre de personnel spécial',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PersonneABord::class,
        ]);
    }
}
