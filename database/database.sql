-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : dim. 08 nov. 2026 à 17:10
-- Version du serveur : 8.4.7
-- Version de PHP : 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `be_healthy`
--

-- --------------------------------------------------------

--
-- Structure de la table `bilanjournalier`
--

DROP TABLE IF EXISTS `bilanjournalier`;
CREATE TABLE IF NOT EXISTS `bilanjournalier` (
  `dateBilan` date NOT NULL,
  `nbRepas` int NOT NULL,
  `caloriesJour` float NOT NULL,
  `objCaloriesJour` float NOT NULL,
  `batterieCorporelle` int NOT NULL,
  `id_user` int DEFAULT NULL,
  KEY `FK_user_bilan` (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `exercice`
--

DROP TABLE IF EXISTS `exercice`;
CREATE TABLE IF NOT EXISTS `exercice` (
  `id_exercice` int NOT NULL AUTO_INCREMENT,
  `nomExercice` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `dureeMinutes` int NOT NULL,
  `difficulte` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `calBrulees` float NOT NULL,
  `lienVideo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id_exercice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `exercice_muscle`
--

DROP TABLE IF EXISTS `exercice_muscle`;
CREATE TABLE IF NOT EXISTS `exercice_muscle` (
  `id_exercice` int NOT NULL,
  `id_muscle` int NOT NULL,
  PRIMARY KEY (`id_exercice`,`id_muscle`),
  KEY `id_muscle` (`id_muscle`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `favoris_exercice`
--

DROP TABLE IF EXISTS `favoris_exercice`;
CREATE TABLE IF NOT EXISTS `favoris_exercice` (
  `id_user` int NOT NULL,
  `id_exercice` int NOT NULL,
  PRIMARY KEY (`id_user`,`id_exercice`),
  KEY `id_exercice` (`id_exercice`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `favoris_repas`
--

DROP TABLE IF EXISTS `favoris_repas`;
CREATE TABLE IF NOT EXISTS `favoris_repas` (
  `id_user` int NOT NULL,
  `id_repas` int NOT NULL,
  PRIMARY KEY (`id_user`,`id_repas`),
  KEY `id_repas` (`id_repas`),
  KEY `id_user` (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ingredient`
--

DROP TABLE IF EXISTS `ingredient`;
CREATE TABLE IF NOT EXISTS `ingredient` (
  `id_ingredient` int NOT NULL AUTO_INCREMENT,
  `nomIngredient` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `calories_100g` decimal(6,1) NOT NULL,
  `proteines_100g` decimal(5,1) NOT NULL,
  `glucides_100g` decimal(5,1) NOT NULL,
  `lipides_100g` decimal(5,1) NOT NULL,
  PRIMARY KEY (`id_ingredient`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ligue`
--

DROP TABLE IF EXISTS `ligue`;
CREATE TABLE IF NOT EXISTS `ligue` (
  `id_ligue` int NOT NULL AUTO_INCREMENT,
  `nomLigue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id_ligue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `muscle`
--

DROP TABLE IF EXISTS `muscle`;
CREATE TABLE IF NOT EXISTS `muscle` (
  `id_muscle` int NOT NULL AUTO_INCREMENT,
  `nomMuscle` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id_muscle`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `parametres`
--

DROP TABLE IF EXISTS `parametres`;
CREATE TABLE IF NOT EXISTS `parametres` (
  `id_param` int NOT NULL AUTO_INCREMENT,
  `notifHydratation` tinyint(1) NOT NULL,
  `notifSeance` tinyint(1) NOT NULL,
  `notifRepas` tinyint(1) NOT NULL,
  `langue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connectiviteMontre` tinyint(1) NOT NULL,
  `connectiviteBalance` tinyint(1) NOT NULL,
  `id_user` int DEFAULT NULL,
  PRIMARY KEY (`id_param`),
  KEY `FK_user_param` (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `repas`
--

DROP TABLE IF EXISTS `repas`;
CREATE TABLE IF NOT EXISTS `repas` (
  `id_repas` int NOT NULL AUTO_INCREMENT,
  `nomRepas` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `dateConsommation` date NOT NULL,
  `lienRecetteExterne` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_repas` enum('petit_dejeuner','dejeuner','diner') COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_user` int DEFAULT NULL,
  PRIMARY KEY (`id_repas`),
  KEY `FK_iduser_repas` (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `repas_ingredient`
--

DROP TABLE IF EXISTS `repas_ingredient`;
CREATE TABLE IF NOT EXISTS `repas_ingredient` (
  `id_repas` int NOT NULL,
  `id_ingredient` int NOT NULL,
  `quantite_g` decimal(6,1) NOT NULL,
  PRIMARY KEY (`id_repas`,`id_ingredient`),
  KEY `id_ingredient` (`id_ingredient`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `seance`
--

DROP TABLE IF EXISTS `seance`;
CREATE TABLE IF NOT EXISTS `seance` (
  `id_seance` int NOT NULL AUTO_INCREMENT,
  `nomSeance` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `datePrevue` date NOT NULL,
  PRIMARY KEY (`id_seance`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `seance_exercice`
--

DROP TABLE IF EXISTS `seance_exercice`;
CREATE TABLE IF NOT EXISTS `seance_exercice` (
  `id_seance` int NOT NULL,
  `id_exercice` int NOT NULL,
  PRIMARY KEY (`id_seance`,`id_exercice`),
  KEY `id_exercice` (`id_exercice`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `id_user` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `mdp` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `dateNaissance` date NOT NULL,
  `sexe` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `poids` float NOT NULL,
  `taille` float NOT NULL,
  `pointsXP` int NOT NULL,
  `id_ligue` int DEFAULT NULL,
  PRIMARY KEY (`id_user`),
  KEY `FK_user_ligue` (`id_ligue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `bilanjournalier`
--
ALTER TABLE `bilanjournalier`
  ADD CONSTRAINT `FK_user_bilan` FOREIGN KEY (`id_user`) REFERENCES `utilisateur` (`id_user`);

--
-- Contraintes pour la table `favoris_repas`
--
ALTER TABLE `favoris_repas`
  ADD CONSTRAINT `favoris_repas_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `utilisateur` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `favoris_repas_ibfk_2` FOREIGN KEY (`id_repas`) REFERENCES `repas` (`id_repas`) ON DELETE CASCADE;

--
-- Contraintes pour la table `parametres`
--
ALTER TABLE `parametres`
  ADD CONSTRAINT `FK_user_param` FOREIGN KEY (`id_user`) REFERENCES `utilisateur` (`id_user`);

--
-- Contraintes pour la table `repas`
--
ALTER TABLE `repas`
  ADD CONSTRAINT `FK_iduser_repas` FOREIGN KEY (`id_user`) REFERENCES `utilisateur` (`id_user`);

--
-- Contraintes pour la table `repas_ingredient`
--
ALTER TABLE `repas_ingredient`
  ADD CONSTRAINT `repas_ingredient_ibfk_1` FOREIGN KEY (`id_repas`) REFERENCES `repas` (`id_repas`),
  ADD CONSTRAINT `repas_ingredient_ibfk_2` FOREIGN KEY (`id_ingredient`) REFERENCES `ingredient` (`id_ingredient`);

--
-- Contraintes pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD CONSTRAINT `FK_user_ligue` FOREIGN KEY (`id_ligue`) REFERENCES `ligue` (`id_ligue`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
