<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250710125517 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE declaration_chantier ADD email_organisme VARCHAR(100) DEFAULT NULL, ADD nom_chantier_conception VARCHAR(100) DEFAULT NULL, ADD email_mandataire VARCHAR(100) DEFAULT NULL, ADD nom_mandataire_ia VARCHAR(100) DEFAULT NULL, ADD prenom_mandataire_ia VARCHAR(100) DEFAULT NULL, ADD qualite_mandataire_ia VARCHAR(50) DEFAULT NULL, ADD telephone_mandataire_ia VARCHAR(100) DEFAULT NULL, ADD email_mandataire_ia VARCHAR(100) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE declaration_chantier DROP email_organisme, DROP nom_chantier_conception, DROP email_mandataire, DROP nom_mandataire_ia, DROP prenom_mandataire_ia, DROP qualite_mandataire_ia, DROP telephone_mandataire_ia, DROP email_mandataire_ia');
    }
}
