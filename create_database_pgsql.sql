-- Création de la base de données

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
  date DATE NOT NULL,
  nourriture VARCHAR(255) NOT NULL,
  quantite DOUBLE PRECISION NOT NULL,
  FOREIGN KEY (animal_id) REFERENCES animaux (id) ON DELETE CASCADE,
  FOREIGN KEY (employe_id) REFERENCES utilisateurs (id) ON DELETE CASCADE
);

COMMENT ON COLUMN alimentations.date IS '(DC2Type:date_immutable)';

-- Table `avis`
CREATE TABLE avis (
  id SERIAL PRIMARY KEY,
  employe_id INTEGER,
  pseudo VARCHAR(255) NOT NULL,
  rating SMALLINT NOT NULL,
  avis TEXT NOT NULL,
  valide BOOLEAN NOT NULL,
  FOREIGN KEY (employe_id) REFERENCES utilisateurs (id)
);

-- Table `avis_habitats`
CREATE TABLE avis_habitats (
  id SERIAL PRIMARY KEY,
  habitat_id INTEGER,
  date DATE NOT NULL,
  veterinaire_id INTEGER,
  avis TEXT NOT NULL,
  FOREIGN KEY (habitat_id) REFERENCES habitats (id),
  FOREIGN KEY (veterinaire_id) REFERENCES utilisateurs (id)
);

COMMENT ON COLUMN avis_habitats.date IS '(DC2Type:date_immutable)';

-- Table `services`
CREATE TABLE services (
  id SERIAL PRIMARY KEY,
  nom VARCHAR(255) NOT NULL,
  descript TEXT NOT NULL
);

-- Table pour stocker les images des services
CREATE TABLE img_services (
  id SERIAL PRIMARY KEY,
  service_id INTEGER NOT NULL,
  image_name VARCHAR(255) NOT NULL,
  FOREIGN KEY (service_id) REFERENCES services(id) 
);

-- Table `img_animaux`
CREATE TABLE img_animaux (
  id SERIAL PRIMARY KEY,
  animal_id INTEGER NOT NULL,
  image_name VARCHAR(255) NOT NULL,
  FOREIGN KEY (animal_id) REFERENCES animaux (id)
);

-- Table `img_habitats`
CREATE TABLE img_habitats (
  id SERIAL PRIMARY KEY,
  habitat_id INTEGER NOT NULL,
  image_name VARCHAR(255) NOT NULL,
  FOREIGN KEY (habitat_id) REFERENCES habitats (id)
);

-- Table `rapports_veterinaires`
CREATE TABLE rapports_veterinaires (
  id SERIAL PRIMARY KEY,
  date DATE NOT NULL,
  animal_id INTEGER NOT NULL,
  veterinaire_id INTEGER NOT NULL,
  etat VARCHAR(255) NOT NULL,
  nourriture VARCHAR(255) NOT NULL,
  grammage DOUBLE PRECISION NOT NULL,
  detail TEXT,
  FOREIGN KEY (animal_id) REFERENCES animaux (id),
  FOREIGN KEY (veterinaire_id) REFERENCES utilisateurs (id)
);

COMMENT ON COLUMN rapports_veterinaires.date IS '(DC2Type:date_immutable)';

-- Table `horaire`
CREATE TABLE horaire (
  id SERIAL PRIMARY KEY,
  jour VARCHAR(255) NOT NULL,
  ouvre TIME NOT NULL,
  ferme TIME NOT NULL
);

-- Création des séquences
CREATE SEQUENCE alimentations_id_seq START WITH 1 INCREMENT BY 1 NO MINVALUE NO MAXVALUE CACHE 1;
CREATE SEQUENCE animaux_id_seq START WITH 1 INCREMENT BY 1 NO MINVALUE NO MAXVALUE CACHE 1;
CREATE SEQUENCE avis_id_seq START WITH 1 INCREMENT BY 1 NO MINVALUE NO MAXVALUE CACHE 1;
CREATE SEQUENCE avis_habitats_id_seq START WITH 1 INCREMENT BY 1 NO MINVALUE NO MAXVALUE CACHE 1;
CREATE SEQUENCE habitats_id_seq START WITH 1 INCREMENT BY 1 NO MINVALUE NO MAXVALUE CACHE 1;
CREATE SEQUENCE horaire_id_seq START WITH 1 INCREMENT BY 1 NO MINVALUE NO MAXVALUE CACHE 1;
CREATE SEQUENCE img_animaux_id_seq START WITH 1 INCREMENT BY 1 NO MINVALUE NO MAXVALUE CACHE 1;
CREATE SEQUENCE img_habitats_id_seq START WITH 1 INCREMENT BY 1 NO MINVALUE NO MAXVALUE CACHE 1;
CREATE SEQUENCE img_services_id_seq START WITH 1 INCREMENT BY 1 NO MINVALUE NO MAXVALUE CACHE 1;
CREATE SEQUENCE races_id_seq START WITH 1 INCREMENT BY 1 NO MINVALUE NO MAXVALUE CACHE 1;
CREATE SEQUENCE rapports_veterinaires_id_seq START WITH 1 INCREMENT BY 1 NO MINVALUE NO MAXVALUE CACHE 1;
CREATE SEQUENCE services_id_seq START WITH 1 INCREMENT BY 1 NO MINVALUE NO MAXVALUE CACHE 1;
CREATE SEQUENCE utilisateurs_id_seq START WITH 1 INCREMENT BY 1 NO MINVALUE NO MAXVALUE CACHE 1;

-- Associations des séquences aux tables
ALTER SEQUENCE alimentations_id_seq OWNED BY alimentations.id;
ALTER SEQUENCE animaux_id_seq OWNED BY animaux.id;
ALTER SEQUENCE avis_id_seq OWNED BY avis.id;
ALTER SEQUENCE avis_habitats_id_seq OWNED BY avis_habitats.id;
ALTER SEQUENCE habitats_id_seq OWNED BY habitats.id;
ALTER SEQUENCE horaire_id_seq OWNED BY horaire.id;
ALTER SEQUENCE img_animaux_id_seq OWNED BY img_animaux.id;
ALTER SEQUENCE img_habitats_id_seq OWNED BY img_habitats.id;
ALTER SEQUENCE img_services_id_seq OWNED BY img_services.id;
ALTER SEQUENCE races_id_seq OWNED BY races.id;
ALTER SEQUENCE rapports_veterinaires_id_seq OWNED BY rapports_veterinaires.id;
ALTER SEQUENCE services_id_seq OWNED BY services.id;
ALTER SEQUENCE utilisateurs_id_seq OWNED BY utilisateurs.id;
