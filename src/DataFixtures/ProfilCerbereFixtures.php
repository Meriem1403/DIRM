<?php

namespace App\DataFixtures;

use App\Entity\ProfilCerbere;
use App\Entity\ApplicationCerbere;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ProfilCerbereFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        /** @var ApplicationCerbere $application */
        $application = $this->getReference('app_411', ApplicationCerbere::class);

        $profils = [
            ['ADMINISTRATEUR', "Exploitation SI3"],
            ['CNLRE_CONSULTATION', "profil consultation CNLRRE"],
            ['CNLRE_MISE_A_JOUR', "profil mise à jour CNLRRE"],
            ['COMMANDE_LPM', "Fonctionnalités de délivrance de LPM, de consultation des attributions, de transfert de numéros de série, ...."],
            ['CONSULTANT_ENIM_CMAF_CROSS', "profil consultant ENIM/CMAF/CROSS"],
            ['CPA', "Profil mise à jour marins CPA ENIM"],
            ['DECIDEUR_GM3', "Profil ayant accès à toutes les fonctionnalités et qui sera attribué à GM3 exclusivement"],
            ['DELIVRANCE_LPM', "Accès aux fonctionnalités de commande de livrets professionnels maritimes (LPM)"],
            ['SAM_CULTURES_MARINES', "profil SAM‑Cultures marines"],
            ['SAM_ENSEIGNEMENT_MARITIME', "profil SAM‑Enseignement Maritime"],
            ['SAM_GM_ENIM_FORM_NAV', "profil GM‑ENIM/Formation Professionnelle/Navigation"],
            ['SAM_GM_ENIM_NAVIGATION', "profil GM/ENIM Navigation"],
            ['SAM_MATRICULE_GM_ENIM', "profil SAM‑Matricule GM/ENIM"],
            ['SAM_NAVIGATION', "profil SAM‑Navigation"],
            ['SAM_NAV_FORM', "profil Navigation/Formation Professionnelle"],
            ['SUPPORT_ADMINISTRE_ENTREPRISE', "profil support administré/Entreprise"],
            ['SUPPORT_CODIFICATION', "profil support codification"],
            ['SUPPORT_MARINS', "profil support marins"],
        ];

        foreach ($profils as [$nom, $description]) {
            $profil = new ProfilCerbere();
            $profil->setNom($nom);
            $profil->setDescription($description);
            $profil->setApplication($application);
            $manager->persist($profil);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ApplicationCerbereFixtures::class,
        ];
    }
}
