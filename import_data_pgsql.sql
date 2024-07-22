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
('Savane', 'Un vaste paysage ouvert, dominé par des herbes et des arbustes, habituellement peuplé par des grands mammifères tels que lions et girafes. le climat est chaud et sec. les visiteurs peuvent observer les animaux de loin et de près selon les zones de vision.'),
('Jungle', 'Dense forêt tropicale, riche en diversité biologique, abritant de nombreuses espèces d''oiseaux et de félins. Les visiteurs peuvent explorer les sentiers sinueux et découvrir des animaux cachés dans la végétation dense.'),
('Marais', 'Zone humide caractérisée par des eaux stagnantes et une forte présence de végétation aquatique, foyer de nombreux reptiles et oiseaux. Les visiteurs peuvent observer les animaux nager et se prélasser au soleil. Des sentiers surélevés permettent une vue panoramique.');

-- Insertion des images pour les habitats
INSERT INTO img_habitats (habitat_id, image_name) VALUES
(1, 'savane-669056118970f967575288.jpeg'),     -- Image pour la Savane
(1, 'savane3-6691584f671df631628913.jpeg'),    -- Autre image pour la Savane
(1, 'savane-669ba2676e5be066955077.jpeg'),     -- Autre image pour la Savane
(2, 'jungle3-6693aa089ec55115911766.jpeg'),    -- Image pour la Jungle
(2, 'jungle4-669125ac39d74442893201.jpeg'),    -- Autre image pour la Jungle
(3, 'marais2-66908191b5d79671994346.jpeg'),    -- Image pour le Marais
(3, 'maris4-66910683ef56c507801468.jpeg');     -- Autre image pour le Marais

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

-- Insertion des images pour chaque animal
INSERT INTO img_animaux (animal_id, image_name) VALUES
(1, 'lion-669123a900693689181035.jpeg'),      -- Image du Lion
(2, 'singe-6693d90f184fd337137806.jpeg'),     -- Image du Singe
(3, 'girafe-6693d92dca587270771807.jpeg'),    -- Image de la Girafe
(4, 'jaguar-6693d94ae7c72218247810.jpeg'),    -- Image du Jaguar
(4, 'jaguar2-6693d95a53e76782803373.jpeg'),   -- Autre image du Jaguar
(5, 'perroquet-6693d976b9abe967717013.jpeg'), -- Image du Perroquet
(6, 'toucan-6693d990c3027332963537.jpeg'),    -- Image du Toucan
(7, 'alligator-6693d9aae4db0174468153.jpeg'), -- Image de l'Alligator
(8, 'heron-6693d9cb54ae0907004164.jpeg'),     -- Image du Héron
(9, 'castor-6693e55b328d0019281235.jpeg');    -- Image du Castor

-- Insertion des services offerts par le parc
INSERT INTO services (nom, descript) VALUES
('Restauration', 'Offre une variété de repas et snacks pour les visiteurs tout au long de leur visite, avec des options pour tous les goûts et préférences alimentaires.'),
('Visite guidée', 'Explorez les habitats avec un guide expert qui partagera des anecdotes fascinantes sur les animaux et leur comportement, enrichissant votre expérience. Vous pouvez poser des questions et interagir avec les animaux.'),
('Petit train', 'Profitez gratuitement d''un tour du zoo en petit train, parfait pour les familles avec enfants, offrant une vue panoramique de tous les habitats sans effort. Les arrêts sont prévus pour observer les animaux de près.');

-- Insertion des images pour les services offerts par le parc
INSERT INTO img_services (service_id, image_name) VALUES
(1, 'restauration-6693ad7c9de53481707361.jpeg'),  -- Image pour Restauration
(2, 'visite-guide-6693ad92468dd572283529.jpeg'),   -- Image pour Visite guidée
(3, 'petit-train-6693adaa629be293813458.jpeg');    -- Image pour Petit train


-- Insertion des avis sur les habitats par les vétérinaires
INSERT INTO avis_habitats (habitat_id, date, veterinaire_id, avis) VALUES
(1, CURRENT_TIMESTAMP, 2, 'Habitat en excellente condition, adapté aux espèces qu''il accueille.'),
(1, CURRENT_TIMESTAMP, 5, 'La savane est bien entretenue, mais pourrait bénéficier de plus de zones ombragées.'),
(2, CURRENT_TIMESTAMP, 2, 'La jungle présente une diversité biologique remarquable, bien entretenue.'),
(2, CURRENT_TIMESTAMP, 5, 'La végétation est dense et bien préservée, mais quelques sentiers sont difficiles à traverser.'),
(3, CURRENT_TIMESTAMP, 2, 'Le marais nécessite plus d''attention pour contrôler les moustiques.'),
(3, CURRENT_TIMESTAMP, 5, 'L''environnement aquatique est idéal pour les reptiles, bien que l''eau pourrait être plus propre.');

