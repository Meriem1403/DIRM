<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251028104734 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE goudurix (id INT AUTO_INCREMENT NOT NULL, responsable_id INT NOT NULL, createur_id INT DEFAULT NULL, service_id INT NOT NULL, lieu_id INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, niveau_risque VARCHAR(50) NOT NULL, statut VARCHAR(50) NOT NULL, date_detection DATE NOT NULL, date_resolution DATE DEFAULT NULL, mesures_preventives LONGTEXT DEFAULT NULL, mesures_correctives LONGTEXT DEFAULT NULL, commentaires LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, categorie VARCHAR(100) DEFAULT NULL, source VARCHAR(100) DEFAULT NULL, probabilite INT DEFAULT NULL, gravite INT DEFAULT NULL, score_risque INT DEFAULT NULL, INDEX IDX_14DAC24853C59D72 (responsable_id), INDEX IDX_14DAC24873A201E5 (createur_id), INDEX IDX_14DAC248ED5CA9E6 (service_id), INDEX IDX_14DAC2486AB213CC (lieu_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE goudurix_observateurs (goudurix_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_C42E68A68702975A (goudurix_id), INDEX IDX_C42E68A6A76ED395 (user_id), PRIMARY KEY(goudurix_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE goudurix ADD CONSTRAINT FK_14DAC24853C59D72 FOREIGN KEY (responsable_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE goudurix ADD CONSTRAINT FK_14DAC24873A201E5 FOREIGN KEY (createur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE goudurix ADD CONSTRAINT FK_14DAC248ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('ALTER TABLE goudurix ADD CONSTRAINT FK_14DAC2486AB213CC FOREIGN KEY (lieu_id) REFERENCES lieu (id)');
        $this->addSql('ALTER TABLE goudurix_observateurs ADD CONSTRAINT FK_C42E68A68702975A FOREIGN KEY (goudurix_id) REFERENCES goudurix (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE goudurix_observateurs ADD CONSTRAINT FK_C42E68A6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE goudurix DROP FOREIGN KEY FK_14DAC24853C59D72');
        $this->addSql('ALTER TABLE goudurix DROP FOREIGN KEY FK_14DAC24873A201E5');
        $this->addSql('ALTER TABLE goudurix DROP FOREIGN KEY FK_14DAC248ED5CA9E6');
        $this->addSql('ALTER TABLE goudurix DROP FOREIGN KEY FK_14DAC2486AB213CC');
        $this->addSql('ALTER TABLE goudurix_observateurs DROP FOREIGN KEY FK_C42E68A68702975A');
        $this->addSql('ALTER TABLE goudurix_observateurs DROP FOREIGN KEY FK_C42E68A6A76ED395');
        $this->addSql('DROP TABLE goudurix');
        $this->addSql('DROP TABLE goudurix_observateurs');
    }
}
