<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use DateTimeImmutable;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;


class UserCrudController extends AbstractCrudController
{
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher) {}

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('un utilisateur')
            ->setEntityLabelInPlural('des utilisateurs')
            ->setPageTitle(Crud::PAGE_INDEX, 'Gestion des utilisateurs')
            ->setDefaultSort(['createdAt' => 'DESC']);
    }




    public function configureFields(string $pageName): array
    {
        return [
            FormField::addPanel('🧍 Identité'),

            TextField::new('prenom', 'Prénom')->setColumns(6),
            TextField::new('nom', 'Nom')->setColumns(6),
            EmailField::new('email', '✉️ Email')->setColumns(6),
            DateField::new('dateNaissance', '📅 Date de naissance')->setColumns(6),

            FormField::addPanel('🏠 Adresse'),

            TextField::new('adresse', 'Adresse')->setColumns(12),
            TextField::new('codePostal', 'Code postal')->setColumns(4),
            TextField::new('ville', 'Ville')->setColumns(4),
            TextField::new('pays', 'Pays')->setColumns(4),

            FormField::addPanel('🏢 Affectation'),

            TextField::new('poste', 'Poste occupé')->setColumns(12),
            AssociationField::new('service', 'Service')->setColumns(6),
            AssociationField::new('domaine', 'Domaine')
                ->setFormTypeOption('required', false)
                ->setFormTypeOption('query_builder', fn(EntityRepository $er) => $er->createQueryBuilder('d')->orderBy('d.nom', 'ASC'))
                ->setFormTypeOption('placeholder', '---')
                ->setColumns(6),

            AssociationField::new('lieu', 'Lieu')
                ->setFormTypeOption('required', false)
                ->setFormTypeOption('query_builder', fn(EntityRepository $er) => $er->createQueryBuilder('l')->orderBy('l.nom', 'ASC'))
                ->setFormTypeOption('placeholder', '---')
                ->setColumns(6),
            AssociationField::new('categorie', 'Catégorie')->setColumns(6),

            FormField::addPanel('🔐 Sécurité'),

            AssociationField::new('role', 'Rôle')->setColumns(6),
            TextField::new('plainPassword', 'Mot de passe')
                ->onlyOnForms()
                ->setFormTypeOption('required', false)
                ->setHelp('Laisser vide pour ne pas modifier')
                ->setColumns(6),

            FormField::addPanel('🕓 Infos système')->onlyOnIndex(),
            DateField::new('createdAt', 'Créé le')->onlyOnIndex(),
            AssociationField::new('createdBy', 'Créé par')->onlyOnIndex(),
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof User) return;

        $request = $this->getContext()->getRequest();
        $plainPassword = $request->request->all()['User']['plainPassword'] ?? null;

        if ($plainPassword) {
            $hashed = $this->passwordHasher->hashPassword($entityInstance, $plainPassword);
            $entityInstance->setPassword($hashed);
        }

        if (!$entityInstance->getCreatedAt()) {
            $entityInstance->setCreatedAt(new DateTimeImmutable());
        }

        if (!$entityInstance->getCreatedBy()) {
            $entityInstance->setCreatedBy($this->getUser());
        }

        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof User) return;

        $request = $this->getContext()->getRequest();
        $plainPassword = $request->request->all()['User']['plainPassword'] ?? null;

        if ($plainPassword) {
            $hashed = $this->passwordHasher->hashPassword($entityInstance, $plainPassword);
            $entityInstance->setPassword($hashed);
        }

        parent::updateEntity($entityManager, $entityInstance);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions;
    }
    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(EntityFilter::new('role'))
            ->add(EntityFilter::new('service'))
            ->add(EntityFilter::new('categorie'))
            ->add(EntityFilter::new('domaine'))
            ->add(EntityFilter::new('lieu'))
            ->add(DateTimeFilter::new('createdAt'));
    }

}
