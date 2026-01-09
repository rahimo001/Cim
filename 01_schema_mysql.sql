-- ============================================================================
-- GESTION DES DONNÉES PATIENTS - EPS BOUHANIFIA
-- Base de données MySQL complète avec 7 tables
-- ============================================================================

-- Créer la base de données
CREATE DATABASE IF NOT EXISTS gestion_patients_cim DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE gestion_patients_cim;

-- ============================================================================
-- TABLE 1: USERS (Authentification et rôles)
-- ============================================================================
CREATE TABLE IF NOT EXISTS users (
    id_user INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    role ENUM('admin', 'medecin', 'manipulateur', 'patient') DEFAULT 'patient',
    statut ENUM('actif', 'inactif', 'suspendu') DEFAULT 'actif',
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_role (role),
    INDEX idx_statut (statut)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABLE 2: PATIENTS
-- ============================================================================
CREATE TABLE IF NOT EXISTS patients (
    id_patient INT PRIMARY KEY AUTO_INCREMENT,
    id_user INT,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    date_naissance DATE,
    sexe ENUM('M', 'F') DEFAULT 'M',
    adresse VARCHAR(255),
    telephone VARCHAR(20),
    email VARCHAR(150),
    notes_medicales LONGTEXT,
    num_dossier VARCHAR(10) NOT NULL UNIQUE,
    date_inscription TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE SET NULL,
    INDEX idx_num_dossier (num_dossier),
    INDEX idx_nom (nom),
    INDEX idx_prenom (prenom),
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABLE 3: MEDECINS
-- ============================================================================
CREATE TABLE IF NOT EXISTS medecins (
    id_medecin INT PRIMARY KEY AUTO_INCREMENT,
    id_user INT NOT NULL UNIQUE,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    specialite VARCHAR(100),
    service VARCHAR(100),
    telephone VARCHAR(20),
    email VARCHAR(150),
    date_embauche DATE,
    date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE,
    INDEX idx_specialite (specialite),
    INDEX idx_service (service),
    INDEX idx_nom (nom)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABLE 4: RENDEZVOUS
-- ============================================================================
CREATE TABLE IF NOT EXISTS rendezvous (
    id_rdv INT PRIMARY KEY AUTO_INCREMENT,
    id_patient INT NOT NULL,
    id_medecin INT NOT NULL,
    date_rdv DATE NOT NULL,
    heure_rdv TIME NOT NULL,
    type_examen VARCHAR(100),
    statut ENUM('prévu', 'annulé', 'terminé', 'en_attente') DEFAULT 'prévu',
    notes LONGTEXT,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_patient) REFERENCES patients(id_patient) ON DELETE CASCADE,
    FOREIGN KEY (id_medecin) REFERENCES medecins(id_medecin) ON DELETE CASCADE,
    INDEX idx_date_rdv (date_rdv),
    INDEX idx_statut (statut),
    INDEX idx_patient (id_patient),
    INDEX idx_medecin (id_medecin),
    UNIQUE KEY unique_rdv (id_medecin, date_rdv, heure_rdv)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABLE 5: EXAMENS
-- ============================================================================
CREATE TABLE IF NOT EXISTS examens (
    id_examen INT PRIMARY KEY AUTO_INCREMENT,
    id_patient INT NOT NULL,
    id_medecin INT,
    id_rdv INT,
    type_examen VARCHAR(100) NOT NULL,
    date_examen DATE NOT NULL,
    compte_rendu LONGTEXT,
    statut ENUM('en_attente', 'en_cours', 'terminé', 'validé') DEFAULT 'en_attente',
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_patient) REFERENCES patients(id_patient) ON DELETE CASCADE,
    FOREIGN KEY (id_medecin) REFERENCES medecins(id_medecin) ON DELETE SET NULL,
    FOREIGN KEY (id_rdv) REFERENCES rendezvous(id_rdv) ON DELETE SET NULL,
    INDEX idx_date_examen (date_examen),
    INDEX idx_type_examen (type_examen),
    INDEX idx_statut (statut),
    INDEX idx_patient (id_patient)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABLE 6: IMAGES_EXAMEN (Gestion des fichiers d'examens)
-- ============================================================================
CREATE TABLE IF NOT EXISTS images_examen (
    id_image INT PRIMARY KEY AUTO_INCREMENT,
    id_examen INT NOT NULL,
    nom_fichier VARCHAR(255) NOT NULL,
    chemin_fichier VARCHAR(500) NOT NULL,
    type_fichier VARCHAR(50),
    taille_fichier INT,
    date_upload TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_examen) REFERENCES examens(id_examen) ON DELETE CASCADE,
    INDEX idx_examen (id_examen),
    INDEX idx_date (date_upload)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABLE 7: LOGS (Historique d'activités)
-- ============================================================================
CREATE TABLE IF NOT EXISTS logs (
    id_log INT PRIMARY KEY AUTO_INCREMENT,
    id_user INT,
    action VARCHAR(255) NOT NULL,
    table_name VARCHAR(100),
    record_id INT,
    description LONGTEXT,
    adresse_ip VARCHAR(45),
    date_action TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE SET NULL,
    INDEX idx_user (id_user),
    INDEX idx_action (action),
    INDEX idx_date (date_action)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- INSÉRER LES DONNÉES DE TEST
-- ============================================================================

-- Utilisateurs (mots de passe: sha256)
INSERT INTO users (nom, prenom, email, mot_de_passe, role, statut) VALUES
('Admin', 'System', 'admin@hopital.com', SHA2('admin123', 256), 'admin', 'actif'),
('Mahmoudi', 'Laouni', 'mahmoudi@hopital.com', SHA2('medecin123', 256), 'medecin', 'actif'),
('Ben', 'Ahmed', 'ahmed@hopital.com', SHA2('patient123', 256), 'patient', 'actif');

-- Patients
INSERT INTO patients (id_user, nom, prenom, date_naissance, sexe, adresse, telephone, email, num_dossier) VALUES
(3, 'Ben', 'Ahmed', '1990-05-15', 'M', '123 Rue de Bouhanifia', '0666123456', 'ahmed@hopital.com', 'P001'),
(NULL, 'Dupont', 'Marie', '1985-08-20', 'F', '456 Avenue Mascara', '0666234567', 'marie@example.com', 'P002');

-- Médecins
INSERT INTO medecins (id_user, nom, prenom, specialite, service, telephone, email, date_embauche) VALUES
(2, 'Mahmoudi', 'Laouni', 'Radiologie', 'Imagerie', '0666345678', 'mahmoudi@hopital.com', '2023-01-01');

-- Rendez-vous
INSERT INTO rendezvous (id_patient, id_medecin, date_rdv, heure_rdv, type_examen, statut) VALUES
(1, 1, '2025-12-31', '09:00:00', 'Radiographie', 'prévu'),
(2, 1, '2025-12-31', '10:30:00', 'Échographie', 'prévu');

-- Examens
INSERT INTO examens (id_patient, id_medecin, id_rdv, type_examen, date_examen, statut) VALUES
(1, 1, 1, 'Radiographie', '2025-12-31', 'en_attente'),
(2, 1, 2, 'Échographie', '2025-12-31', 'en_attente');

-- ============================================================================
-- FIN DU SCRIPT SQL
-- ============================================================================
