-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 24 juin 2024 à 12:40
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

-- Configurations initiales
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Configuration des jeux de caractères pour assurer la compatibilité
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- Création de la base de données si elle n'existe pas déjà
CREATE DATABASE IF NOT EXISTS `arcadia_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `arcadia_db`;

-- Table `utilisateurs`
CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(180) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT '(DC2Type:json)' CHECK (json_valid(`roles`)),
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_IDENTIFIER_USERNAME` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Table pour stocker les informations des utilisateurs';

-- Table `races`
CREATE TABLE `races` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Table `habitats`
CREATE TABLE `habitats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `descript` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Table `animaux`
CREATE TABLE `animaux` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prenom` varchar(255) NOT NULL,
  `race_id` int(11) NOT NULL,
  `habitat_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_9ABE194DAFFE2D26` (`habitat_id`),
  KEY `IDX_9ABE194D6E59D40D` (`race_id`),
  CONSTRAINT `FK_9ABE194D6E59D40D` FOREIGN KEY (`race_id`) REFERENCES `races` (`id`),
  CONSTRAINT `FK_9ABE194DAFFE2D26` FOREIGN KEY (`habitat_id`) REFERENCES `habitats` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Table `alimentations`
CREATE TABLE `alimentations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `animal_id` int(11) NOT NULL,
  `employe_id` int(11) NOT NULL,
  `date_alimentation` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `nourriture` varchar(255) NOT NULL,
  `quantite` double NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_CD56093C8E962C16` (`animal_id`),
  KEY `IDX_CD56093C1B65292` (`employe_id`),
  CONSTRAINT `FK_CD56093C1B65292` FOREIGN KEY (`employe_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_CD56093C8E962C16` FOREIGN KEY (`animal_id`) REFERENCES `animaux` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Table `avis`
CREATE TABLE `avis` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employe_id` int(11) DEFAULT NULL,
  `pseudo` varchar(255) NOT NULL,
  `avis` longtext NOT NULL,
  `valide` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_8F91ABF01B65292` (`employe_id`),
  CONSTRAINT `FK_8F91ABF01B65292` FOREIGN KEY (`employe_id`) REFERENCES `utilisateurs` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Table `avis_habitats`
CREATE TABLE `avis_habitats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `habitat_id` int(11) DEFAULT NULL,
  `veterinaire_id` int(11) DEFAULT NULL,
  `avis` longtext NOT NULL,
  `date_avis` date NOT NULL COMMENT '(DC2Type:date_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_5CAC3456AFFE2D26` (`habitat_id`),
  KEY `IDX_5CAC34565C80924` (`veterinaire_id`),
  CONSTRAINT `FK_5CAC34565C80924` FOREIGN KEY (`veterinaire_id`) REFERENCES `utilisateurs` (`id`),
  CONSTRAINT `FK_5CAC3456AFFE2D26` FOREIGN KEY (`habitat_id`) REFERENCES `habitats` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Table `services`
CREATE TABLE `services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `descript` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Table `img_animaux`
CREATE TABLE `img_animaux` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `animal_id` int(11) NOT NULL,
  `image` longblob NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_D7D15DA58E962C16` (`animal_id`),
  CONSTRAINT `FK_D7D15DA58E962C16` FOREIGN KEY (`animal_id`) REFERENCES `animaux` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Table `img_habitats`
CREATE TABLE `img_habitats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `habitat_id` int(11) NOT NULL,
  `image` longblob NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_1B7897FDAFFE2D26` (`habitat_id`),
  CONSTRAINT `FK_1B7897FDAFFE2D26` FOREIGN KEY (`habitat_id`) REFERENCES `habitats` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Table `rapports_veterinaires`
CREATE TABLE `rapports_veterinaires` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `animal_id` int(11) NOT NULL,
  `veterinaire_id` int(11) NOT NULL,
  `etat` varchar(255) NOT NULL,
  `nourriture` varchar(255) NOT NULL,
  `grammage` double NOT NULL,
  `detail` longtext DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_A35921A8E962C16` (`animal_id`),
  KEY `IDX_A35921A5C80924` (`veterinaire_id`),
  CONSTRAINT `FK_A35921A5C80924` FOREIGN KEY (`veterinaire_id`) REFERENCES `utilisateurs` (`id`),
  CONSTRAINT `FK_A35921A8E962C16` FOREIGN KEY (`animal_id`) REFERENCES `animaux` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Fermeture de la transaction pour garantir l'intégrité
COMMIT;

-- Restauration des configurations de jeu de caractères initiales
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
