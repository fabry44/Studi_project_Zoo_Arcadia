-- Création de la base de données
-- Pour PostgreSQL, créez la base de données séparément si nécessaire
-- CREATE DATABASE testarcadia_db_test;



-- Table `utilisateurs`
CREATE TABLE utilisateurs (
  id SERIAL PRIMARY KEY,
  username VARCHAR(180) NOT NULL UNIQUE,
  nom VARCHAR(255) NOT NULL,
  prenom VARCHAR(255) NOT NULL,
  roles JSONB NOT NULL,
  password VARCHAR(255) NOT NULL,
  is_verified BOOLEAN NOT NULL
);

-- Table `races`
CREATE TABLE races (
  id SERIAL PRIMARY KEY,
  label VARCHAR(255) NOT NULL
);

-- Table `habitats`
CREATE TABLE habitats (
  id SERIAL PRIMARY KEY,
  nom VARCHAR(255) NOT NULL,
  descript TEXT NOT NULL
);

-- Table `animaux`
CREATE TABLE animaux (
  id SERIAL PRIMARY KEY,
  prenom VARCHAR(255) NOT NULL,
  race_id INTEGER NOT NULL,
  habitat_id INTEGER NOT NULL,
  FOREIGN KEY (race_id) REFERENCES races (id),
  FOREIGN KEY (habitat_id) REFERENCES habitats (id)
);

-- Table `alimentations`
CREATE TABLE alimentations (
  id SERIAL PRIMARY KEY,
  animal_id INTEGER NOT NULL,
  employe_id INTEGER NOT NULL,
  date timestamp(0) without time zone NOT NULL,
  nourriture VARCHAR(255) NOT NULL,
  quantite DOUBLE PRECISION NOT NULL,
  FOREIGN KEY (animal_id) REFERENCES animaux (id) ON DELETE CASCADE,
  FOREIGN KEY (employe_id) REFERENCES utilisateurs (id) ON DELETE CASCADE
);

-- Table `avis`
CREATE TABLE avis (
  id SERIAL PRIMARY KEY,
  employe_id INTEGER,
  pseudo VARCHAR(255) NOT NULL,
  rating smallint NOT NULL,
  avis TEXT NOT NULL,
  valide BOOLEAN NOT NULL,
  FOREIGN KEY (employe_id) REFERENCES utilisateurs (id)
);

-- Table `avis_habitats`
CREATE TABLE avis_habitats (
  id SERIAL PRIMARY KEY,
  habitat_id INTEGER,
  date timestamp(0) without time zone NOT NULL,
  veterinaire_id INTEGER,
  avis TEXT NOT NULL,
  FOREIGN KEY (habitat_id) REFERENCES habitats (id),
  FOREIGN KEY (veterinaire_id) REFERENCES utilisateurs (id)
);

-- Table `services`
CREATE TABLE services (
  id SERIAL PRIMARY KEY,
  nom VARCHAR(255) NOT NULL,
  descript TEXT NOT NULL
);

-- Table pour stocker les images des services
CREATE TABLE img_services (
  id SERIAL PRIMARY KEY,
  service_id INT NOT NULL,
  image_name VARCHAR(255) NOT NULL,
  -- image_size INT NOT NULL,
  -- updated_at TIMESTAMP NOT NULL,
  FOREIGN KEY (service_id) REFERENCES services(id) 
);

-- Table `img_animaux`
CREATE TABLE img_animaux (
  id SERIAL PRIMARY KEY,
  animal_id INTEGER NOT NULL,
  image_name VARCHAR(255) NOT NULL,
  -- image_size INT NOT NULL,
  -- updated_at TIMESTAMP NOT NULL,
  FOREIGN KEY (animal_id) REFERENCES animaux (id)
);

-- Table `img_habitats`
CREATE TABLE img_habitats (
  id SERIAL PRIMARY KEY,
  habitat_id INTEGER NOT NULL,
  image_name VARCHAR(255) NOT NULL,
  -- image_size INT NOT NULL,
  -- updated_at TIMESTAMP NOT NULL,
  FOREIGN KEY (habitat_id) REFERENCES habitats (id)
);

-- Table `rapports_veterinaires`
CREATE TABLE rapports_veterinaires (
  id SERIAL PRIMARY KEY,
  date timestamp(0) without time zone NOT NULL,
  animal_id INTEGER NOT NULL,
  veterinaire_id INTEGER NOT NULL,
  etat VARCHAR(255) NOT NULL,
  nourriture VARCHAR(255) NOT NULL,
  grammage DOUBLE PRECISION NOT NULL,
  detail TEXT,
  FOREIGN KEY (animal_id) REFERENCES animaux (id),
  FOREIGN KEY (veterinaire_id) REFERENCES utilisateurs (id)
);
