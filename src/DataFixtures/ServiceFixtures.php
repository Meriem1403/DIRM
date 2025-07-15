<?php

namespace App\DataFixtures;

use App\Entity\Service;
use App\Entity\DomaineService;
use App\Entity\Lieu;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ServiceFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $services = [
            [
                'nom' => 'Service RH',
                'domaines' => ['domaine_ressources_humaines'],
                'lieux' => ['lieu_marseille', 'lieu_toulon'],
            ],
            [
                'nom' => 'Service Informatique',
                'domaines' => ['domaine_informatique'],
                'lieux' => ['lieu_marseille', 'lieu_ajaccio'],
            ],
            [
                'nom' => 'Service Budget',
                'domaines' => ['domaine_budget'],
                'lieux' => ['lieu_sète', 'lieu_montpellier'],
            ],
            [
                'nom' => 'Secrétariat Général',
                'domaines' => ['domaine_budget'],
                'lieux' => ['lieu_marseille', 'lieu_montpellier'],
            ],
            [
                'nom' => 'Polmar',
                'domaines' => ['domaine_budget'], // à adapter selon ta logique
                'lieux' => ['lieu_marseille'],
            ],
            [
                'nom' => 'CSN',
                'domaines' => ['domaine_ressources_humaines'],
                'lieux' => ['lieu_toulon'],
            ],
            [
                'nom' => 'CROSS Med',
                'domaines' => ['domaine_informatique'],
                'lieux' => ['lieu_ajaccio'],
            ],
            [
                'nom' => 'Lycée Professionnel Maritime',
                'domaines' => ['domaine_enseignement'],
                'lieux' => ['lieu_marseille'],
            ],

        ];

        foreach ($services as $data) {
            $service = new Service();
            $service->setNom($data['nom']);

            foreach ($data['domaines'] as $ref) {
                /** @var DomaineService $domaine */
                $domaine = $this->getReference($ref, DomaineService::class);
                $service->addDomaine($domaine);
            }

            foreach ($data['lieux'] as $ref) {
                /** @var Lieu $lieu */
                $lieu = $this->getReference($ref, Lieu::class);
                $service->addLieu($lieu);
            }

            $manager->persist($service);
            $this->addReference('service_' . strtolower(str_replace(' ', '_', $data['nom'])), $service);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            DomaineServiceFixtures::class,
            LieuFixtures::class,
        ];
    }
}
