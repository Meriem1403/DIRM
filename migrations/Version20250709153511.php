<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250709153511 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE personne_abord (id INT AUTO_INCREMENT NOT NULL, declaration_chantier_id INT NOT NULL, fonction VARCHAR(100) NOT NULL, nombre INT NOT NULL, observations LONGTEXT DEFAULT NULL, INDEX IDX_F3E3B529F8F0B84A (declaration_chantier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE personne_abord ADD CONSTRAINT FK_F3E3B529F8F0B84A FOREIGN KEY (declaration_chantier_id) REFERENCES declaration_chantier (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE personne_abord DROP FOREIGN KEY FK_F3E3B529F8F0B84A');
        $this->addSql('DROP TABLE personne_abord');
    }
}
