<?php

namespace App\Form;

use App\Entity\ProfilCerbere;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfilCerbereType extends AbstractType
{
public function buildForm(FormBuilderInterface $builder, array $options): void
{
$builder
->add('nom', TextType::class, [
'label' => 'Nom du profil',
'required' => true,
])
->add('description', TextareaType::class, [
'label' => 'Description',
'required' => false,
]);
}

public function configureOptions(OptionsResolver $resolver): void
{
$resolver->setDefaults([
'data_class' => ProfilCerbere::class,
]);
}
}
