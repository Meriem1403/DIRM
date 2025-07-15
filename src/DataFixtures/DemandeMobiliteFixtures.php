<?php

namespace App\DataFixtures;

use App\Entity\DemandeMobilite;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use DateTimeImmutable;
use RuntimeException;
use DateTime;

class DemandeMobiliteFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $users = $manager->getRepository(User::class)->findAll();

        if (empty($users)) {
            throw new RuntimeException('Aucun utilisateur trouvé pour créer les demandes de mobilité.');
        }

        $donnees = [
            [
                'prenom' => 'Jean',
                'nom' => 'Dupont',
                'statutAgent' => 'Titulaire',
                'statut' => 'en_attente',
                'corps' => 'Attaché',
                'grade' => 'Attaché principal',
                'typeDemande' => 'arrivée',
                'ministereOrigine' => 'Ministère de l\'Écologie',
                'directionOrigine' => 'DREAL PACA',
                'serviceOrigine' => 'Unité Logistique',
                'serviceActuel' => null,
                'dateDepart' => null,
                'motifDepart' => null,
                'natureMutation' => 'externe',
                'datePrisePoste' => new DateTime('+1 month'),
                'serviceAffectation' => 'Service RH',
                'siteGeographique' => 'Site Marseille',
                'bureau' => 'B101',
                'fonction' => 'Chargé de mission RH',
                'posteRemplacement' => true,
                'posteCreation' => false,
                'prenomRemplace' => 'Paul',
                'nomRemplace' => 'Martin',
                'besoinMobilier' => true,
                'besoinFournitures' => true,
                'besoinInformatique' => true,
                'carteANTS' => 'oui',
                'carteAchats' => 'non',
                'chargeVoyages' => 'oui',
                'correspondantBudgetaire' => 'non',
                'encadreAgents' => 'oui',
                'utiliseChorus' => 'oui',
                'commentaire' => 'Demande urgente',
            ],
            [
                'prenom' => 'Sophie',
                'nom' => 'Martin',
                'statutAgent' => 'Contractuelle',
                'statut' => 'traitee',
                'corps' => 'SAENES',
                'grade' => 'Classe normale',
                'typeDemande' => 'départ',
                'ministereOrigine' => null,
                'directionOrigine' => null,
                'serviceOrigine' => 'Service Budget',
                'serviceActuel' => 'Service Budget',
                'dateDepart' => new DateTime('-1 week'),
                'motifDepart' => 'Départ à la retraite',
                'natureMutation' => 'retraite',
                'datePrisePoste' => null,
                'serviceAffectation' => null,
                'siteGeographique' => 'Site Toulon',
                'bureau' => null,
                'fonction' => 'Gestionnaire budgétaire',
                'posteRemplacement' => false,
                'posteCreation' => false,
                'prenomRemplace' => null,
                'nomRemplace' => null,
                'besoinMobilier' => false,
                'besoinFournitures' => false,
                'besoinInformatique' => false,
                'carteANTS' => 'non',
                'carteAchats' => 'non',
                'chargeVoyages' => 'non',
                'correspondantBudgetaire' => 'oui',
                'encadreAgents' => 'non',
                'utiliseChorus' => 'non',
                'commentaire' => 'Départ organisé depuis 6 mois',
            ],
            [
                'prenom' => 'Lucas',
                'nom' => 'Morel',
                'statutAgent' => 'Titulaire',
                'statut' => 'refusee',
                'corps' => 'Attaché',
                'grade' => 'Attaché',
                'typeDemande' => 'arrivée',
                'ministereOrigine' => 'Ministère de l\'Intérieur',
                'directionOrigine' => 'Préfecture de l\'Hérault',
                'serviceOrigine' => 'Service Accueil',
                'serviceActuel' => null,
                'dateDepart' => null,
                'motifDepart' => null,
                'natureMutation' => 'interne',
                'datePrisePoste' => new DateTime('+3 weeks'),
                'serviceAffectation' => 'Service Communication',
                'siteGeographique' => 'Site Montpellier',
                'bureau' => 'C204',
                'fonction' => 'Chargé de communication',
                'posteRemplacement' => true,
                'posteCreation' => false,
                'prenomRemplace' => 'Claire',
                'nomRemplace' => 'Dumas',
                'besoinMobilier' => true,
                'besoinFournitures' => false,
                'besoinInformatique' => true,
                'carteANTS' => 'oui',
                'carteAchats' => 'oui',
                'chargeVoyages' => 'non',
                'correspondantBudgetaire' => 'non',
                'encadreAgents' => 'oui',
                'utiliseChorus' => 'oui',
                'commentaire' => 'En attente de validation RH',
            ],
        ];

        foreach ($donnees as $data) {
            $demande = new DemandeMobilite();
            $demande->setPrenom($data['prenom']);
            $demande->setNom($data['nom']);
            $demande->setStatutAgent($data['statutAgent']);
            $demande->setStatut($data['statut']);
            $demande->setCorps($data['corps']);
            $demande->setGrade($data['grade']);
            $demande->setTypeDemande($data['typeDemande']);
            $demande->setMinistereOrigine($data['ministereOrigine']);
            $demande->setDirectionOrigine($data['directionOrigine']);
            $demande->setServiceOrigine($data['serviceOrigine']);
            $demande->setServiceActuel($data['serviceActuel']);
            $demande->setDateDepart($data['dateDepart']);
            $demande->setMotifDepart($data['motifDepart']);
            $demande->setNatureMutation($data['natureMutation']);
            $demande->setDatePrisePoste($data['datePrisePoste']);
            $demande->setServiceAffectation($data['serviceAffectation']);
            $demande->setSiteGeographique($data['siteGeographique']);
            $demande->setBureau($data['bureau']);
            $demande->setFonction($data['fonction']);
            $demande->setPosteRemplacement($data['posteRemplacement']);
            $demande->setPosteCreation($data['posteCreation']);
            $demande->setPrenomRemplace($data['prenomRemplace']);
            $demande->setNomRemplace($data['nomRemplace']);
            $demande->setBesoinMobilier($data['besoinMobilier']);
            $demande->setBesoinFournitures($data['besoinFournitures']);
            $demande->setBesoinInformatique($data['besoinInformatique']);
            $demande->setCarteANTS($data['carteANTS']);
            $demande->setCarteAchats($data['carteAchats']);
            $demande->setChargeVoyages($data['chargeVoyages']);
            $demande->setCorrespondantBudgetaire($data['correspondantBudgetaire']);
            $demande->setEncadreAgents($data['encadreAgents']);
            $demande->setUtiliseChorus($data['utiliseChorus']);
            $demande->setCreatedAt(new DateTimeImmutable());
            $demande->setCreatedBy($users[array_rand($users)]);
            $demande->setCommentaire($data['commentaire']);

            $manager->persist($demande);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
