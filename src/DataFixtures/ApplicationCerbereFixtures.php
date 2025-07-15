<?php

namespace App\DataFixtures;

use App\Entity\ApplicationCerbere;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ApplicationCerbereFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $applications = [
            [
                'code' => '411',
                'nom' => 'ADM',
                'description' => 'Gestion des administrés du monde maritime et des professionnels de la mer (armateur, propriétaires de navires).',
            ],
            [
                'code' => '503',
                'nom' => 'AFFMAR-INFOCENTRE',
                'description' => 'Suivi de la vie professionnelle des marins.',
            ],
            [
                'code' => '370',
                'nom' => 'ALADIN',
                'description' => 'Gestion de la signalisation maritime et des avis aux navigateurs.',
            ],
            [
                'code' => '484',
                'nom' => 'ALIDADE',
                'description' => 'Application contrôle des activités maritimes.',
            ],
            [
                'code' => '580',
                'nom' => 'AMFORE',
                'description' => 'Suivi des formations des marins.',
            ],
            [
                'code' => '578',
                'nom' => 'ANNE',
                'description' => 'Application Numérique de Notification Électorale des pêches maritimes et des élevages marins.',
            ],
            [
                'code' => '669',
                'nom' => 'ANNE PREPROD',
                'description' => 'Application Numérique de Notification Électorale des pêches maritimes et des élevages marins (préproduction).',
            ],
            [
                'code' => '757',
                'nom' => 'ASTERIE NG',
                'description' => 'Visualisation des données 360 DGAMPA.',
            ],
            [
                'code' => '351',
                'nom' => 'CULTURES MARINES',
                'description' => 'Gestion des arrêtés de concession de cultures marines.',
            ],
            [
                'code' => '533',
                'nom' => 'ECUME',
                'description' => "Calcul et facturation des redevances domaniales pour les concessions de cultures marines.",
            ],
        ];

        foreach ($applications as $data) {
            $app = new ApplicationCerbere();
            $app->setCode($data['code']);
            $app->setNom($data['nom']);
            $app->setDescription($data['description']);

            $manager->persist($app);

            $this->addReference('app_' . strtolower($data['code']), $app);
        }

        $manager->flush();
    }
}
