-- Création de la base de données, si elle n'existe pas déjà
CREATE DATABASE IF NOT EXISTS arcadia_db;
USE arcadia_db;

-- Table des utilisateurs avec des rôles sous forme de VARCHAR
CREATE TABLE IF NOT EXISTS utilisateurs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(255) NOT NULL,
  nom VARCHAR(255) NOT NULL,
  prenom VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  role VARCHAR(255) NOT NULL
);

-- Table des habitats du zoo
CREATE TABLE IF NOT EXISTS habitats (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(255) NOT NULL,
  descript TEXT NOT NULL
);

-- Table pour stocker les images des habitats
CREATE TABLE IF NOT EXISTS img_habitats (
  id INT AUTO_INCREMENT PRIMARY KEY,
  habitat_id INT NOT NULL,
  image BLOB NOT NULL,
  FOREIGN KEY (habitat_id) REFERENCES habitats(id) ON DELETE CASCADE
);

-- Table des races
CREATE TABLE IF NOT EXISTS races (
  id INT AUTO_INCREMENT PRIMARY KEY,
  label VARCHAR(255) NOT NULL
);

-- Table des animaux du zoo
CREATE TABLE IF NOT EXISTS animaux (
  id INT AUTO_INCREMENT PRIMARY KEY,
  prenom VARCHAR(255) NOT NULL,
  race_id INT NOT NULL,
  habitat_id INT NOT NULL,
  FOREIGN KEY (race_id) REFERENCES races(id),
  FOREIGN KEY (habitat_id) REFERENCES habitats(id)
);

-- Table pour stocker les images des animaux
CREATE TABLE IF NOT EXISTS img_animaux (
  id INT AUTO_INCREMENT PRIMARY KEY,
  animal_id INT NOT NULL,
  image BLOB NOT NULL,
  FOREIGN KEY (animal_id) REFERENCES animaux(id) ON DELETE CASCADE
);

-- Table des services offerts par le zoo
CREATE TABLE IF NOT EXISTS services (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(255) NOT NULL,
  descript TEXT NOT NULL
);

-- Table des avis laissés par les visiteurs
CREATE TABLE IF NOT EXISTS avis (
  id INT AUTO_INCREMENT PRIMARY KEY,
  pseudo VARCHAR(255) NOT NULL,
  avis TEXT NOT NULL,
  valide BOOLEAN NOT NULL,
  employe_id INT NOT NULL,
  FOREIGN KEY (employe_id) REFERENCES utilisateurs(id)
);

-- Table des rapports vétérinaires
CREATE TABLE IF NOT EXISTS rapports_veterinaires (
  id INT AUTO_INCREMENT PRIMARY KEY,
  animal_id INT NOT NULL,
  veterinaire_id INT NOT NULL,
  etat VARCHAR(255),
  nourriture VARCHAR(255),
  grammage DOUBLE NOT NULL,
  date DATE,
  detail TEXT,
  FOREIGN KEY (animal_id) REFERENCES animaux(id) ON DELETE CASCADE,
  FOREIGN KEY (veterinaire_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
);

-- Table pour stocker les avis des vétérinaires sur les habitats
CREATE TABLE IF NOT EXISTS avis_habitats(
  id INT AUTO_INCREMENT PRIMARY KEY,
  habitat_id INT NOT NULL,
  veterinaire_id INT NOT NULL,
  avis TEXT NOT NULL,
  date DATE,
  FOREIGN KEY (habitat_id) REFERENCES habitats(id) ON DELETE CASCADE,
  FOREIGN KEY (veterinaire_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
);

-- Table pour stocker les informations sur l'alimentation des animaux
CREATE TABLE IF NOT EXISTS alimentations (
  id INT AUTO_INCREMENT PRIMARY KEY,
  animal_id INT NOT NULL,
  employe_id INT NOT NULL,
  date_alimentation DATETIME NOT NULL,
  nourriture VARCHAR(255) NOT NULL,
  quantite DOUBLE NOT NULL,
  FOREIGN KEY (animal_id) REFERENCES animaux(id) ON DELETE CASCADE,
  FOREIGN KEY (employe_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
);

