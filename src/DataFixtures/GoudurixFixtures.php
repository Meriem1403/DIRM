<?php

namespace App\DataFixtures;

use App\Entity\Goudurix;
use App\Entity\User;
use App\Entity\Service;
use App\Entity\Lieu;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class GoudurixFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Récupérer les entités existantes
        $users = $manager->getRepository(User::class)->findAll();
        $services = $manager->getRepository(Service::class)->findAll();
        $lieux = $manager->getRepository(Lieu::class)->findAll();

        if (empty($users) || empty($services)) {
            return; // Pas de données de base disponibles
        }

        $risques = [
            // Risques CROSS Med
            [
                'titre' => 'Défaillance système de communication',
                'description' => 'Risque de panne du système de communication radio maritime pouvant compromettre la coordination des secours en mer.',
                'niveauRisque' => 'critique',
                'statut' => 'en_cours',
                'categorie' => 'Sécurité maritime',
                'source' => 'Maintenance préventive',
                'probabilite' => 2,
                'gravite' => 5,
                'mesuresPreventives' => 'Maintenance préventive régulière, système de secours en place',
                'mesuresCorrectives' => 'Remplacement des équipements défaillants, formation des opérateurs',
                'commentaires' => 'Risque critique pour la sécurité maritime',
                'service_nom' => 'CROSS Med'
            ],
            [
                'titre' => 'Erreur de navigation',
                'description' => 'Risque d\'erreur dans le guidage des navires en détresse vers les zones de secours.',
                'niveauRisque' => 'élevé',
                'statut' => 'surveillé',
                'categorie' => 'Sécurité maritime',
                'source' => 'Retour d\'expérience',
                'probabilite' => 3,
                'gravite' => 4,
                'mesuresPreventives' => 'Formation continue des opérateurs, procédures standardisées',
                'mesuresCorrectives' => 'Amélioration des outils de navigation, double vérification',
                'commentaires' => 'Surveillance renforcée des procédures',
                'service_nom' => 'CROSS Med'
            ],
            // Risques Service RH
            [
                'titre' => 'Risque de chute de hauteur',
                'description' => 'Risque de chute lors des travaux de maintenance sur les équipements en hauteur. Les agents peuvent être exposés à des chutes de plus de 3 mètres.',
                'niveauRisque' => 'élevé',
                'statut' => 'en_cours',
                'categorie' => 'Sécurité',
                'source' => 'Inspection sécurité',
                'probabilite' => 3,
                'gravite' => 4,
                'mesuresPreventives' => 'Formation aux travaux en hauteur, utilisation d\'EPI adaptés, vérification des équipements',
                'mesuresCorrectives' => 'Installation de garde-corps, mise en place de filets de sécurité',
                'commentaires' => 'Risque identifié lors de l\'audit sécurité mensuel',
                'service_nom' => 'Service RH'
            ],
            [
                'titre' => 'Exposition aux produits chimiques',
                'description' => 'Exposition potentielle aux produits chimiques utilisés pour le nettoyage des équipements techniques.',
                'niveauRisque' => 'moyen',
                'statut' => 'traité',
                'categorie' => 'Santé',
                'source' => 'Fiche de poste',
                'probabilite' => 2,
                'gravite' => 3,
                'mesuresPreventives' => 'Formation aux risques chimiques, utilisation de gants et masques',
                'mesuresCorrectives' => 'Substitution par des produits moins dangereux, amélioration de la ventilation',
                'commentaires' => 'Risque traité avec succès',
                'service_nom' => 'Service RH'
            ],
            // Risques Service Informatique
            [
                'titre' => 'Cyberattaque',
                'description' => 'Risque de cyberattaque pouvant compromettre la sécurité des données et des systèmes informatiques.',
                'niveauRisque' => 'critique',
                'statut' => 'surveillé',
                'categorie' => 'Cybersécurité',
                'source' => 'Audit sécurité',
                'probabilite' => 2,
                'gravite' => 5,
                'mesuresPreventives' => 'Formation des utilisateurs, mise à jour des systèmes, pare-feu',
                'mesuresCorrectives' => 'Renforcement de la sécurité, audit de vulnérabilités',
                'commentaires' => 'Surveillance 24h/24 des systèmes',
                'service_nom' => 'Service Informatique'
            ],
            [
                'titre' => 'Perte de données',
                'description' => 'Risque de perte de données critiques en cas de panne matérielle ou logicielle.',
                'niveauRisque' => 'élevé',
                'statut' => 'en_cours',
                'categorie' => 'Sécurité informatique',
                'source' => 'Analyse des risques',
                'probabilite' => 3,
                'gravite' => 4,
                'mesuresPreventives' => 'Sauvegardes automatiques, redondance des systèmes',
                'mesuresCorrectives' => 'Mise en place de sauvegardes cloud, tests de restauration',
                'commentaires' => 'Plan de continuité d\'activité en cours',
                'service_nom' => 'Service Informatique'
            ],
            // Risques Polmar
            [
                'titre' => 'Pollution marine',
                'description' => 'Risque de pollution marine lors des opérations de lutte contre les pollutions.',
                'niveauRisque' => 'élevé',
                'statut' => 'en_cours',
                'categorie' => 'Environnement',
                'source' => 'Exercice Polmar',
                'probabilite' => 3,
                'gravite' => 4,
                'mesuresPreventives' => 'Formation des équipes, équipements de protection',
                'mesuresCorrectives' => 'Amélioration des procédures, nouveaux équipements',
                'commentaires' => 'Exercice annuel programmé',
                'service_nom' => 'Polmar'
            ],
            [
                'titre' => 'Risque électrique',
                'description' => 'Risque d\'électrocution lors des interventions sur les installations électriques.',
                'niveauRisque' => 'critique',
                'statut' => 'surveillé',
                'categorie' => 'Sécurité',
                'source' => 'Accident du travail',
                'probabilite' => 2,
                'gravite' => 5,
                'mesuresPreventives' => 'Formation électrique, utilisation d\'outils isolés, respect des consignes',
                'mesuresCorrectives' => 'Mise en place de procédures de consignation, formation renforcée',
                'commentaires' => 'Surveillance renforcée suite à un incident'
            ],
            [
                'titre' => 'Troubles musculo-squelettiques',
                'description' => 'Risque de TMS lié aux gestes répétitifs et aux postures contraignantes.',
                'niveauRisque' => 'moyen',
                'statut' => 'en_cours',
                'categorie' => 'Santé',
                'source' => 'Médecine du travail',
                'probabilite' => 4,
                'gravite' => 2,
                'mesuresPreventives' => 'Formation à l\'ergonomie, pauses régulières, rotation des postes',
                'mesuresCorrectives' => 'Amélioration de l\'ergonomie des postes de travail',
                'commentaires' => 'Surveillance médicale renforcée'
            ],
            [
                'titre' => 'Pollution sonore',
                'description' => 'Exposition au bruit des équipements techniques dépassant les seuils réglementaires.',
                'niveauRisque' => 'faible',
                'statut' => 'traité',
                'categorie' => 'Environnement',
                'source' => 'Mesures acoustiques',
                'probabilite' => 3,
                'gravite' => 2,
                'mesuresPreventives' => 'Port de protections auditives, limitation du temps d\'exposition',
                'mesuresCorrectives' => 'Installation d\'écrans acoustiques, maintenance des équipements',
                'commentaires' => 'Niveaux sonores ramenés dans les normes'
            ]
        ];

        foreach ($risques as $index => $risqueData) {
            $risque = new Goudurix();
            $risque->setTitre($risqueData['titre']);
            $risque->setDescription($risqueData['description']);
            $risque->setNiveauRisque($risqueData['niveauRisque']);
            $risque->setStatut($risqueData['statut']);
            $risque->setCategorie($risqueData['categorie']);
            $risque->setSource($risqueData['source']);
            $risque->setProbabilite($risqueData['probabilite']);
            $risque->setGravite($risqueData['gravite']);
            $risque->setMesuresPreventives($risqueData['mesuresPreventives']);
            $risque->setMesuresCorrectives($risqueData['mesuresCorrectives']);
            $risque->setCommentaires($risqueData['commentaires']);
            
            // Dates
            $risque->setDateDetection(new \DateTime('-' . rand(1, 90) . ' days'));
            if ($risqueData['statut'] === 'traité') {
                $risque->setDateResolution(new \DateTime('-' . rand(1, 30) . ' days'));
            }
            
            // Relations
            $risque->setResponsable($users[array_rand($users)]);
            $risque->setCreateur($users[array_rand($users)]);
            
            // Assigner le service selon le nom spécifié ou aléatoirement
            if (isset($risqueData['service_nom'])) {
                $serviceSpecifique = null;
                foreach ($services as $service) {
                    if ($service->getNom() === $risqueData['service_nom']) {
                        $serviceSpecifique = $service;
                        break;
                    }
                }
                $risque->setService($serviceSpecifique ?: $services[array_rand($services)]);
            } else {
                $risque->setService($services[array_rand($services)]);
            }
            
            if (!empty($lieux)) {
                $risque->setLieu($lieux[array_rand($lieux)]);
            }
            
            // Ajouter quelques observateurs
            $nbObservateurs = rand(1, 3);
            $observateurs = array_rand($users, min($nbObservateurs, count($users)));
            if (!is_array($observateurs)) {
                $observateurs = [$observateurs];
            }
            
            foreach ($observateurs as $observateurIndex) {
                $risque->addObservateur($users[$observateurIndex]);
            }
            
            // Calculer le score de risque
            $risque->setScoreRisque($risque->getProbabilite() * $risque->getGravite());
            
            $manager->persist($risque);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            ServiceFixtures::class,
            LieuFixtures::class,
        ];
    }
}
