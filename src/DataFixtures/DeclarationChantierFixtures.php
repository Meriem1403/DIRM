<?php

namespace App\DataFixtures;

use App\Entity\DeclarationChantier;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use DateTime;
use DateTimeImmutable;

class DeclarationChantierFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $chantier = new DeclarationChantier();
        $chantier->setNom('Dupont');
        $chantier->setPrenom('Jean');
        $chantier->setAdresse('12 quai de la Mer, 13000 Marseille');
        $chantier->setEmail('jean.dupont@example.com');
        $chantier->setTelephone('0601020304');
        $chantier->setTypeDemande('Construction');
        $chantier->setActivites(['Plaisance', 'Pêche']);
        $chantier->setNomNavire('Le Vent du Large');
        $chantier->setPavillonOrigine('France');
        $chantier->setQuartierImmatriculation('MA1234');
        $chantier->setJauge(22.5);
        $chantier->setLongueur(15.3);
        $chantier->setLargeur(4.5);
        $chantier->setPropulsion('Diesel');
        $chantier->setPuissanceKw(500);
        $chantier->setVitesse('20 nœuds');
        $chantier->setMateriau('Fibre de verre');
        $chantier->setEloignementCote('5 miles');
        $chantier->setDureeSejourMer('48 heures');
        $chantier->setDatePoseQuille(new DateTime('2023-04-01'));

        $chantier->setArchitecteNaval('Bureau Naval Marseille');
        $chantier->setArchitecteMail('contact@bureaunaval.fr');
        $chantier->setOrganismeClasse('Bureau Veritas');
        $chantier->setCategorieConception('B');
        $chantier->setNumeroSerie('SN-84726');
        $chantier->setNumeroCoque('COQ-92475');
        $chantier->setModulesEvaluation('Module A1');
        $chantier->setOrganismeNotifie('Bureau Veritas');
        $chantier->setNomChantier('Chantier Naval Méditerranée');
        $chantier->setNomChantierConception('Chantiers Atlantique');

        $chantier->setAutrePrenom('Paul');
        $chantier->setAutreQualite('Chef de projet');
        $chantier->setAutreTelephone('0611223344');
        $chantier->setChantierAvecContrat(true);
        $chantier->setAdresseChantier('Zone portuaire Est, 83500 La Seyne-sur-Mer');
        $chantier->setContactChantier('chantier@mediterranee.fr');
        $chantier->setContactArchitecte('Jean Architecte');
        $chantier->setContactOrganismeClasse('Valérie Classe');
        $chantier->setNumeroExamenCe('CE-9234-MED');

        $chantier->setNomMandataire('Martin');
        $chantier->setPrenomMandataire('Claire');
        $chantier->setQualiteMandataire('Responsable juridique');
        $chantier->setTelephoneMandataire('0612457890');
        $chantier->setEmailMandataire('claire.martin@mandataires.fr');

        $chantier->setNomMandataireIa('Leclerc');
        $chantier->setPrenomMandataireIa('Olivier');
        $chantier->setQualiteMandataireIa('Interlocuteur agréé');
        $chantier->setTelephoneMandataireIa('0625478931');
        $chantier->setEmailMandataireIa('olivier.leclerc@ia.fr');

        $chantier->setPortDepart('Port de Marseille');
        $chantier->setStatut('soumis');
        $chantier->setDateSoumission(new DateTimeImmutable('2024-06-20'));
        $chantier->setDateValidation(null); // non validé pour l'exemple

        // Si un User admin existe déjà en fixtures, ajouter la référence
        $chantier->setValidePar(null); // ou : $this->getReference('admin_user', User::class);

        $chantier->setRecepissePath(null);
        $chantier->setNoteExplicativePath(null);

        $chantier->setNbEquipage(5);
        $chantier->setNbPassagers(12);
        $chantier->setNbPersonnelSpecial(3);

        $manager->persist($chantier);

        $this->addReference('chantier_1', $chantier);

        $manager->flush();
    }
}
