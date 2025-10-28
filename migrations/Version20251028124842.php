<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251028124842 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mesure DROP FOREIGN KEY FK_5F1B6E704ECC2413');
        $this->addSql('ALTER TABLE retour_action DROP FOREIGN KEY FK_7CE84EC160BB6FE6');
        $this->addSql('ALTER TABLE retour_action DROP FOREIGN KEY FK_7CE84EC143AB22FA');
        $this->addSql('DROP TABLE mesure');
        $this->addSql('DROP TABLE retour_action');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE mesure (id INT AUTO_INCREMENT NOT NULL, risque_id INT NOT NULL, description LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, en_cours TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL, INDEX IDX_5F1B6E704ECC2413 (risque_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE retour_action (id INT AUTO_INCREMENT NOT NULL, auteur_id INT NOT NULL, mesure_id INT NOT NULL, description LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, date_retour DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE84EC160BB6FE6 (auteur_id), INDEX IDX_7CE84EC143AB22FA (mesure_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE mesure ADD CONSTRAINT FK_5F1B6E704ECC2413 FOREIGN KEY (risque_id) REFERENCES goudurix (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE retour_action ADD CONSTRAINT FK_7CE84EC160BB6FE6 FOREIGN KEY (auteur_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE retour_action ADD CONSTRAINT FK_7CE84EC143AB22FA FOREIGN KEY (mesure_id) REFERENCES mesure (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
