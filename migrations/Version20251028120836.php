<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251028120836 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE mesure (id INT AUTO_INCREMENT NOT NULL, risque_id INT NOT NULL, description LONGTEXT NOT NULL, en_cours TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL, INDEX IDX_5F1B6E704ECC2413 (risque_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE retour_action (id INT AUTO_INCREMENT NOT NULL, auteur_id INT NOT NULL, mesure_id INT NOT NULL, description LONGTEXT NOT NULL, date_retour DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE84EC160BB6FE6 (auteur_id), INDEX IDX_7CE84EC143AB22FA (mesure_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mesure ADD CONSTRAINT FK_5F1B6E704ECC2413 FOREIGN KEY (risque_id) REFERENCES goudurix (id)');
        $this->addSql('ALTER TABLE retour_action ADD CONSTRAINT FK_7CE84EC160BB6FE6 FOREIGN KEY (auteur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE retour_action ADD CONSTRAINT FK_7CE84EC143AB22FA FOREIGN KEY (mesure_id) REFERENCES mesure (id)');
        $this->addSql('ALTER TABLE goudurix_observateurs DROP FOREIGN KEY FK_C42E68A6A76ED395');
        $this->addSql('ALTER TABLE goudurix_observateurs DROP FOREIGN KEY FK_C42E68A68702975A');
        $this->addSql('DROP TABLE goudurix_observateurs');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE goudurix_observateurs (goudurix_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_C42E68A68702975A (goudurix_id), INDEX IDX_C42E68A6A76ED395 (user_id), PRIMARY KEY(goudurix_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE goudurix_observateurs ADD CONSTRAINT FK_C42E68A6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE goudurix_observateurs ADD CONSTRAINT FK_C42E68A68702975A FOREIGN KEY (goudurix_id) REFERENCES goudurix (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mesure DROP FOREIGN KEY FK_5F1B6E704ECC2413');
        $this->addSql('ALTER TABLE retour_action DROP FOREIGN KEY FK_7CE84EC160BB6FE6');
        $this->addSql('ALTER TABLE retour_action DROP FOREIGN KEY FK_7CE84EC143AB22FA');
        $this->addSql('DROP TABLE mesure');
        $this->addSql('DROP TABLE retour_action');
    }
}
