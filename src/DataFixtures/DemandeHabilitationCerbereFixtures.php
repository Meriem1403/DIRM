<?php

namespace App\DataFixtures;

use App\Entity\DemandeHabilitationCerbere;
use App\Entity\User;
use App\Entity\ApplicationCerbere;
use App\Entity\ProfilCerbere;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use DateTimeImmutable;
use RuntimeException;

class DemandeHabilitationCerbereFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $users = $manager->getRepository(User::class)->findAll();
        $applications = $manager->getRepository(ApplicationCerbere::class)->findAll();
        $profils = $manager->getRepository(ProfilCerbere::class)->findAll();

        if (count($users) < 3 || empty($applications) || empty($profils)) {
            throw new RuntimeException('Il faut au moins 3 utilisateurs, une application et un profil pour créer des demandes d’habilitation Cerbère.');
        }

        $agent = $users[0];
        $demandeur = $users[1];
        $valideur = $users[2];

        // Demande 1 - validée
        $demande1 = new DemandeHabilitationCerbere();
        $demande1->setAgent($agent);
        $demande1->setDemandeur($demandeur);
        $demande1->setStatut('VALIDEE');
        $demande1->setDateSoumission(new DateTimeImmutable('-5 days'));
        $demande1->setValidePar($valideur);
        $demande1->setDateValidation(new DateTimeImmutable('-2 days'));
        $demande1->setReglePortee('DGAMPA');
        $demande1->setRestrictions('Accès limité à la consultation');

        $demande1->addApplication($applications[0]);
        $demande1->addProfil($profils[0]);

        $manager->persist($demande1);

        // Demande 2 - en attente
        $demande2 = new DemandeHabilitationCerbere();
        $demande2->setAgent($users[2]);
        $demande2->setDemandeur($users[0]);
        $demande2->setStatut('EN_ATTENTE');
        $demande2->setDateSoumission(new DateTimeImmutable('-3 days'));
        $demande2->setReglePortee('DIRM');
        $demande2->setRestrictions(null);

        $demande2->addApplication($applications[1]);
        $demande2->addProfil($profils[1]);

        $manager->persist($demande2);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            ApplicationCerbereFixtures::class,
            ProfilCerbereFixtures::class,
        ];
    }
}
