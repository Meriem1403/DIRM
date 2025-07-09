<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250708173256 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user ADD domaine_id INT DEFAULT NULL, ADD lieu_id INT DEFAULT NULL, ADD categorie_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6494272FC9F FOREIGN KEY (domaine_id) REFERENCES domaine_service (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6496AB213CC FOREIGN KEY (lieu_id) REFERENCES lieu (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('CREATE INDEX IDX_8D93D6494272FC9F ON user (domaine_id)');
        $this->addSql('CREATE INDEX IDX_8D93D6496AB213CC ON user (lieu_id)');
        $this->addSql('CREATE INDEX IDX_8D93D649BCF5E72D ON user (categorie_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649BCF5E72D');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6494272FC9F');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6496AB213CC');
        $this->addSql('DROP INDEX IDX_8D93D6494272FC9F ON user');
        $this->addSql('DROP INDEX IDX_8D93D6496AB213CC ON user');
        $this->addSql('DROP INDEX IDX_8D93D649BCF5E72D ON user');
        $this->addSql('ALTER TABLE user DROP domaine_id, DROP lieu_id, DROP categorie_id');
    }
}
