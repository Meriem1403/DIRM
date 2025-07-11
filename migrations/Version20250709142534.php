<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250709142534 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE declaration_chantier ADD chantier_avec_contrat TINYINT(1) DEFAULT NULL, ADD adresse_chantier VARCHAR(255) DEFAULT NULL, ADD contact_chantier VARCHAR(255) DEFAULT NULL, ADD contact_architecte VARCHAR(255) DEFAULT NULL, ADD contact_organisme_classe VARCHAR(255) DEFAULT NULL, ADD numero_examen_ce VARCHAR(100) DEFAULT NULL, ADD nom_mandataire VARCHAR(100) DEFAULT NULL, ADD prenom_mandataire VARCHAR(100) DEFAULT NULL, ADD qualite_mandataire VARCHAR(50) DEFAULT NULL, ADD telephone_mandataire VARCHAR(100) DEFAULT NULL, ADD port_depart VARCHAR(100) DEFAULT NULL, ADD nb_equipage INT DEFAULT NULL, ADD nb_passagers INT DEFAULT NULL, ADD nb_personnel_special INT DEFAULT NULL, ADD eloignement_cote VARCHAR(50) DEFAULT NULL, ADD duree_sejour_mer VARCHAR(50) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE declaration_chantier DROP chantier_avec_contrat, DROP adresse_chantier, DROP contact_chantier, DROP contact_architecte, DROP contact_organisme_classe, DROP numero_examen_ce, DROP nom_mandataire, DROP prenom_mandataire, DROP qualite_mandataire, DROP telephone_mandataire, DROP port_depart, DROP nb_equipage, DROP nb_passagers, DROP nb_personnel_special, DROP eloignement_cote, DROP duree_sejour_mer');
    }
}
