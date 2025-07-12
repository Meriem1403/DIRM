<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Repository\CategorieRepository;
use App\Repository\DomaineServiceRepository;
use App\Repository\LieuRepository;
use App\Repository\RoleRepository;
use App\Repository\ServiceRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use DateTime;
use DateTimeImmutable;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use RuntimeException;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private readonly UserPasswordHasherInterface $hasher,
        private readonly RoleRepository $roleRepository,
        private readonly ServiceRepository $serviceRepository,
        private readonly DomaineServiceRepository $domaineRepository,
        private readonly LieuRepository $lieuRepository,
        private readonly CategorieRepository $categorieRepository
    ) {}

    public function load(ObjectManager $manager): void
    {
        $roles = $this->roleRepository->findAll();
        $services = $this->serviceRepository->findAll();
        $domaines = $this->domaineRepository->findAll();
        $lieux = $this->lieuRepository->findAll();
        $categories = $this->categorieRepository->findAll();

        if (empty($roles) || empty($services) || empty($domaines) || empty($lieux) || empty($categories)) {
            throw new RuntimeException('Certaines dépendances sont manquantes dans la base de données (roles, services, domaines, lieux, catégories). Vérifie les autres fixtures.');
        }

        foreach ($roles as $role) {
            $user = new User();
            $user->setEmail(strtolower($role->getCode()) . '@example.com');
            $user->setNom('Nom' . $role->getCode());
            $user->setPrenom('Prénom' . $role->getCode());
            $user->setPoste('Développeur');
            $user->setAdresse('123 rue des Tests');
            $user->setCodePostal('13000');
            $user->setVille('Marseille');
            $user->setPays('France');
            $user->setDateNaissance(new DateTime('1990-01-01'));
            $user->setCreatedAt(new DateTimeImmutable());
            $user->setRole($role);
            $user->setService($services[array_rand($services)]);
            $user->setDomaine($domaines[array_rand($domaines)]);
            $user->setLieu($lieux[array_rand($lieux)]);
            $user->setCategorie($categories[array_rand($categories)]);

            $password = $this->hasher->hashPassword($user, 'admin123');
            $user->setPassword($password);

            $manager->persist($user);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            RoleFixtures::class,
            ServiceFixtures::class,
            DomaineServiceFixtures::class,
            LieuFixtures::class,
            CategorieFixtures::class,
        ];
    }
}
