-- Création de la base de données

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'SQL_ASCII';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';
SET default_table_access_method = heap;

-- Table `alimentations`
CREATE TABLE public.alimentations (
    id integer NOT NULL,
    animal_id integer NOT NULL,
    employe_id integer NOT NULL,
    date date NOT NULL,
    nourriture character varying(255) NOT NULL,
    quantite double precision NOT NULL
);
ALTER TABLE public.alimentations OWNER TO postgres;
COMMENT ON COLUMN public.alimentations.date IS '(DC2Type:date_immutable)';

-- Séquence `alimentations_id_seq`
CREATE SEQUENCE public.alimentations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
ALTER SEQUENCE public.alimentations_id_seq OWNER TO postgres;
ALTER SEQUENCE public.alimentations_id_seq OWNED BY public.alimentations.id;

-- Table `animaux`
CREATE TABLE public.animaux (
    id integer NOT NULL,
    prenom character varying(255) NOT NULL,
    race_id integer NOT NULL,
    habitat_id integer NOT NULL
);
ALTER TABLE public.animaux OWNER TO postgres;

-- Séquence `animaux_id_seq`
CREATE SEQUENCE public.animaux_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
ALTER SEQUENCE public.animaux_id_seq OWNER TO postgres;
ALTER SEQUENCE public.animaux_id_seq OWNED BY public.animaux.id;

-- Table `avis`
CREATE TABLE public.avis (
    id integer NOT NULL,
    employe_id integer,
    pseudo character varying(255) NOT NULL,
    rating smallint NOT NULL,
    avis text NOT NULL,
    valide boolean NOT NULL
);
ALTER TABLE public.avis OWNER TO postgres;

-- Séquence `avis_id_seq`
CREATE SEQUENCE public.avis_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
ALTER SEQUENCE public.avis_id_seq OWNER TO postgres;
ALTER SEQUENCE public.avis_id_seq OWNED BY public.avis.id;

-- Table `avis_habitats`
CREATE TABLE public.avis_habitats (
    id integer NOT NULL,
    habitat_id integer,
    date date NOT NULL,
    veterinaire_id integer,
    avis text NOT NULL
);
ALTER TABLE public.avis_habitats OWNER TO postgres;
COMMENT ON COLUMN public.avis_habitats.date IS '(DC2Type:date_immutable)';

-- Séquence `avis_habitats_id_seq`
CREATE SEQUENCE public.avis_habitats_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
ALTER SEQUENCE public.avis_habitats_id_seq OWNER TO postgres;
ALTER SEQUENCE public.avis_habitats_id_seq OWNED BY public.avis_habitats.id;

-- Table `habitats`
CREATE TABLE public.habitats (
    id integer NOT NULL,
    nom character varying(255) NOT NULL,
    descript text NOT NULL
);
ALTER TABLE public.habitats OWNER TO postgres;

-- Séquence `habitats_id_seq`
CREATE SEQUENCE public.habitats_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
ALTER SEQUENCE public.habitats_id_seq OWNER TO postgres;
ALTER SEQUENCE public.habitats_id_seq OWNED BY public.habitats.id;

-- Table `horaire`
CREATE TABLE public.horaire (
    id integer NOT NULL,
    jour character varying(255) NOT NULL,
    ouvre time(0) without time zone NOT NULL,
    ferme time(0) without time zone NOT NULL
);
ALTER TABLE public.horaire OWNER TO postgres;

-- Séquence `horaire_id_seq`
CREATE SEQUENCE public.horaire_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
ALTER SEQUENCE public.horaire_id_seq OWNER TO postgres;

-- Table `img_animaux`
CREATE TABLE public.img_animaux (
    id integer NOT NULL,
    animal_id integer NOT NULL,
    image_name character varying(255)
);
ALTER TABLE public.img_animaux OWNER TO postgres;

-- Séquence `img_animaux_id_seq`
CREATE SEQUENCE public.img_animaux_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
ALTER SEQUENCE public.img_animaux_id_seq OWNER TO postgres;
ALTER SEQUENCE public.img_animaux_id_seq OWNED BY public.img_animaux.id;

-- Table `img_habitats`
CREATE TABLE public.img_habitats (
    id integer NOT NULL,
    habitat_id integer NOT NULL,
    image_name character varying(255)
);
ALTER TABLE public.img_habitats OWNER TO postgres;

