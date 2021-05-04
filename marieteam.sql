-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : Dim 02 mai 2021 à 13:57
-- Version du serveur :  5.7.31
-- Version de PHP : 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `marieteam`
--

-- --------------------------------------------------------

--
-- Structure de la table `bateau`
--

DROP TABLE IF EXISTS `bateau`;
CREATE TABLE IF NOT EXISTS `bateau` (
  `idBateau` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  `longueur` double NOT NULL,
  `largeur` double NOT NULL,
  `vitesse` double DEFAULT NULL,
  `image` varchar(50) DEFAULT NULL,
  `poidsMax` double DEFAULT NULL,
  `type` varchar(11) NOT NULL,
  PRIMARY KEY (`idBateau`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `bateau`
--

INSERT INTO `bateau` (`idBateau`, `nom`, `longueur`, `largeur`, `vitesse`, `image`, `poidsMax`, `type`) VALUES
(1, 'Kor\' Ant', 31.8, 9.4, 22, 'E:\\Eclipse\\PPE-Marieteam\\KorAnt.jpg', NULL, 'v'),
(2, 'Ar Solen', 34.8, 16, 16, 'E:\\Eclipse\\PPE-Marieteam\\ArSolen.jpg', NULL, 'v'),
(3, 'Al\'xi', 25, 7, 16, 'E:\\Eclipse\\PPE-Marieteam\\Alxi.jpg', NULL, 'v'),
(4, 'Luce isle', 37.2, 8.6, 26, 'E:\\Eclipse\\PPE-Marieteam\\LuceIsle.jpg', NULL, 'v'),
(5, 'Maëllys', 38, 10, 28, 'E:\\Eclipse\\PPE-Marieteam\\Maellys.jpg', NULL, 'v'),
(6, 'Edouard', 95.6, 32, NULL, NULL, 3200, 'f');

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

DROP TABLE IF EXISTS `categorie`;
CREATE TABLE IF NOT EXISTS `categorie` (
  `lettre` varchar(50) NOT NULL,
  `libelle` varchar(50) NOT NULL,
  PRIMARY KEY (`lettre`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `categorie`
--

INSERT INTO `categorie` (`lettre`, `libelle`) VALUES
('A', 'Passager'),
('B', 'Véh.inf.2m'),
('C', 'Véh.sup.2m');

-- --------------------------------------------------------

--
-- Structure de la table `contenir`
--

DROP TABLE IF EXISTS `contenir`;
CREATE TABLE IF NOT EXISTS `contenir` (
  `idBateau` int(11) NOT NULL,
  `lettre` varchar(50) NOT NULL,
  `capaciteMax` int(11) NOT NULL,
  PRIMARY KEY (`idBateau`,`lettre`),
  KEY `CONTENIR_CATEGORIE0_FK` (`lettre`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `contenir`
--

INSERT INTO `contenir` (`idBateau`, `lettre`, `capaciteMax`) VALUES
(1, 'A', 238),
(1, 'B', 11),
(1, 'C', 2),
(2, 'A', 276),
(2, 'B', 5),
(2, 'C', 1),
(3, 'A', 250),
(3, 'B', 3),
(3, 'C', 0),
(4, 'A', 155),
(4, 'B', 0),
(4, 'C', 0),
(5, 'A', 132),
(5, 'B', 0),
(5, 'C', 0);

-- --------------------------------------------------------

--
-- Structure de la table `enregistrer`
--

DROP TABLE IF EXISTS `enregistrer`;
CREATE TABLE IF NOT EXISTS `enregistrer` (
  `numType` int(11) NOT NULL,
  `numReserv` int(11) NOT NULL,
  `quantite` int(11) NOT NULL,
  PRIMARY KEY (`numType`,`numReserv`),
  KEY `ENREGISTRER_RESERVATION0_FK` (`numReserv`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `enregistrer`
--

INSERT INTO `enregistrer` (`numType`, `numReserv`, `quantite`) VALUES
(1, 17052, 2),
(1, 54471, 2),
(2, 54471, 1),
(4, 54471, 1);

-- --------------------------------------------------------

--
-- Structure de la table `equipement`
--

DROP TABLE IF EXISTS `equipement`;
CREATE TABLE IF NOT EXISTS `equipement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lib` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `equipement`
--

INSERT INTO `equipement` (`id`, `lib`) VALUES
(1, 'Accès Handicapé'),
(2, 'Bar'),
(3, 'Pont promenade'),
(4, 'Salon Video'),
(5, 'Piscine'),
(6, 'Spa'),
(7, 'Salle de cinéma');

-- --------------------------------------------------------

--
-- Structure de la table `liaison`
--

DROP TABLE IF EXISTS `liaison`;
CREATE TABLE IF NOT EXISTS `liaison` (
  `code` int(11) NOT NULL,
  `distance` varchar(50) NOT NULL,
  `idPort` int(11) NOT NULL,
  `idPort_ARRIVEE` int(11) NOT NULL,
  `idSecteur` int(11) NOT NULL,
  PRIMARY KEY (`code`),
  KEY `LIAISON_PORT_FK` (`idPort`),
  KEY `LIAISON_PORT0_FK` (`idPort_ARRIVEE`),
  KEY `LIAISON_SECTEUR1_FK` (`idSecteur`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `liaison`
--

INSERT INTO `liaison` (`code`, `distance`, `idPort`, `idPort_ARRIVEE`, `idSecteur`) VALUES
(11, '25.1', 2, 4, 1),
(15, '8.3', 1, 2, 1),
(16, '8', 1, 3, 1),
(17, '7.9', 3, 1, 1),
(19, '23.7', 4, 2, 1),
(21, '7.7', 7, 6, 3),
(22, '7.4', 6, 7, 3),
(24, '9', 2, 1, 1),
(25, '8.8', 1, 5, 2),
(30, '8.8', 5, 1, 2);

-- --------------------------------------------------------

--
-- Structure de la table `periode`
--

DROP TABLE IF EXISTS `periode`;
CREATE TABLE IF NOT EXISTS `periode` (
  `dateDeb` date NOT NULL,
  `dateFin` date NOT NULL,
  PRIMARY KEY (`dateDeb`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `periode`
--

INSERT INTO `periode` (`dateDeb`, `dateFin`) VALUES
('2020-09-01', '2021-06-15'),
('2021-06-16', '2021-09-15'),
('2021-09-16', '2022-05-31');

-- --------------------------------------------------------

--
-- Structure de la table `port`
--

DROP TABLE IF EXISTS `port`;
CREATE TABLE IF NOT EXISTS `port` (
  `idPort` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  PRIMARY KEY (`idPort`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `port`
--

INSERT INTO `port` (`idPort`, `nom`) VALUES
(1, 'Quiberon'),
(2, 'Le Palais'),
(3, 'Sauzon'),
(4, 'Vannes'),
(5, 'Port St Gildas'),
(6, 'Port-Tudy'),
(7, 'Lorient');

-- --------------------------------------------------------

--
-- Structure de la table `posseder`
--

DROP TABLE IF EXISTS `posseder`;
CREATE TABLE IF NOT EXISTS `posseder` (
  `idBat` int(11) NOT NULL,
  `idEquip` int(11) NOT NULL,
  PRIMARY KEY (`idBat`,`idEquip`),
  KEY `EQUIPEMENT_FK` (`idEquip`),
  KEY `BATEAU_FK` (`idBat`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `posseder`
--

INSERT INTO `posseder` (`idBat`, `idEquip`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(1, 2),
(2, 2),
(4, 2),
(1, 3),
(3, 3),
(4, 3),
(5, 3),
(2, 4),
(4, 4),
(2, 5),
(3, 5),
(5, 5),
(2, 6),
(3, 6),
(5, 7);

-- --------------------------------------------------------

--
-- Structure de la table `reservation`
--

DROP TABLE IF EXISTS `reservation`;
CREATE TABLE IF NOT EXISTS `reservation` (
  `numReserv` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  `adr` varchar(50) NOT NULL,
  `cp` int(11) NOT NULL,
  `ville` varchar(50) NOT NULL,
  `prix` double NOT NULL DEFAULT '0',
  `numTrav` int(11) NOT NULL,
  `codeuti` int(11) NOT NULL,
  PRIMARY KEY (`numReserv`),
  KEY `RESERVATION_CODE_FK` (`codeuti`),
  KEY `RESERVATION_TRAVERSEE_FK` (`numTrav`)
) ENGINE=InnoDB AUTO_INCREMENT=54472 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `reservation`
--

INSERT INTO `reservation` (`numReserv`, `nom`, `adr`, `cp`, `ville`, `prix`, `numTrav`, `codeuti`) VALUES
(17052, 'Petillon', '966 avenue de dunkerque', 59160, 'Lomme', 38, 256, 1),
(54471, 'Petillon', '966 avenue de dunkerque', 59160, 'Lomme', 141.1, 367, 1);

-- --------------------------------------------------------

--
-- Structure de la table `secteur`
--

DROP TABLE IF EXISTS `secteur`;
CREATE TABLE IF NOT EXISTS `secteur` (
  `idSecteur` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  PRIMARY KEY (`idSecteur`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `secteur`
--

INSERT INTO `secteur` (`idSecteur`, `nom`) VALUES
(1, 'Belle-Ile-en-Mer'),
(2, 'Houat'),
(3, 'Ile de Groix');

-- --------------------------------------------------------

--
-- Structure de la table `tarifer`
--

DROP TABLE IF EXISTS `tarifer`;
CREATE TABLE IF NOT EXISTS `tarifer` (
  `dateDeb` date NOT NULL,
  `code` int(11) NOT NULL,
  `tarif` double NOT NULL,
  `numType` int(11) NOT NULL,
  PRIMARY KEY (`dateDeb`,`code`,`numType`),
  KEY `TARIFER_LIAISON0_FK` (`code`),
  KEY `numType` (`numType`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `tarifer`
--

INSERT INTO `tarifer` (`dateDeb`, `code`, `tarif`, `numType`) VALUES
('2020-09-01', 15, 18, 1),
('2020-09-01', 15, 11.1, 2),
('2020-09-01', 15, 5.6, 3),
('2020-09-01', 15, 86, 4),
('2020-09-01', 15, 129, 5),
('2020-09-01', 15, 189, 6),
('2020-09-01', 15, 205, 7),
('2020-09-01', 15, 268, 8),
('2020-09-01', 19, 27.2, 1),
('2020-09-01', 19, 17.3, 2),
('2020-09-01', 19, 9.8, 3),
('2020-09-01', 19, 129, 4),
('2020-09-01', 19, 194, 5),
('2020-09-01', 19, 284, 6),
('2020-09-01', 19, 308, 7),
('2020-09-01', 19, 402, 8),
('2021-06-16', 15, 20, 1),
('2021-06-16', 15, 13.1, 2),
('2021-06-16', 15, 7, 3),
('2021-06-16', 15, 95, 4),
('2021-06-16', 15, 142, 5),
('2021-06-16', 15, 208, 6),
('2021-06-16', 15, 226, 7),
('2021-06-16', 15, 295, 8),
('2021-06-16', 19, 29.3, 1),
('2021-06-16', 19, 18.6, 2),
('2021-06-16', 19, 10.6, 3),
('2021-06-16', 19, 139, 4),
('2021-06-16', 19, 209, 5),
('2021-06-16', 19, 306, 6),
('2021-06-16', 19, 332, 7),
('2021-06-16', 19, 434, 8),
('2021-09-16', 15, 19, 1),
('2021-09-16', 15, 12.1, 2),
('2021-09-16', 15, 6.4, 3),
('2021-09-16', 15, 91, 4),
('2021-09-16', 15, 136, 5),
('2021-09-16', 15, 199, 6),
('2021-09-16', 15, 216, 7),
('2021-09-16', 15, 282, 8),
('2021-09-16', 19, 28.5, 1),
('2021-09-16', 19, 18.1, 2),
('2021-09-16', 19, 10.2, 3),
('2021-09-16', 19, 135, 4),
('2021-09-16', 19, 203, 5),
('2021-09-16', 19, 298, 6),
('2021-09-16', 19, 323, 7),
('2021-09-16', 19, 422, 8);

-- --------------------------------------------------------

--
-- Structure de la table `traversee`
--

DROP TABLE IF EXISTS `traversee`;
CREATE TABLE IF NOT EXISTS `traversee` (
  `numTrav` int(11) NOT NULL,
  `date` date NOT NULL,
  `heure` varchar(50) NOT NULL,
  `placesA` int(11) NOT NULL,
  `placesB` int(11) NOT NULL,
  `placesC` int(11) NOT NULL,
  `idBateau` int(11) NOT NULL,
  `code` int(11) NOT NULL,
  PRIMARY KEY (`numTrav`),
  KEY `TRAVERSEE_BATEAU_FK` (`idBateau`),
  KEY `TRAVERSEE_LIAISON0_FK` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `traversee`
--

INSERT INTO `traversee` (`numTrav`, `date`, `heure`, `placesA`, `placesB`, `placesC`, `idBateau`, `code`) VALUES
(76, '2021-11-24', '14:30', 238, 11, 2, 1, 25),
(143, '2021-11-26', '17:53', 276, 5, 1, 2, 16),
(256, '2021-11-25', '9:42', 272, 4, 1, 2, 15),
(367, '2021-11-25', '9:41', 234, 1, 0, 3, 15);

-- --------------------------------------------------------

--
-- Structure de la table `type`
--

DROP TABLE IF EXISTS `type`;
CREATE TABLE IF NOT EXISTS `type` (
  `numType` int(11) NOT NULL,
  `libelle` varchar(50) NOT NULL,
  `lettre` varchar(50) NOT NULL,
  PRIMARY KEY (`numType`),
  KEY `TYPE_CATEGORIE_FK` (`lettre`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `type`
--

INSERT INTO `type` (`numType`, `libelle`, `lettre`) VALUES
(1, 'Adulte', 'A'),
(2, 'Junior 8 à 18 ans', 'A'),
(3, 'Enfant de 0 à 7 ans', 'A'),
(4, 'Voiture long.inf.4m', 'B'),
(5, 'Voiture long.inf.5m', 'B'),
(6, 'Fourgon', 'C'),
(7, 'Camping Car', 'C'),
(8, 'Camion', 'C');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `code_uti` int(11) NOT NULL AUTO_INCREMENT,
  `nom_uti` varchar(50) NOT NULL,
  `mdp_uti` varchar(50) NOT NULL,
  `pt_fid` int(11) NOT NULL,
  PRIMARY KEY (`code_uti`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`code_uti`, `nom_uti`, `mdp_uti`, `pt_fid`) VALUES
(1, 'Sazed', '0103', 9150);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `contenir`
--
ALTER TABLE `contenir`
  ADD CONSTRAINT `CONTENIR_BATEAU_FK` FOREIGN KEY (`idBateau`) REFERENCES `bateau` (`idBateau`),
  ADD CONSTRAINT `CONTENIR_CATEGORIE0_FK` FOREIGN KEY (`lettre`) REFERENCES `categorie` (`lettre`);

--
-- Contraintes pour la table `enregistrer`
--
ALTER TABLE `enregistrer`
  ADD CONSTRAINT `ENREGISTRER_RESERVATION0_FK` FOREIGN KEY (`numReserv`) REFERENCES `reservation` (`numReserv`),
  ADD CONSTRAINT `ENREGISTRER_TYPE_FK` FOREIGN KEY (`numType`) REFERENCES `type` (`numType`);

--
-- Contraintes pour la table `liaison`
--
ALTER TABLE `liaison`
  ADD CONSTRAINT `LIAISON_PORT0_FK` FOREIGN KEY (`idPort_ARRIVEE`) REFERENCES `port` (`idPort`),
  ADD CONSTRAINT `LIAISON_PORT_FK` FOREIGN KEY (`idPort`) REFERENCES `port` (`idPort`),
  ADD CONSTRAINT `LIAISON_SECTEUR1_FK` FOREIGN KEY (`idSecteur`) REFERENCES `secteur` (`idSecteur`);

--
-- Contraintes pour la table `posseder`
--
ALTER TABLE `posseder`
  ADD CONSTRAINT `posseder_ibfk_1` FOREIGN KEY (`idBat`) REFERENCES `bateau` (`idBateau`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `posseder_ibfk_2` FOREIGN KEY (`idEquip`) REFERENCES `equipement` (`id`);

--
-- Contraintes pour la table `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `RESERVATION_CODE_FK` FOREIGN KEY (`codeuti`) REFERENCES `utilisateur` (`code_uti`),
  ADD CONSTRAINT `RESERVATION_TRAVERSEE_FK` FOREIGN KEY (`numTrav`) REFERENCES `traversee` (`numTrav`);

--
-- Contraintes pour la table `tarifer`
--
ALTER TABLE `tarifer`
  ADD CONSTRAINT `TARIFER_LIAISON0_FK` FOREIGN KEY (`code`) REFERENCES `liaison` (`code`),
  ADD CONSTRAINT `TARIFER_PERIODE_FK` FOREIGN KEY (`dateDeb`) REFERENCES `periode` (`dateDeb`),
  ADD CONSTRAINT `tarifer_ibfk_1` FOREIGN KEY (`numType`) REFERENCES `type` (`numType`);

--
-- Contraintes pour la table `traversee`
--
ALTER TABLE `traversee`
  ADD CONSTRAINT `TRAVERSEE_BATEAU_FK` FOREIGN KEY (`idBateau`) REFERENCES `bateau` (`idBateau`),
  ADD CONSTRAINT `TRAVERSEE_LIAISON0_FK` FOREIGN KEY (`code`) REFERENCES `liaison` (`code`);

--
-- Contraintes pour la table `type`
--
ALTER TABLE `type`
  ADD CONSTRAINT `TYPE_CATEGORIE_FK` FOREIGN KEY (`lettre`) REFERENCES `categorie` (`lettre`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
