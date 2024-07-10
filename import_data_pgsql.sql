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
('employe@gmail.com', 'L''employé', 'UserEmployé', '$2y$13$KkISQjBj.0hdQaXABASw3O3QKkpD0kLQflLWeyHe6k6Ext.Ew6WRC', '["ROLE_EMPLOYE"]'::jsonb, TRUE),
('nouvel.employe@gmail.com', 'Nouveau', 'Employé', '$2y$13$KkISQjBj.0hdQaXABASw3O3QKkpD0kLQflLWeyHe6k6Ext.Ew6WRC', '["ROLE_EMPLOYE"]'::jsonb, TRUE),
('nouvel.veterinaire@gmail.com', 'Nouveau', 'Vétérinaire', '$2y$13$vDdTvu9ixbwkU3HlEY/2DOtQw/k5UBy0.GAJOTP/AYa9ip8bXYybS', '["ROLE_VETERINAIRE"]'::jsonb, TRUE);

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
INSERT INTO avis_habitats (habitat_id, date, veterinaire_id, avis) VALUES
(1, CURRENT_TIMESTAMP, 2, 'Habitat en excellente condition, adapté aux espèces qu''il accueille.'),
(1, CURRENT_TIMESTAMP, 5, 'La savane est bien entretenue, mais pourrait bénéficier de plus de zones ombragées.'),
(2, CURRENT_TIMESTAMP, 2, 'La jungle présente une diversité biologique remarquable, bien entretenue.'),
(2, CURRENT_TIMESTAMP, 5, 'La végétation est dense et bien préservée, mais quelques sentiers sont difficiles à traverser.'),
(3, CURRENT_TIMESTAMP, 2, 'Le marais nécessite plus d''attention pour contrôler les moustiques.'),
(3, CURRENT_TIMESTAMP, 5, 'L''environnement aquatique est idéal pour les reptiles, bien que l''eau pourrait être plus propre.');

-- Insertion des avis
INSERT INTO avis (employe_id, pseudo, avis, valide) VALUES
(3, 'Visitor1', 'J''ai adoré la visite guidée, très instructive.', TRUE),
(4, 'Visitor2', 'Les habitats sont très bien conçus, les animaux semblent heureux.', TRUE),
(5, 'Visitor3', 'Les zones de restauration sont propres et accueillantes.', TRUE),
(3, 'Visitor4', 'Le petit train est parfait pour voir tout le parc sans se fatiguer.', TRUE),
(4, 'Visitor5', 'La diversité des animaux est impressionnante.', TRUE),
(5, 'Visitor6', 'Les explications des guides sont très claires.', TRUE),
(3, 'Visitor7', 'Les enfants ont adoré la savane, surtout les girafes.', TRUE),
(4, 'Visitor8', 'J''aurais aimé plus de zones ombragées.', TRUE),
(5, 'Visitor9', 'Le personnel est très accueillant et serviable.', TRUE),
(3, 'Visitor10', 'La propreté des installations est impeccable.', TRUE);

-- Alimentation des animaux (exemple)
INSERT INTO alimentations (animal_id, employe_id, date, nourriture, quantite) VALUES
(1, 3, CURRENT_TIMESTAMP, 'Viande', 5.0),
(2, 3, CURRENT_TIMESTAMP, 'Fruits', 1.5),
(3, 3, CURRENT_TIMESTAMP, 'Feuilles', 10.0),
(4, 4, CURRENT_TIMESTAMP, 'Viande', 4.5),
(5, 4, CURRENT_TIMESTAMP, 'Graines', 0.3),
(6, 4, CURRENT_TIMESTAMP, 'Fruits', 0.4),
(7, 3, CURRENT_TIMESTAMP, 'Poissons', 2.0),
(8, 3, CURRENT_TIMESTAMP, 'Poissons', 1.8),
(9, 4, CURRENT_TIMESTAMP, 'Bois', 1.0);

-- Rapports vétérinaires pour chaque animal en exemple
INSERT INTO rapports_veterinaires (animal_id, date, veterinaire_id, etat, nourriture, grammage, detail) VALUES
(1, CURRENT_TIMESTAMP, 2, 'Sain', 'Viande', 5, 'Le lion montre une excellente santé et vigueur.'),
(1, CURRENT_TIMESTAMP, 5, 'Sain', 'Viande', 5, 'Excellente condition physique, très actif.'),
(2, CURRENT_TIMESTAMP, 2, 'Sain', 'Fruits', 1.5, 'Actif et en bonne santé, montre une curiosité naturelle.'),
(2, CURRENT_TIMESTAMP, 5, 'Sain', 'Fruits', 1.5, 'Bon état général, très social.'),
(3, CURRENT_TIMESTAMP, 2, 'Sain', 'Feuilles', 10, 'Santé stable, nécessite surveillance continue des pattes pour signes d''arthrite.'),
(3, CURRENT_TIMESTAMP, 5, 'Sain', 'Feuilles', 10, 'État de santé optimal, très paisible.'),
(4, CURRENT_TIMESTAMP, 2, 'Sain', 'Viande', 4.5, 'Le jaguar est en pleine forme, montre un comportement normal.'),
(4, CURRENT_TIMESTAMP, 5, 'Sain', 'Viande', 4.5, 'Très actif, aucun signe de maladie.'),
(5, CURRENT_TIMESTAMP, 2, 'Sain', 'Graines', 0.3, 'Le perroquet est en bonne santé, plumage brillant.'),
(5, CURRENT_TIMESTAMP, 5, 'Sain', 'Graines', 0.3, 'Très vocal et social, bonne condition physique.'),
(6, CURRENT_TIMESTAMP, 2, 'Sain', 'Fruits', 0.4, 'Le toucan est en bonne forme, très énergique.'),
(6, CURRENT_TIMESTAMP, 5, 'Sain', 'Fruits', 0.4, 'Très actif et curieux, aucun problème détecté.'),
(7, CURRENT_TIMESTAMP, 2, 'Sain', 'Poissons', 2, 'L''alligator montre une bonne santé, comportement normal.'),
(7, CURRENT_TIMESTAMP, 5, 'Sain', 'Poissons', 2, 'Bonne condition physique, très calme.'),
(8, CURRENT_TIMESTAMP, 2, 'Sain', 'Poissons', 1.8, 'Le héron est en pleine forme, très agile.'),
(8, CURRENT_TIMESTAMP, 5, 'Sain', 'Poissons', 1.8, 'Bonne condition générale, très alerte.'),
(9, CURRENT_TIMESTAMP, 2, 'Sain', 'Bois', 1, 'Le castor montre une excellente santé, très actif.'),
(9, CURRENT_TIMESTAMP, 5, 'Sain', 'Bois', 1, 'Très social, aucun signe de maladie.');

-- Fermeture de la transaction pour garantir l'intégrité
COMMIT;
