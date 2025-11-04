<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Migration pour créer la table nom_risque
 */
final class Version20250131120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Création de la table nom_risque pour gérer les noms/types de risques';
    }

    public function up(Schema $schema): void
    {
        // Créer la table nom_risque
        $this->addSql('CREATE TABLE nom_risque (
            id INT AUTO_INCREMENT NOT NULL,
            nom VARCHAR(255) NOT NULL,
            description LONGTEXT DEFAULT NULL,
            actif TINYINT(1) DEFAULT 1 NOT NULL,
            UNIQUE INDEX UNIQ_nom_risque_nom (nom),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // Supprimer la table nom_risque
        $this->addSql('DROP TABLE nom_risque');
    }
}