-- Séquence `img_habitats_id_seq`
CREATE SEQUENCE public.img_habitats_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
ALTER SEQUENCE public.img_habitats_id_seq OWNER TO postgres;
ALTER SEQUENCE public.img_habitats_id_seq OWNED BY public.img_habitats.id;

-- Table `img_services`
CREATE TABLE public.img_services (
    id integer NOT NULL,
    services_id integer NOT NULL,
    image_name character varying(255)
);
ALTER TABLE public.img_services OWNER TO postgres;

-- Séquence `img_services_id_seq`
CREATE SEQUENCE public.img_services_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
ALTER SEQUENCE public.img_services_id_seq OWNER TO postgres;
ALTER SEQUENCE public.img_services_id_seq OWNED BY public.img_services.id;

-- Table `races`
CREATE TABLE public.races (
    id integer NOT NULL,
    label character varying(255) NOT NULL
);
ALTER TABLE public.races OWNER TO postgres;

-- Séquence `races_id_seq`
CREATE SEQUENCE public.races_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
ALTER SEQUENCE public.races_id_seq OWNER TO postgres;
ALTER SEQUENCE public.races_id_seq OWNED BY public.races.id;

-- Table `rapports_veterinaires`
CREATE TABLE public.rapports_veterinaires (
    id integer NOT NULL,
    date date NOT NULL,
    animal_id integer NOT NULL,
    veterinaire_id integer NOT NULL,
    etat character varying(255) NOT NULL,
    nourriture character varying(255) NOT NULL,
    grammage double precision NOT NULL,
    detail text
);
ALTER TABLE public.rapports_veterinaires OWNER TO postgres;
COMMENT ON COLUMN public.rapports_veterinaires.date IS '(DC2Type:date_immutable)';

-- Séquence `rapports_veterinaires_id_seq`
CREATE SEQUENCE public.rapports_veterinaires_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
ALTER SEQUENCE public.rapports_veterinaires_id_seq OWNER TO postgres;
ALTER SEQUENCE public.rapports_veterinaires_id_seq OWNED BY public.rapports_veterinaires.id;

-- Table `services`
CREATE TABLE public.services (
    id integer NOT NULL,
    nom character varying(255) NOT NULL,
    descript text NOT NULL
);
ALTER TABLE public.services OWNER TO postgres;

-- Séquence `services_id_seq`
CREATE SEQUENCE public.services_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
ALTER SEQUENCE public.services_id_seq OWNER TO postgres;
ALTER SEQUENCE public.services_id_seq OWNED BY public.services.id;

-- Table `utilisateurs`
CREATE TABLE public.utilisateurs (
    id integer NOT NULL,
    username character varying(180) NOT NULL,
    nom character varying(255) NOT NULL,
    prenom character varying(255) NOT NULL,
    roles jsonb NOT NULL,
    password character varying(255) NOT NULL,
    is_verified boolean NOT NULL
);
ALTER TABLE public.utilisateurs OWNER TO postgres;

-- Séquence `utilisateurs_id_seq`
CREATE SEQUENCE public.utilisateurs_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
ALTER SEQUENCE public.utilisateurs_id_seq OWNER TO postgres;
ALTER SEQUENCE public.utilisateurs_id_seq OWNED BY public.utilisateurs.id;

-- Définir les valeurs des séquences
SELECT pg_catalog.setval('public.alimentations_id_seq', 9, true);
SELECT pg_catalog.setval('public.animaux_id_seq', 9, true);
SELECT pg_catalog.setval('public.avis_habitats_id_seq', 6, true);
SELECT pg_catalog.setval('public.avis_id_seq', 10, true);
SELECT pg_catalog.setval('public.habitats_id_seq', 3, true);
SELECT pg_catalog.setval('public.horaire_id_seq', 1, false);
SELECT pg_catalog.setval('public.img_animaux_id_seq', 10, true);
SELECT pg_catalog.setval('public.img_habitats_id_seq', 7, true);
SELECT pg_catalog.setval('public.img_services_id_seq', 3, true);
SELECT pg_catalog.setval('public.races_id_seq', 9, true);
SELECT pg_catalog.setval('public.rapports_veterinaires_id_seq', 18, true);
SELECT pg_catalog.setval('public.services_id_seq', 3, true);
SELECT pg_catalog.setval('public.utilisateurs_id_seq', 5, true);

