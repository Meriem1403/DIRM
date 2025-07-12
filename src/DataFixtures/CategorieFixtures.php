<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategorieFixtures extends Fixture
{
    public const CATEGORIES = [
        'A',
        'B',
        'C',
        'Vacataire',
        'Contractuel',
        'Stagiaire',
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::CATEGORIES as $key => $nom) {
            $categorie = new Categorie();
            $categorie->setNom($nom);
            $manager->persist($categorie);

            // Pour les relations ultérieures (ex : UserFixtures)
            $this->addReference('categorie_' . strtolower($nom), $categorie);
        }

        $manager->flush();
    }
}
