<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251028155353 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, destinataire_id INT NOT NULL, expediteur_id INT DEFAULT NULL, retour_action_id INT DEFAULT NULL, risque_id INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, message LONGTEXT NOT NULL, type VARCHAR(50) NOT NULL, lu TINYINT(1) NOT NULL, date_creation DATETIME NOT NULL, INDEX IDX_BF5476CAA4F84F6E (destinataire_id), INDEX IDX_BF5476CA10335F61 (expediteur_id), INDEX IDX_BF5476CA360731FA (retour_action_id), INDEX IDX_BF5476CA4ECC2413 (risque_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE retour_action (id INT AUTO_INCREMENT NOT NULL, risque_id INT NOT NULL, auteur_id INT NOT NULL, validateur_id INT DEFAULT NULL, description LONGTEXT NOT NULL, statut VARCHAR(50) NOT NULL, commentaire_validation LONGTEXT DEFAULT NULL, date_creation DATETIME NOT NULL, date_validation DATETIME DEFAULT NULL, INDEX IDX_7CE84EC14ECC2413 (risque_id), INDEX IDX_7CE84EC160BB6FE6 (auteur_id), INDEX IDX_7CE84EC1E57AEF2F (validateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAA4F84F6E FOREIGN KEY (destinataire_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA10335F61 FOREIGN KEY (expediteur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA360731FA FOREIGN KEY (retour_action_id) REFERENCES retour_action (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA4ECC2413 FOREIGN KEY (risque_id) REFERENCES goudurix (id)');
        $this->addSql('ALTER TABLE retour_action ADD CONSTRAINT FK_7CE84EC14ECC2413 FOREIGN KEY (risque_id) REFERENCES goudurix (id)');
        $this->addSql('ALTER TABLE retour_action ADD CONSTRAINT FK_7CE84EC160BB6FE6 FOREIGN KEY (auteur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE retour_action ADD CONSTRAINT FK_7CE84EC1E57AEF2F FOREIGN KEY (validateur_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CAA4F84F6E');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CA10335F61');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CA360731FA');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CA4ECC2413');
        $this->addSql('ALTER TABLE retour_action DROP FOREIGN KEY FK_7CE84EC14ECC2413');
        $this->addSql('ALTER TABLE retour_action DROP FOREIGN KEY FK_7CE84EC160BB6FE6');
        $this->addSql('ALTER TABLE retour_action DROP FOREIGN KEY FK_7CE84EC1E57AEF2F');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE retour_action');
    }
}
