-- Migration Version20250708112507
CREATE TABLE application_cerbere (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(50) NOT NULL, nom VARCHAR(100) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
CREATE TABLE declaration_chantier (id INT AUTO_INCREMENT NOT NULL, valide_par_id INT DEFAULT NULL, nom VARCHAR(100) NOT NULL, prenom VARCHAR(100) NOT NULL, adresse VARCHAR(255) NOT NULL, email VARCHAR(100) NOT NULL, telephone VARCHAR(20) NOT NULL, type_demande VARCHAR(50) NOT NULL, activites JSON NOT NULL, nom_navire VARCHAR(150) NOT NULL, pavillon_origine VARCHAR(150) DEFAULT NULL, quartier_immatriculation VARCHAR(150) NOT NULL, jauge DOUBLE PRECISION NOT NULL, longueur DOUBLE PRECISION NOT NULL, largeur DOUBLE PRECISION NOT NULL, propulsion VARCHAR(50) NOT NULL, puissance_kw DOUBLE PRECISION NOT NULL, vitesse VARCHAR(50) NOT NULL, materiau VARCHAR(100) NOT NULL, date_pose_quille DATE NOT NULL, architecte_naval VARCHAR(255) DEFAULT NULL, organisme_classe VARCHAR(255) DEFAULT NULL, categorie_conception VARCHAR(50) DEFAULT NULL, numero_serie VARCHAR(100) DEFAULT NULL, numero_coque VARCHAR(100) DEFAULT NULL, modules_evaluation VARCHAR(255) DEFAULT NULL, organisme_notifie VARCHAR(100) DEFAULT NULL, autre_prenom VARCHAR(100) DEFAULT NULL, autre_qualite VARCHAR(50) DEFAULT NULL, autre_telephone VARCHAR(100) DEFAULT NULL, statut VARCHAR(50) NOT NULL, date_soumission DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', date_validation DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', recepisse_path VARCHAR(255) DEFAULT NULL, INDEX IDX_90B9C4D86AF12ED9 (valide_par_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
CREATE TABLE demande_habilitation_cerbere (id INT AUTO_INCREMENT NOT NULL, agent_id INT NOT NULL, demandeur_id INT NOT NULL, valide_par_id INT DEFAULT NULL, regle_portee VARCHAR(100) DEFAULT NULL, restrictions LONGTEXT DEFAULT NULL, date_soumission DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', statut VARCHAR(50) NOT NULL, date_validation DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_28BB67A33414710B (agent_id), INDEX IDX_28BB67A395A6EE59 (demandeur_id), INDEX IDX_28BB67A36AF12ED9 (valide_par_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
CREATE TABLE demande_habilitation_cerbere_application_cerbere (demande_habilitation_cerbere_id INT NOT NULL, application_cerbere_id INT NOT NULL, INDEX IDX_83C5705ACB70B145 (demande_habilitation_cerbere_id), INDEX IDX_83C5705A4A2DF76 (application_cerbere_id), PRIMARY KEY(demande_habilitation_cerbere_id, application_cerbere_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
CREATE TABLE demande_habilitation_cerbere_profil_cerbere (demande_habilitation_cerbere_id INT NOT NULL, profil_cerbere_id INT NOT NULL, INDEX IDX_3A5ED556CB70B145 (demande_habilitation_cerbere_id), INDEX IDX_3A5ED55648E7B564 (profil_cerbere_id), PRIMARY KEY(demande_habilitation_cerbere_id, profil_cerbere_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
CREATE TABLE demande_mobilite (id INT AUTO_INCREMENT NOT NULL, created_by_id INT NOT NULL, prenom VARCHAR(100) NOT NULL, nom VARCHAR(100) NOT NULL, statut_agent VARCHAR(100) NOT NULL, statut VARCHAR(50) NOT NULL, corps VARCHAR(100) NOT NULL, grade VARCHAR(100) NOT NULL, type_demande VARCHAR(50) NOT NULL, ministere_origine VARCHAR(150) DEFAULT NULL, direction_origine VARCHAR(150) DEFAULT NULL, service_origine VARCHAR(150) DEFAULT NULL, service_actuel VARCHAR(150) DEFAULT NULL, date_depart DATE DEFAULT NULL, motif_depart VARCHAR(255) DEFAULT NULL, nature_mutation VARCHAR(100) DEFAULT NULL, date_prise_poste DATE DEFAULT NULL, service_affectation VARCHAR(150) DEFAULT NULL, site_geographique VARCHAR(150) DEFAULT NULL, bureau VARCHAR(50) DEFAULT NULL, fonction VARCHAR(150) DEFAULT NULL, poste_remplacement TINYINT(1) DEFAULT NULL, poste_creation TINYINT(1) DEFAULT NULL, prenom_remplace VARCHAR(100) DEFAULT NULL, nom_remplace VARCHAR(100) DEFAULT NULL, besoin_mobilier TINYINT(1) DEFAULT NULL, besoin_fournitures TINYINT(1) DEFAULT NULL, besoin_informatique TINYINT(1) DEFAULT NULL, carte_ants VARCHAR(3) DEFAULT NULL, carte_achats VARCHAR(3) DEFAULT NULL, charge_voyages VARCHAR(3) DEFAULT NULL, correspondant_budgetaire VARCHAR(3) DEFAULT NULL, encadre_agents VARCHAR(3) DEFAULT NULL, utilise_chorus VARCHAR(3) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', commentaire LONGTEXT DEFAULT NULL, INDEX IDX_84083591B03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
CREATE TABLE domaine_service (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
CREATE TABLE domaine_service_service (domaine_service_id INT NOT NULL, service_id INT NOT NULL, INDEX IDX_19181657762EB0F8 (domaine_service_id), INDEX IDX_19181657ED5CA9E6 (service_id), PRIMARY KEY(domaine_service_id, service_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
CREATE TABLE lieu (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
CREATE TABLE lieu_service (lieu_id INT NOT NULL, service_id INT NOT NULL, INDEX IDX_19A2336AB213CC (lieu_id), INDEX IDX_19A233ED5CA9E6 (service_id), PRIMARY KEY(lieu_id, service_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
CREATE TABLE profil_cerbere (id INT AUTO_INCREMENT NOT NULL, application_id INT NOT NULL, nom VARCHAR(100) NOT NULL, INDEX IDX_86EFD3F13E030ACD (application_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(50) NOT NULL, label VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
CREATE TABLE service (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, role_id INT NOT NULL, service_id INT NOT NULL, created_by_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(100) NOT NULL, prenom VARCHAR(100) NOT NULL, date_naissance DATE NOT NULL, adresse VARCHAR(255) NOT NULL, ville VARCHAR(100) NOT NULL, code_postal VARCHAR(10) NOT NULL, pays VARCHAR(100) NOT NULL, poste VARCHAR(150) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D649D60322AC (role_id), INDEX IDX_8D93D649ED5CA9E6 (service_id), INDEX IDX_8D93D649B03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;

-- Migration Version20250708173256
CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
ALTER TABLE user ADD domaine_id INT DEFAULT NULL, ADD lieu_id INT DEFAULT NULL, ADD categorie_id INT DEFAULT NULL;

-- Migration Version20250709112031
ALTER TABLE profil_cerbere ADD description LONGTEXT DEFAULT NULL;

-- Migration Version20250709142534
ALTER TABLE declaration_chantier ADD chantier_avec_contrat TINYINT(1) DEFAULT NULL, ADD adresse_chantier VARCHAR(255) DEFAULT NULL, ADD contact_chantier VARCHAR(255) DEFAULT NULL, ADD contact_architecte VARCHAR(255) DEFAULT NULL, ADD contact_organisme_classe VARCHAR(255) DEFAULT NULL, ADD numero_examen_ce VARCHAR(100) DEFAULT NULL, ADD nom_mandataire VARCHAR(100) DEFAULT NULL, ADD prenom_mandataire VARCHAR(100) DEFAULT NULL, ADD qualite_mandataire VARCHAR(50) DEFAULT NULL, ADD telephone_mandataire VARCHAR(100) DEFAULT NULL, ADD port_depart VARCHAR(100) DEFAULT NULL, ADD eloignement_cote VARCHAR(50) DEFAULT NULL, ADD duree_sejour_mer VARCHAR(50) DEFAULT NULL;

-- Migration Version20250709150748
ALTER TABLE declaration_chantier ADD note_explicative_path VARCHAR(255) DEFAULT NULL;

-- Migration Version20250709153511
CREATE TABLE personne_abord (id INT AUTO_INCREMENT NOT NULL, declaration_chantier_id INT NOT NULL, fonction VARCHAR(100) NOT NULL, nombre INT NOT NULL, observations LONGTEXT DEFAULT NULL, INDEX IDX_F3E3B529F8F0B84A (declaration_chantier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;

-- Migration Version20250710085642
ALTER TABLE declaration_chantier DROP nb_equipage, DROP nb_passagers, DROP nb_personnel_special;
ALTER TABLE personne_abord ADD equipage INT DEFAULT NULL, ADD passagers INT DEFAULT NULL, ADD personnes INT DEFAULT NULL, DROP fonction, DROP nombre, DROP observations;

-- Migration Version20250710095921
ALTER TABLE declaration_chantier ADD architecte_mail VARCHAR(255) DEFAULT NULL, ADD nb_equipage INT DEFAULT NULL, ADD nb_passagers INT DEFAULT NULL, ADD nb_personnel_special INT DEFAULT NULL, CHANGE eloignement_cote eloignement_cote VARCHAR(100) NOT NULL, CHANGE duree_sejour_mer duree_sejour_mer VARCHAR(100) NOT NULL, CHANGE autre_nom nom_chantier VARCHAR(100) DEFAULT NULL;

-- Migration Version20250710125517
ALTER TABLE declaration_chantier ADD email_organisme VARCHAR(100) DEFAULT NULL, ADD nom_chantier_conception VARCHAR(100) DEFAULT NULL, ADD email_mandataire VARCHAR(100) DEFAULT NULL, ADD nom_mandataire_ia VARCHAR(100) DEFAULT NULL, ADD prenom_mandataire_ia VARCHAR(100) DEFAULT NULL, ADD qualite_mandataire_ia VARCHAR(50) DEFAULT NULL, ADD telephone_mandataire_ia VARCHAR(100) DEFAULT NULL, ADD email_mandataire_ia VARCHAR(100) DEFAULT NULL;

-- Migration Version20250710131241
ALTER TABLE declaration_chantier DROP qualite_declarant, DROP type_projet, DROP chantier_naval;

-- Foreign Keys
ALTER TABLE declaration_chantier ADD CONSTRAINT FK_90B9C4D86AF12ED9 FOREIGN KEY (valide_par_id) REFERENCES user (id);
ALTER TABLE demande_habilitation_cerbere ADD CONSTRAINT FK_28BB67A33414710B FOREIGN KEY (agent_id) REFERENCES user (id);
ALTER TABLE demande_habilitation_cerbere ADD CONSTRAINT FK_28BB67A395A6EE59 FOREIGN KEY (demandeur_id) REFERENCES user (id);
ALTER TABLE demande_habilitation_cerbere ADD CONSTRAINT FK_28BB67A36AF12ED9 FOREIGN KEY (valide_par_id) REFERENCES user (id);
ALTER TABLE demande_habilitation_cerbere_application_cerbere ADD CONSTRAINT FK_83C5705ACB70B145 FOREIGN KEY (demande_habilitation_cerbere_id) REFERENCES demande_habilitation_cerbere (id) ON DELETE CASCADE;
ALTER TABLE demande_habilitation_cerbere_application_cerbere ADD CONSTRAINT FK_83C5705A4A2DF76 FOREIGN KEY (application_cerbere_id) REFERENCES application_cerbere (id) ON DELETE CASCADE;
ALTER TABLE demande_habilitation_cerbere_profil_cerbere ADD CONSTRAINT FK_3A5ED556CB70B145 FOREIGN KEY (demande_habilitation_cerbere_id) REFERENCES demande_habilitation_cerbere (id) ON DELETE CASCADE;
ALTER TABLE demande_habilitation_cerbere_profil_cerbere ADD CONSTRAINT FK_3A5ED55648E7B564 FOREIGN KEY (profil_cerbere_id) REFERENCES profil_cerbere (id) ON DELETE CASCADE;
ALTER TABLE demande_mobilite ADD CONSTRAINT FK_84083591B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id);
ALTER TABLE domaine_service_service ADD CONSTRAINT FK_19181657762EB0F8 FOREIGN KEY (domaine_service_id) REFERENCES domaine_service (id) ON DELETE CASCADE;
ALTER TABLE domaine_service_service ADD CONSTRAINT FK_19181657ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) ON DELETE CASCADE;
ALTER TABLE lieu_service ADD CONSTRAINT FK_19A2336AB213CC FOREIGN KEY (lieu_id) REFERENCES lieu (id) ON DELETE CASCADE;
ALTER TABLE lieu_service ADD CONSTRAINT FK_19A233ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) ON DELETE CASCADE;
ALTER TABLE profil_cerbere ADD CONSTRAINT FK_86EFD3F13E030ACD FOREIGN KEY (application_id) REFERENCES application_cerbere (id);
ALTER TABLE user ADD CONSTRAINT FK_8D93D649D60322AC FOREIGN KEY (role_id) REFERENCES role (id);
ALTER TABLE user ADD CONSTRAINT FK_8D93D649ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id);
ALTER TABLE user ADD CONSTRAINT FK_8D93D649B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id);
ALTER TABLE user ADD CONSTRAINT FK_8D93D6494272FC9F FOREIGN KEY (domaine_id) REFERENCES domaine_service (id);
ALTER TABLE user ADD CONSTRAINT FK_8D93D6496AB213CC FOREIGN KEY (lieu_id) REFERENCES lieu (id);
ALTER TABLE user ADD CONSTRAINT FK_8D93D649BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id);
ALTER TABLE personne_abord ADD CONSTRAINT FK_F3E3B529F8F0B84A FOREIGN KEY (declaration_chantier_id) REFERENCES declaration_chantier (id);

-- Indexes
CREATE INDEX IDX_8D93D6494272FC9F ON user (domaine_id);
CREATE INDEX IDX_8D93D6496AB213CC ON user (lieu_id);
CREATE INDEX IDX_8D93D649BCF5E72D ON user (categorie_id);

-- Doctrine Migrations Table
CREATE TABLE doctrine_migration_versions (version VARCHAR(191) NOT NULL, executed_at DATETIME DEFAULT NULL, execution_time INT DEFAULT NULL, PRIMARY KEY(version)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
INSERT INTO doctrine_migration_versions (version, executed_at, execution_time) VALUES 
('DoctrineMigrations\\Version20250708112507', NOW(), 0),
('DoctrineMigrations\\Version20250708173256', NOW(), 0),
('DoctrineMigrations\\Version20250709100735', NOW(), 0),
('DoctrineMigrations\\Version20250709112031', NOW(), 0),
('DoctrineMigrations\\Version20250709131656', NOW(), 0),
('DoctrineMigrations\\Version20250709142534', NOW(), 0),
('DoctrineMigrations\\Version20250709150748', NOW(), 0),
('DoctrineMigrations\\Version20250709153511', NOW(), 0),
('DoctrineMigrations\\Version20250710085642', NOW(), 0),
('DoctrineMigrations\\Version20250710095921', NOW(), 0),
('DoctrineMigrations\\Version20250710125517', NOW(), 0),
('DoctrineMigrations\\Version20250710131241', NOW(), 0);
