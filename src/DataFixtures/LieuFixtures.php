<?php

namespace App\DataFixtures;

use App\Entity\Lieu;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LieuFixtures extends Fixture
{
    public const LIEUX = [
        'Marseille',
        'Toulon',
        'Nice',
        'Ajaccio',
        'Bastia',
        'Montpellier',
        'Sète',
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::LIEUX as $key => $nom) {
            $lieu = new Lieu();
            $lieu->setNom($nom);
            $manager->persist($lieu);

            // Pour y accéder dans d'autres fixtures (ex: ServiceFixtures)
            $this->addReference('lieu_' . strtolower($nom), $lieu);
        }

        $manager->flush();
    }
}