-- Insertion des avis
INSERT INTO avis (employe_id, pseudo, rating, avis, valide) VALUES
(3, 'Henri.D', 5, 'J''ai adoré la visite guidée, très instructive.', TRUE),
(4, 'L''inconnu',4 , 'Les habitats sont très bien conçus, les animaux semblent heureux.', TRUE),
(5, 'Moogli',3 , 'Les zones de restauration sont propres et accueillantes.', TRUE),
(3, 'Un visiteur',5 , 'Le petit train est parfait pour voir tout le parc sans se fatiguer.', TRUE),
(4, 'David.P',5 , 'La diversité des animaux est impressionnante.', TRUE),
(5, 'J.T',4 , 'Les explications des guides sont très claires.', TRUE),
(3, 'Isabelle',5 , 'Les enfants ont adoré la savane, surtout les girafes.', TRUE),
(4, 'Mickaël.R',2 , 'J''aurais aimé plus de zones ombragées.', TRUE),
(5, 'Jean-Miche',3 , 'Le personnel est très accueillant et serviable.', TRUE),
(3, 'un autre visiteur',4 , 'La propreté des installations est impeccable.', TRUE);

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
(1, CURRENT_TIMESTAMP, 2, 'Sain', 'Viande', 5, 'Le lion, nommé Simba, affiche une vitalité remarquable avec un pelage lustré et des signes clairs de bonne santé nutritionnelle et physique.'),
(1, CURRENT_TIMESTAMP, 5, 'Sain', 'Viande', 5, 'Simba est extrêmement actif et sociable au sein de son groupe, montrant une agilité et une force exemplaires lors des interactions.'),
(2, CURRENT_TIMESTAMP, 2, 'Sain', 'Fruits', 1.5, 'Cesar le singe présente une excellente dextérité et curiosité, grimpant fréquemment et explorant son environnement avec vigueur.'),
(2, CURRENT_TIMESTAMP, 5, 'Sain', 'Fruits', 1.5, 'Il interagit bien avec les autres singes et montre une sociabilité élevée, ce qui est vital pour son bien-être psychologique.'),
(3, CURRENT_TIMESTAMP, 2, 'Sain', 'Feuilles', 10, 'Gerry la girafe montre une bonne mobilité des articulations malgré une légère raideur observée lors des changements de temps.'),
(3, CURRENT_TIMESTAMP, 5, 'Sain', 'Feuilles', 10, 'Son alimentation est bien gérée, permettant un maintien optimal de son poids et de sa santé globale.'),
(4, CURRENT_TIMESTAMP, 2, 'Sain', 'Viande', 4.5, 'Diego le jaguar démontre une grande aptitude à la chasse simulée, ce qui indique un excellent état de santé mentale et physique.'),
(4, CURRENT_TIMESTAMP, 5, 'Sain', 'Viande', 4.5, 'Aucun signe de maladie n''a été détecté lors des derniers examens, et il montre une adaptation parfaite à son habitat.'),
(5, CURRENT_TIMESTAMP, 2, 'Sain', 'Graines', 0.3, 'Polly le perroquet a un plumage vibrant et complet, un indicateur clé de bonne santé chez les oiseaux.'),
(5, CURRENT_TIMESTAMP, 5, 'Sain', 'Graines', 0.3, 'Il est très communicatif et répond bien aux stimuli, ce qui est essentiel pour les oiseaux de son espèce.'),
(6, CURRENT_TIMESTAMP, 2, 'Sain', 'Fruits', 0.4, 'Tico le toucan affiche une grande énergie et un comportement ludique, souvent vu jouant avec des objets ou d''autres toucans.'),
(6, CURRENT_TIMESTAMP, 5, 'Sain', 'Fruits', 0.4, 'Sa capacité à manipuler des objets avec son bec est excellente, montrant une bonne santé neurologique.'),
(7, CURRENT_TIMESTAMP, 2, 'Sain', 'Poissons', 2, 'Ally l''alligator montre une activité normale et une alimentation régulière, deux facteurs importants pour sa santé à long terme.'),
(7, CURRENT_TIMESTAMP, 5, 'Sain', 'Poissons', 2, 'Ses réflexes et sa capacité à répondre à l''environnement sont excellents, ce qui démontre un bon équilibre neurologique.'),
(8, CURRENT_TIMESTAMP, 2, 'Sain', 'Poissons', 1.8, 'Harry le héron excelle dans la pêche, un comportement naturel qui montre sa bonne santé et son adaptation.'),
(8, CURRENT_TIMESTAMP, 5, 'Sain', 'Poissons', 1.8, 'Sa capacité à rester immobile pendant de longues périodes et son agilité lors du vol sont impressionnantes.'),
(9, CURRENT_TIMESTAMP, 2, 'Sain', 'Bois', 1, 'Bucky le castor est très actif dans la construction de barrages, ce qui est crucial pour son bien-être psychologique et physique.'),
(9, CURRENT_TIMESTAMP, 5, 'Sain', 'Bois', 1, 'Son interaction avec l''environnement et d''autres castors est positive, indiquant un état de santé stable et actif.');

-- Horaires d'ouverture du parc
INSERT INTO horaire (id, jour, ouvre, ferme) VALUES
(1, 'Lundi', '09:00:00', '18:00:00'),
(2, 'Mardi', '09:00:00', '18:00:00'),
(3, 'Mercredi', '09:00:00', '18:00:00'),
(4, 'Jeudi', '09:00:00', '18:00:00'),
(5, 'Vendredi', '09:00:00', '18:00:00'),
(6, 'Samedi', '10:00:00', '20:00:00'),
(7, 'Dimanche', '10:00:00', '20:00:00');

-- Fermeture de la transaction pour garantir l'intégrité
COMMIT;
