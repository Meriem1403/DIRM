<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Migration pour ajouter les colonnes additionnelles du CSV dans goudurix
 */
final class Version20250131120002 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ajoute les colonnes additionnelles du CSV dans la table goudurix';
    }

    public function up(Schema $schema): void
    {
        // Ajouter les colonnes additionnelles du CSV
        $this->addSql('ALTER TABLE goudurix 
            ADD idaction VARCHAR(50) DEFAULT NULL,
            ADD id_situ_d VARCHAR(50) DEFAULT NULL,
            ADD id_dommage VARCHAR(50) DEFAULT NULL,
            ADD id_mesure VARCHAR(50) DEFAULT NULL,
            ADD unite VARCHAR(100) DEFAULT NULL,
            ADD numero VARCHAR(50) DEFAULT NULL,
            ADD dommage LONGTEXT DEFAULT NULL,
            ADD etat VARCHAR(50) DEFAULT NULL,
            ADD periodicite VARCHAR(100) DEFAULT NULL,
            ADD prochain_controle DATE DEFAULT NULL,
            ADD n_pdf VARCHAR(100) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // Supprimer les colonnes
        $this->addSql('ALTER TABLE goudurix 
            DROP idaction,
            DROP id_situ_d,
            DROP id_dommage,
            DROP id_mesure,
            DROP unite,
            DROP numero,
            DROP dommage,
            DROP etat,
            DROP periodicite,
            DROP prochain_controle,
            DROP n_pdf');
    }
}