-- Clés primaires
ALTER TABLE ONLY public.alimentations ADD CONSTRAINT alimentations_pkey PRIMARY KEY (id);
ALTER TABLE ONLY public.animaux ADD CONSTRAINT animaux_pkey PRIMARY KEY (id);
ALTER TABLE ONLY public.avis_habitats ADD CONSTRAINT avis_habitats_pkey PRIMARY KEY (id);
ALTER TABLE ONLY public.avis ADD CONSTRAINT avis_pkey PRIMARY KEY (id);
ALTER TABLE ONLY public.habitats ADD CONSTRAINT habitats_pkey PRIMARY KEY (id);
ALTER TABLE ONLY public.horaire ADD CONSTRAINT horaire_pkey PRIMARY KEY (id);
ALTER TABLE ONLY public.img_animaux ADD CONSTRAINT img_animaux_pkey PRIMARY KEY (id);
ALTER TABLE ONLY public.img_habitats ADD CONSTRAINT img_habitats_pkey PRIMARY KEY (id);
ALTER TABLE ONLY public.img_services ADD CONSTRAINT img_services_pkey PRIMARY KEY (id);
ALTER TABLE ONLY public.races ADD CONSTRAINT races_pkey PRIMARY KEY (id);
ALTER TABLE ONLY public.rapports_veterinaires ADD CONSTRAINT rapports_veterinaires_pkey PRIMARY KEY (id);
ALTER TABLE ONLY public.services ADD CONSTRAINT services_pkey PRIMARY KEY (id);
ALTER TABLE ONLY public.utilisateurs ADD CONSTRAINT utilisateurs_pkey PRIMARY KEY (id);

-- Clé unique
ALTER TABLE ONLY public.utilisateurs ADD CONSTRAINT uniq_identifier_username UNIQUE (username);

-- Index
CREATE INDEX idx_ddaee467aef5a6c1 ON public.img_services USING btree (services_id);

-- Clés étrangères
ALTER TABLE ONLY public.alimentations ADD CONSTRAINT alimentations_animal_id_fkey FOREIGN KEY (animal_id) REFERENCES public.animaux(id) ON DELETE CASCADE;
ALTER TABLE ONLY public.alimentations ADD CONSTRAINT alimentations_employe_id_fkey FOREIGN KEY (employe_id) REFERENCES public.utilisateurs(id) ON DELETE CASCADE;
ALTER TABLE ONLY public.animaux ADD CONSTRAINT animaux_habitat_id_fkey FOREIGN KEY (habitat_id) REFERENCES public.habitats(id);
ALTER TABLE ONLY public.animaux ADD CONSTRAINT animaux_race_id_fkey FOREIGN KEY (race_id) REFERENCES public.races(id);
ALTER TABLE ONLY public.avis ADD CONSTRAINT avis_employe_id_fkey FOREIGN KEY (employe_id) REFERENCES public.utilisateurs(id);
ALTER TABLE ONLY public.avis_habitats ADD CONSTRAINT avis_habitats_habitat_id_fkey FOREIGN KEY (habitat_id) REFERENCES public.habitats(id);
ALTER TABLE ONLY public.avis_habitats ADD CONSTRAINT avis_habitats_veterinaire_id_fkey FOREIGN KEY (veterinaire_id) REFERENCES public.utilisateurs(id);
ALTER TABLE ONLY public.img_services ADD CONSTRAINT fk_ddaee467aef5a6c1 FOREIGN KEY (services_id) REFERENCES public.services(id);
ALTER TABLE ONLY public.img_animaux ADD CONSTRAINT img_animaux_animal_id_fkey FOREIGN KEY (animal_id) REFERENCES public.animaux(id);
ALTER TABLE ONLY public.img_habitats ADD CONSTRAINT img_habitats_habitat_id_fkey FOREIGN KEY (habitat_id) REFERENCES public.habitats(id);
ALTER TABLE ONLY public.rapports_veterinaires ADD CONSTRAINT rapports_veterinaires_animal_id_fkey FOREIGN KEY (animal_id) REFERENCES public.animaux(id);
ALTER TABLE ONLY public.rapports_veterinaires ADD CONSTRAINT rapports_veterinaires_veterinaire_id_fkey FOREIGN KEY (veterinaire_id) REFERENCES public.utilisateurs(id);
