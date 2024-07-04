-- Configurations initiales
-- Ces commandes sont spécifiques à MySQL/MariaDB et ne sont pas nécessaires pour PostgreSQL
-- SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
-- START TRANSACTION;
-- SET time_zone = "+00:00";

BEGIN;

-- Chargement des données de la table `utilisateurs`
INSERT INTO utilisateurs (username, nom, prenom, password, roles, is_verified) VALUES
('admin@gmail.com', 'L''administrateur', 'UserAdmin', '$2y$13$vDdTvu9ixbwkU3HlEY/2DOtQw/k5UBy0.GAJOTP/AYa9ip8bXYybS', '["ROLE_ADMIN"]'::jsonb, TRUE),
('veterinaire@gmail.com', 'Le vétérinaire', 'UserVétérinaire', '$2y$13$vDdTvu9ixbwkU3HlEY/2DOtQw/k5UBy0.GAJOTP/AYa9ip8bXYybS', '["ROLE_VETERINAIRE"]'::jsonb, TRUE),
('employe@gmail.com', 'L''employé', 'UserEmployé', '$2y$13$KkISQjBj.0hdQaXABASw3O3QKkpD0kLQflLWeyHe6k6Ext.Ew6WRC', '["ROLE_EMPLOYE"]'::jsonb, TRUE);

-- Chargement des données pour les habitats
INSERT INTO habitats (nom, descript) VALUES
('Savane', 'Un vaste paysage ouvert, dominé par des herbes et des arbustes, habituellement peuplé par des grands mammifères tels que lions et girafes.'),
('Jungle', 'Dense forêt tropicale, riche en diversité biologique, abritant de nombreuses espèces d''oiseaux et de félins.'),
('Marais', 'Zone humide caractérisée par des eaux stagnantes et une forte présence de végétation aquatique, foyer de nombreux reptiles et oiseaux.');

-- Chargement des données pour les races
INSERT INTO races (label) VALUES
('Lion'), ('Singe'), ('Girafe'), 
('Jaguar'), ('Perroquet'), ('Toucan'), 
('Alligator'), ('Héron'), ('Castor');

-- Insertion des animaux avec les associations avec leurs habitats et races correspondantes
INSERT INTO animaux (prenom, race_id, habitat_id) VALUES
('Simba', 1, 1), -- Lion dans la Savane
('Cesar', 2, 1), -- Singe dans la Savane
('Gerry', 3, 1), -- Girafe dans la Savane
('Diego', 4, 2), -- Jaguar dans la Jungle
('Polly', 5, 2), -- Perroquet dans la Jungle
('Tico', 6, 2), -- Toucan dans la Jungle
('Ally', 7, 3), -- Alligator dans les Marais
('Harry', 8, 3), -- Héron dans les Marais
('Bucky', 9, 3); -- Castor dans les Marais

-- Insertion des services offerts par le parc
INSERT INTO services (nom, descript) VALUES
('Restauration', 'Offre une variété de repas et snacks pour les visiteurs tout au long de leur visite.'),
('Visite guidée', 'Visite des habitats avec un guide expert pour apprendre sur les animaux et leur environnement.'),
('Petit train', 'Tour du zoo en petit train, idéal pour les familles et permettant de voir tous les principaux habitats.');

-- Insertion des avis sur les habitats par les vétérinaires
INSERT INTO avis_habitats (habitat_id, veterinaire_id, avis, date_avis) VALUES
(1, 2, 'Habitat en excellente condition, adapté aux espèces qu''il accueille.', CURRENT_DATE),
(2, 2, 'La jungle présente une diversité biologique remarquable, bien entretenue.', CURRENT_DATE),
(3, 2, 'Le marais nécessite plus d''attention pour contrôler les moustiques.', CURRENT_DATE);

-- Alimentation des animaux (exemple)
INSERT INTO alimentations (animal_id, employe_id, date_alimentation, nourriture, quantite) VALUES
(1, 3, CURRENT_DATE, 'Viande', 5.0),
(2, 3, CURRENT_DATE, 'Fruits', 1.5),
(3, 3, CURRENT_DATE, 'Feuilles', 10.0);

-- Rapports vétérinaires pour chaque animal en exemple
INSERT INTO rapports_veterinaires (animal_id, veterinaire_id, etat, nourriture, grammage, detail) VALUES
(1, 2, 'Sain', 'Viande', 5, 'Le lion montre une excellente santé et vigueur.'),
(2, 2, 'Sain', 'Fruits', 1.5, 'Actif et en bonne santé, montre une curiosité naturelle.'),
(3, 2, 'Sain', 'Feuilles', 10, 'Santé stable, nécessite surveillance continue des pattes pour signes d''arthrite.');

-- Fermeture de la transaction pour garantir l'intégrité
COMMIT;
