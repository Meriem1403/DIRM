<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250710095921 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE declaration_chantier ADD architecte_mail VARCHAR(255) DEFAULT NULL, ADD nb_equipage INT DEFAULT NULL, ADD nb_passagers INT DEFAULT NULL, ADD nb_personnel_special INT DEFAULT NULL, CHANGE eloignement_cote eloignement_cote VARCHAR(100) NOT NULL, CHANGE duree_sejour_mer duree_sejour_mer VARCHAR(100) NOT NULL, CHANGE autre_nom nom_chantier VARCHAR(100) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE declaration_chantier DROP architecte_mail, DROP nb_equipage, DROP nb_passagers, DROP nb_personnel_special, CHANGE eloignement_cote eloignement_cote VARCHAR(50) DEFAULT NULL, CHANGE duree_sejour_mer duree_sejour_mer VARCHAR(50) DEFAULT NULL, CHANGE nom_chantier autre_nom VARCHAR(100) DEFAULT NULL');
    }
}
