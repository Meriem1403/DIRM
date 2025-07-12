<?php

// src/Command/CreateAdminCommand.php
namespace App\Command;

use App\Entity\User;
use App\Repository\RoleRepository;
use App\Repository\ServiceRepository;
use App\Repository\CategorieRepository;
use App\Repository\DomaineServiceRepository;
use App\Repository\LieuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use DateTime;
use DateTimeImmutable;
#[AsCommand(name: 'app:create-admin')]
class CreateAdminCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly UserPasswordHasherInterface $hasher,
        private readonly RoleRepository $roleRepo,
        private readonly ServiceRepository $serviceRepo,
        private readonly CategorieRepository $categorieRepo,
        private readonly DomaineServiceRepository $domaineRepo,
        private readonly LieuRepository $lieuRepo,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $user = new User();
        $user->setEmail('admin@admin.fr');
        $user->setNom('Admin');
        $user->setPrenom('Super');
        $user->setAdresse('Rue de l\'accès');
        $user->setVille('Adminville');
        $user->setCodePostal('12345');
        $user->setPays('France');
        $user->setPoste('Administrateur');
        $user->setDateNaissance(new DateTime('1990-01-01'));
        $user->setCreatedAt(new DateTimeImmutable());

        $user->setRole($this->roleRepo->findOneBy(['code' => 'ROLE_ADMIN']));
        $user->setService($this->serviceRepo->findAll()[0]);
        $user->setCategorie($this->categorieRepo->findAll()[0]);
        $user->setDomaine($this->domaineRepo->findAll()[0]);
        $user->setLieu($this->lieuRepo->findAll()[0]);

        $password = $this->hasher->hashPassword($user, 'admin123');
        $user->setPassword($password);

        $this->em->persist($user);
        $this->em->flush();

        $output->writeln('✅ Admin créé avec email: admin@admin.fr / mdp: admin123');

        return Command::SUCCESS;
    }
}
