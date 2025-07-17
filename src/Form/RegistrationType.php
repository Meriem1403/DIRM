<?php
// src/Form/RegistrationType.php
// src/Form/RegistrationFormType.php
namespace App\Form;

use App\Entity\User;
use App\Entity\Role;
use App\Entity\Service;
use App\Entity\DomaineService;
use App\Entity\Lieu;
use App\Entity\Categorie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\{
    TextType, EmailType, PasswordType, DateType
};
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\{
    NotBlank, Length
};

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
                'constraints' => [new NotBlank()],
            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'constraints' => [new NotBlank()],
            ])
            ->add('dateNaissance', DateType::class, [
                'label' => 'Date de naissance',
                'widget' => 'single_text',
                'constraints' => [new NotBlank()],
            ])
            ->add('adresse', TextType::class, [
                'label' => 'Adresse',
                'constraints' => [new NotBlank()],
            ])
            ->add('ville', TextType::class, [
                'label' => 'Ville',
                'constraints' => [new NotBlank()],
            ])
            ->add('codePostal', TextType::class, [
                'label' => 'Code postal',
                'constraints' => [new NotBlank()],
            ])
            ->add('pays', TextType::class, [
                'label' => 'Pays',
                'constraints' => [new NotBlank()],
            ])
            ->add('poste', TextType::class, [
                'label' => 'Poste occupé',
                'constraints' => [new NotBlank()],
            ])
            ->add('service', EntityType::class, [
                'class' => Service::class,
                'choice_label' => 'nom',
                'label' => 'Service',
                'placeholder' => 'Sélectionnez un service',
            ])
            ->add('domaine', EntityType::class, [
                'class' => DomaineService::class,
                'choice_label' => 'nom',
                'label' => 'Domaine',
                'required' => false,
                'placeholder' => '---',
            ])
            ->add('lieu', EntityType::class, [
                'class' => Lieu::class,
                'choice_label' => 'nom',
                'label' => 'Lieu',
                'required' => false,
                'placeholder' => '---',
            ])
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'nom',
                'label' => 'Catégorie',
                'placeholder' => '---',
            ])
            ->add('role', EntityType::class, [
                'class' => Role::class,
                'choice_label' => 'label',
                'label' => 'Rôle',
                'placeholder' => 'Sélectionnez un rôle',
            ])
            ->add('email', EmailType::class, [
                'label' => 'Adresse e‑mail',
                'constraints' => [new NotBlank()],
            ])
            ->add('plainPassword', PasswordType::class, [
                'label' => 'Mot de passe',
                'mapped' => true,
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 6, 'minMessage' => '6 caractères minimum'])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            // En création, on veut valider plainPassword
            'validation_groups' => ['Default','create'],
        ]);
    }
}
