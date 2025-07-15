<?php

namespace App\DataFixtures;

use App\Entity\PersonneABord;
use App\Entity\DeclarationChantier;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ProfilABordFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        /** @var DeclarationChantier $chantier */
        $chantier = $this->getReference('chantier_1', DeclarationChantier::class);

        $donnees = [
            ['equipage' => 5, 'passagers' => 12, 'personnes' => 3],
            ['equipage' => 3, 'passagers' => 8, 'personnes' => 1],
            ['equipage' => 10, 'passagers' => 25, 'personnes' => 5],
        ];

        foreach ($donnees as $data) {
            $personne = new PersonneABord();
            $personne->setEquipage($data['equipage']);
            $personne->setPassagers($data['passagers']);
            $personne->setPersonnes($data['personnes']);
            $personne->setDeclarationChantier($chantier);
            $manager->persist($personne);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            DeclarationChantierFixtures::class,
        ];
    }
}
