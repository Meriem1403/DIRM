<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250708112507 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE application_cerbere (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(50) NOT NULL, nom VARCHAR(100) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE declaration_chantier (id INT AUTO_INCREMENT NOT NULL, valide_par_id INT DEFAULT NULL, nom VARCHAR(100) NOT NULL, prenom VARCHAR(100) NOT NULL, adresse VARCHAR(255) NOT NULL, email VARCHAR(100) NOT NULL, telephone VARCHAR(20) NOT NULL, qualite_declarant VARCHAR(50) NOT NULL, type_demande VARCHAR(50) NOT NULL, type_projet VARCHAR(50) NOT NULL, activites JSON NOT NULL, nom_navire VARCHAR(150) NOT NULL, pavillon_origine VARCHAR(150) DEFAULT NULL, quartier_immatriculation VARCHAR(150) NOT NULL, jauge DOUBLE PRECISION NOT NULL, longueur DOUBLE PRECISION NOT NULL, largeur DOUBLE PRECISION NOT NULL, propulsion VARCHAR(50) NOT NULL, puissance_kw DOUBLE PRECISION NOT NULL, vitesse VARCHAR(50) NOT NULL, materiau VARCHAR(100) NOT NULL, date_pose_quille DATE NOT NULL, chantier_naval VARCHAR(255) NOT NULL, architecte_naval VARCHAR(255) DEFAULT NULL, organisme_classe VARCHAR(255) DEFAULT NULL, categorie_conception VARCHAR(50) DEFAULT NULL, numero_serie VARCHAR(100) DEFAULT NULL, numero_coque VARCHAR(100) DEFAULT NULL, modules_evaluation VARCHAR(255) DEFAULT NULL, organisme_notifie VARCHAR(100) DEFAULT NULL, autre_nom VARCHAR(100) DEFAULT NULL, autre_prenom VARCHAR(100) DEFAULT NULL, autre_qualite VARCHAR(50) DEFAULT NULL, autre_telephone VARCHAR(100) DEFAULT NULL, statut VARCHAR(50) NOT NULL, date_soumission DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', date_validation DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', recepisse_path VARCHAR(255) DEFAULT NULL, INDEX IDX_90B9C4D86AF12ED9 (valide_par_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demande_habilitation_cerbere (id INT AUTO_INCREMENT NOT NULL, agent_id INT NOT NULL, demandeur_id INT NOT NULL, valide_par_id INT DEFAULT NULL, no VARCHAR(255) NOT NULL, regle_portee VARCHAR(100) DEFAULT NULL, restrictions LONGTEXT DEFAULT NULL, date_soumission DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', statut VARCHAR(50) NOT NULL, date_validation DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_28BB67A33414710B (agent_id), INDEX IDX_28BB67A395A6EE59 (demandeur_id), INDEX IDX_28BB67A36AF12ED9 (valide_par_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demande_habilitation_cerbere_application_cerbere (demande_habilitation_cerbere_id INT NOT NULL, application_cerbere_id INT NOT NULL, INDEX IDX_83C5705ACB70B145 (demande_habilitation_cerbere_id), INDEX IDX_83C5705A4A2DF76 (application_cerbere_id), PRIMARY KEY(demande_habilitation_cerbere_id, application_cerbere_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demande_habilitation_cerbere_profil_cerbere (demande_habilitation_cerbere_id INT NOT NULL, profil_cerbere_id INT NOT NULL, INDEX IDX_3A5ED556CB70B145 (demande_habilitation_cerbere_id), INDEX IDX_3A5ED55648E7B564 (profil_cerbere_id), PRIMARY KEY(demande_habilitation_cerbere_id, profil_cerbere_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demande_mobilite (id INT AUTO_INCREMENT NOT NULL, created_by_id INT NOT NULL, prenom VARCHAR(100) NOT NULL, nom VARCHAR(100) NOT NULL, statut_agent VARCHAR(100) NOT NULL, statut VARCHAR(50) NOT NULL, corps VARCHAR(100) NOT NULL, grade VARCHAR(100) NOT NULL, type_demande VARCHAR(50) NOT NULL, ministere_origine VARCHAR(150) DEFAULT NULL, direction_origine VARCHAR(150) DEFAULT NULL, service_origine VARCHAR(150) DEFAULT NULL, service_actuel VARCHAR(150) DEFAULT NULL, date_depart DATE DEFAULT NULL, motif_depart VARCHAR(255) DEFAULT NULL, nature_mutation VARCHAR(100) DEFAULT NULL, date_prise_poste DATE DEFAULT NULL, service_affectation VARCHAR(150) DEFAULT NULL, site_geographique VARCHAR(150) DEFAULT NULL, bureau VARCHAR(50) DEFAULT NULL, fonction VARCHAR(150) DEFAULT NULL, poste_remplacement TINYINT(1) DEFAULT NULL, poste_creation TINYINT(1) DEFAULT NULL, prenom_remplace VARCHAR(100) DEFAULT NULL, nom_remplace VARCHAR(100) DEFAULT NULL, besoin_mobilier TINYINT(1) DEFAULT NULL, besoin_fournitures TINYINT(1) DEFAULT NULL, besoin_informatique TINYINT(1) DEFAULT NULL, carte_ants VARCHAR(3) DEFAULT NULL, carte_achats VARCHAR(3) DEFAULT NULL, charge_voyages VARCHAR(3) DEFAULT NULL, correspondant_budgetaire VARCHAR(3) DEFAULT NULL, encadre_agents VARCHAR(3) DEFAULT NULL, utilise_chorus VARCHAR(3) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', commentaire LONGTEXT DEFAULT NULL, INDEX IDX_84083591B03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE domaine_service (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE domaine_service_service (domaine_service_id INT NOT NULL, service_id INT NOT NULL, INDEX IDX_19181657762EB0F8 (domaine_service_id), INDEX IDX_19181657ED5CA9E6 (service_id), PRIMARY KEY(domaine_service_id, service_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lieu (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lieu_service (lieu_id INT NOT NULL, service_id INT NOT NULL, INDEX IDX_19A2336AB213CC (lieu_id), INDEX IDX_19A233ED5CA9E6 (service_id), PRIMARY KEY(lieu_id, service_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profil_cerbere (id INT AUTO_INCREMENT NOT NULL, application_id INT NOT NULL, nom VARCHAR(100) NOT NULL, INDEX IDX_86EFD3F13E030ACD (application_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(50) NOT NULL, label VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, role_id INT NOT NULL, service_id INT NOT NULL, created_by_id INT NOT NULL, email VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(100) NOT NULL, prenom VARCHAR(100) NOT NULL, date_naissance DATE NOT NULL, adresse VARCHAR(255) NOT NULL, ville VARCHAR(100) NOT NULL, code_postal VARCHAR(10) NOT NULL, pays VARCHAR(100) NOT NULL, poste VARCHAR(150) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D649D60322AC (role_id), INDEX IDX_8D93D649ED5CA9E6 (service_id), INDEX IDX_8D93D649B03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE declaration_chantier ADD CONSTRAINT FK_90B9C4D86AF12ED9 FOREIGN KEY (valide_par_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE demande_habilitation_cerbere ADD CONSTRAINT FK_28BB67A33414710B FOREIGN KEY (agent_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE demande_habilitation_cerbere ADD CONSTRAINT FK_28BB67A395A6EE59 FOREIGN KEY (demandeur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE demande_habilitation_cerbere ADD CONSTRAINT FK_28BB67A36AF12ED9 FOREIGN KEY (valide_par_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE demande_habilitation_cerbere_application_cerbere ADD CONSTRAINT FK_83C5705ACB70B145 FOREIGN KEY (demande_habilitation_cerbere_id) REFERENCES demande_habilitation_cerbere (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE demande_habilitation_cerbere_application_cerbere ADD CONSTRAINT FK_83C5705A4A2DF76 FOREIGN KEY (application_cerbere_id) REFERENCES application_cerbere (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE demande_habilitation_cerbere_profil_cerbere ADD CONSTRAINT FK_3A5ED556CB70B145 FOREIGN KEY (demande_habilitation_cerbere_id) REFERENCES demande_habilitation_cerbere (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE demande_habilitation_cerbere_profil_cerbere ADD CONSTRAINT FK_3A5ED55648E7B564 FOREIGN KEY (profil_cerbere_id) REFERENCES profil_cerbere (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE demande_mobilite ADD CONSTRAINT FK_84083591B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE domaine_service_service ADD CONSTRAINT FK_19181657762EB0F8 FOREIGN KEY (domaine_service_id) REFERENCES domaine_service (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE domaine_service_service ADD CONSTRAINT FK_19181657ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lieu_service ADD CONSTRAINT FK_19A2336AB213CC FOREIGN KEY (lieu_id) REFERENCES lieu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lieu_service ADD CONSTRAINT FK_19A233ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE profil_cerbere ADD CONSTRAINT FK_86EFD3F13E030ACD FOREIGN KEY (application_id) REFERENCES application_cerbere (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649D60322AC FOREIGN KEY (role_id) REFERENCES role (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE declaration_chantier DROP FOREIGN KEY FK_90B9C4D86AF12ED9');
        $this->addSql('ALTER TABLE demande_habilitation_cerbere DROP FOREIGN KEY FK_28BB67A33414710B');
        $this->addSql('ALTER TABLE demande_habilitation_cerbere DROP FOREIGN KEY FK_28BB67A395A6EE59');
        $this->addSql('ALTER TABLE demande_habilitation_cerbere DROP FOREIGN KEY FK_28BB67A36AF12ED9');
        $this->addSql('ALTER TABLE demande_habilitation_cerbere_application_cerbere DROP FOREIGN KEY FK_83C5705ACB70B145');
        $this->addSql('ALTER TABLE demande_habilitation_cerbere_application_cerbere DROP FOREIGN KEY FK_83C5705A4A2DF76');
        $this->addSql('ALTER TABLE demande_habilitation_cerbere_profil_cerbere DROP FOREIGN KEY FK_3A5ED556CB70B145');
        $this->addSql('ALTER TABLE demande_habilitation_cerbere_profil_cerbere DROP FOREIGN KEY FK_3A5ED55648E7B564');
        $this->addSql('ALTER TABLE demande_mobilite DROP FOREIGN KEY FK_84083591B03A8386');
        $this->addSql('ALTER TABLE domaine_service_service DROP FOREIGN KEY FK_19181657762EB0F8');
        $this->addSql('ALTER TABLE domaine_service_service DROP FOREIGN KEY FK_19181657ED5CA9E6');
        $this->addSql('ALTER TABLE lieu_service DROP FOREIGN KEY FK_19A2336AB213CC');
        $this->addSql('ALTER TABLE lieu_service DROP FOREIGN KEY FK_19A233ED5CA9E6');
        $this->addSql('ALTER TABLE profil_cerbere DROP FOREIGN KEY FK_86EFD3F13E030ACD');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649D60322AC');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649ED5CA9E6');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649B03A8386');
        $this->addSql('DROP TABLE application_cerbere');
        $this->addSql('DROP TABLE declaration_chantier');
        $this->addSql('DROP TABLE demande_habilitation_cerbere');
        $this->addSql('DROP TABLE demande_habilitation_cerbere_application_cerbere');
        $this->addSql('DROP TABLE demande_habilitation_cerbere_profil_cerbere');
        $this->addSql('DROP TABLE demande_mobilite');
        $this->addSql('DROP TABLE domaine_service');
        $this->addSql('DROP TABLE domaine_service_service');
        $this->addSql('DROP TABLE lieu');
        $this->addSql('DROP TABLE lieu_service');
        $this->addSql('DROP TABLE profil_cerbere');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE service');
        $this->addSql('DROP TABLE user');
    }
}
