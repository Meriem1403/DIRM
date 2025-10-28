<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251028122754 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE goudurix ADD auteur_retour_id INT DEFAULT NULL, ADD mesure_en_cours LONGTEXT DEFAULT NULL, ADD mesure_mise_en_place TINYINT(1) NOT NULL, ADD retour_action LONGTEXT DEFAULT NULL, ADD date_retour DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE goudurix ADD CONSTRAINT FK_14DAC2483231B1D5 FOREIGN KEY (auteur_retour_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_14DAC2483231B1D5 ON goudurix (auteur_retour_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE goudurix DROP FOREIGN KEY FK_14DAC2483231B1D5');
        $this->addSql('DROP INDEX IDX_14DAC2483231B1D5 ON goudurix');
        $this->addSql('ALTER TABLE goudurix DROP auteur_retour_id, DROP mesure_en_cours, DROP mesure_mise_en_place, DROP retour_action, DROP date_retour');
    }
}
