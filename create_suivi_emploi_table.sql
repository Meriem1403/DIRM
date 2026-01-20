-- Script SQL pour créer la table suivi_emploi
-- À exécuter si la migration Doctrine ne peut pas être exécutée

CREATE TABLE IF NOT EXISTS suivi_emploi (
    id INT AUTO_INCREMENT NOT NULL,
    nir VARCHAR(50) DEFAULT NULL,
    matricule_sirh VARCHAR(50) DEFAULT NULL,
    nom_usage VARCHAR(255) DEFAULT NULL,
    nom_naissance VARCHAR(255) DEFAULT NULL,
    prenom VARCHAR(255) DEFAULT NULL,
    niveau06_libelle_court VARCHAR(255) DEFAULT NULL,
    niveau08_libelle_court VARCHAR(255) DEFAULT NULL,
    poste_code VARCHAR(50) DEFAULT NULL,
    poste_libelle_long LONGTEXT DEFAULT NULL,
    etpt_rh NUMERIC(10, 2) DEFAULT NULL,
    etpt_prog_action NUMERIC(10, 2) DEFAULT NULL,
    code_nne_date_obs BIGINT DEFAULT NULL,
    nne_libelle_date_obs VARCHAR(255) DEFAULT NULL,
    PRIMARY KEY(id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;


