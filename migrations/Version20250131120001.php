<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Migration pour ajouter la relation nom_risque dans goudurix
 */
final class Version20250131120001 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ajoute la colonne nom_risque_id dans la table goudurix';
    }

    public function up(Schema $schema): void
    {
        // Ajouter la colonne nom_risque_id dans goudurix
        $this->addSql('ALTER TABLE goudurix ADD nom_risque_id INT DEFAULT NULL');
        $this->addSql('CREATE INDEX IDX_goudurix_nom_risque ON goudurix (nom_risque_id)');
        $this->addSql('ALTER TABLE goudurix ADD CONSTRAINT FK_goudurix_nom_risque FOREIGN KEY (nom_risque_id) REFERENCES nom_risque (id)');
    }

    public function down(Schema $schema): void
    {
        // Supprimer la colonne nom_risque_id
        $this->addSql('ALTER TABLE goudurix DROP FOREIGN KEY FK_goudurix_nom_risque');
        $this->addSql('DROP INDEX IDX_goudurix_nom_risque ON goudurix');
        $this->addSql('ALTER TABLE goudurix DROP nom_risque_id');
    }
}

