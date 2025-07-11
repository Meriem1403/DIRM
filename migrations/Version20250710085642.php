<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250710085642 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE declaration_chantier DROP nb_equipage, DROP nb_passagers, DROP nb_personnel_special');
        $this->addSql('ALTER TABLE personne_abord ADD equipage INT DEFAULT NULL, ADD passagers INT DEFAULT NULL, ADD personnes INT DEFAULT NULL, DROP fonction, DROP nombre, DROP observations');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE declaration_chantier ADD nb_equipage INT DEFAULT NULL, ADD nb_passagers INT DEFAULT NULL, ADD nb_personnel_special INT DEFAULT NULL');
        $this->addSql('ALTER TABLE personne_abord ADD fonction VARCHAR(100) NOT NULL, ADD nombre INT NOT NULL, ADD observations LONGTEXT DEFAULT NULL, DROP equipage, DROP passagers, DROP personnes');
    }
}
