<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250710131241 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE declaration_chantier DROP qualite_declarant, DROP type_projet, DROP chantier_naval');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE declaration_chantier ADD qualite_declarant VARCHAR(50) NOT NULL, ADD type_projet VARCHAR(50) NOT NULL, ADD chantier_naval VARCHAR(255) NOT NULL');
    }
}
