<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251028133816 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE goudurix_observateurs (goudurix_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_C42E68A68702975A (goudurix_id), INDEX IDX_C42E68A6A76ED395 (user_id), PRIMARY KEY(goudurix_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE goudurix_observateurs ADD CONSTRAINT FK_C42E68A68702975A FOREIGN KEY (goudurix_id) REFERENCES goudurix (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE goudurix_observateurs ADD CONSTRAINT FK_C42E68A6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE goudurix_observateurs DROP FOREIGN KEY FK_C42E68A68702975A');
        $this->addSql('ALTER TABLE goudurix_observateurs DROP FOREIGN KEY FK_C42E68A6A76ED395');
        $this->addSql('DROP TABLE goudurix_observateurs');
    }
}
