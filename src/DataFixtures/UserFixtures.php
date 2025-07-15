<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Role;
use App\Entity\Service;
use App\Entity\Lieu;
use App\Entity\DomaineService;
use App\Entity\Categorie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use DateTimeImmutable;
use DateTime;
use RuntimeException;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private readonly UserPasswordHasherInterface $hasher,
    ) {}

    public function load(ObjectManager $manager): void
    {
        // Liste réaliste d'utilisateurs pour la DIRM
        $utilisateurs = [
            [
                'email' => 'admin.dirm@example.com',
                'nom' => 'Durand',
                'prenom' => 'Alice',
                'role' => 'ROLE_ADMIN',
                'poste' => 'Administratrice DIRM',
                'service' => 'Secrétariat Général',
            ],
            [
                'email' => 'chef.polmar@example.com',
                'nom' => 'Lemoine',
                'prenom' => 'Bernard',
                'role' => 'ROLE_CHEF',
                'poste' => 'Chef du service POLMAR',
                'service' => 'Polmar',
            ],
            [
                'email' => 'agent.csn@example.com',
                'nom' => 'Martin',
                'prenom' => 'Sophie',
                'role' => 'ROLE_AGENT',
                'poste' => 'Inspectrice Sécurité Navires',
                'service' => 'CSN',
            ],
            [
                'email' => 'agent.cross@example.com',
                'nom' => 'Garcia',
                'prenom' => 'Julien',
                'role' => 'ROLE_AGENT',
                'poste' => 'Opérateur CROSS MED',
                'service' => 'CROSS Med',
            ],
            [
                'email' => 'prof.lycee@example.com',
                'nom' => 'Moreau',
                'prenom' => 'Claire',
                'role' => 'ROLE_AGENT',
                'poste' => 'Professeur maritime',
                'service' => 'Lycée Professionnel Maritime',
            ],
        ];

        $roles = $manager->getRepository(Role::class)->findAll();
        $services = $manager->getRepository(Service::class)->findAll();
        $lieux = $manager->getRepository(Lieu::class)->findAll();
        $domaines = $manager->getRepository(DomaineService::class)->findAll();
        $categories = $manager->getRepository(Categorie::class)->findAll();

        $rolesByCode = [];
        foreach ($roles as $role) {
            $rolesByCode[$role->getCode()] = $role;
        }

        $servicesByName = [];
        foreach ($services as $service) {
            $servicesByName[$service->getNom()] = $service;
        }

        $adminUser = null;

        foreach ($utilisateurs as $data) {
            $user = new User();
            $user->setEmail($data['email']);
            $user->setNom($data['nom']);
            $user->setPrenom($data['prenom']);
            $user->setPoste($data['poste']);
            $user->setAdresse('123 rue de la République');
            $user->setCodePostal('13001');
            $user->setVille('Marseille');
            $user->setPays('France');
            $user->setDateNaissance(new DateTime('1985-06-15'));
            $user->setCreatedAt(new DateTimeImmutable());

            if (!isset($rolesByCode[$data['role']])) {
                throw new RuntimeException("Rôle non trouvé : " . $data['role']);
            }
            $user->setRole($rolesByCode[$data['role']]);
            if (!isset($servicesByName[$data['service']])) {
                throw new RuntimeException("Service non trouvé : " . $data['service']);
            }
            $user->setService($servicesByName[$data['service']]);
            $user->setDomaine($domaines[array_rand($domaines)]);
            $user->setLieu($lieux[array_rand($lieux)]);
            $user->setCategorie($categories[array_rand($categories)]);

            $hashedPassword = $this->hasher->hashPassword($user, 'admin123');
            $user->setPassword($hashedPassword);

            if ($data['email'] === 'admin.dirm@example.com') {
                $adminUser = $user;
            }


            if ($adminUser && $user !== $adminUser) {
                $user->setCreatedBy($adminUser);
            }

            $manager->persist($user);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            RoleFixtures::class,
            ServiceFixtures::class,
            LieuFixtures::class,
            DomaineServiceFixtures::class,
            CategorieFixtures::class,
        ];
    }
}


