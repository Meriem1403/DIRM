<?php

namespace App\DataFixtures;

use App\Entity\DomaineService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class DomaineServiceFixtures extends Fixture
{
    public const DOMAINES = [
        'Ressources humaines',
        'Informatique',
        'Budget',
        'Logistique',
        'Affaires juridiques',
        'Communication',
        'Sécurité maritime',
        'enseignement',
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::DOMAINES as $nom) {
            $domaine = new DomaineService();
            $domaine->setNom($nom);
            $manager->persist($domaine);

            // Pour pouvoir le réutiliser dans les autres fixtures (ex: ServiceFixtures)
            $this->addReference('domaine_' . strtolower(str_replace(' ', '_', $nom)), $domaine);
        }

        $manager->flush();
    }
}
