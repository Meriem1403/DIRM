<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250128120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add notifications relation to User entity';
    }

    public function up(Schema $schema): void
    {
        // Cette migration est automatiquement gérée par Doctrine
        // car nous avons ajouté la relation OneToMany dans l'entité User
    }

    public function down(Schema $schema): void
    {
        // Cette migration est automatiquement gérée par Doctrine
    }
}
