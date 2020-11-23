-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : Dim 22 nov. 2020 à 21:52
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
  `idBateau` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  PRIMARY KEY (`idBateau`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `bateau`
--

INSERT INTO `bateau` (`idBateau`, `nom`) VALUES
(1, 'Kor\' Ant'),
(2, 'Ar Solen'),
(3, 'Al\'xi'),
(4, 'Luce isle'),
(5, 'Maëllys');

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
-- Structure de la table `reservation`
--

DROP TABLE IF EXISTS `reservation`;
CREATE TABLE IF NOT EXISTS `reservation` (
  `numReserv` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `adr` varchar(50) NOT NULL,
  `cp` int(11) NOT NULL,
  `ville` varchar(50) NOT NULL,
  `numTrav` int(11) NOT NULL,
  `codeuti` int(11) NOT NULL,
  PRIMARY KEY (`numReserv`),
  KEY `RESERVATION_CODE_FK` (`codeuti`),
  KEY `RESERVATION_TRAVERSEE_FK` (`numTrav`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  `tarif` int(11) NOT NULL,
  PRIMARY KEY (`dateDeb`,`code`),
  KEY `TARIFER_LIAISON0_FK` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `traversee`
--

DROP TABLE IF EXISTS `traversee`;
CREATE TABLE IF NOT EXISTS `traversee` (
  `numTrav` int(11) NOT NULL,
  `date` date NOT NULL,
  `heure` varchar(50) NOT NULL,
  `idBateau` int(11) NOT NULL,
  `code` int(11) NOT NULL,
  PRIMARY KEY (`numTrav`),
  KEY `TRAVERSEE_BATEAU_FK` (`idBateau`),
  KEY `TRAVERSEE_LIAISON0_FK` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `traversee`
--

INSERT INTO `traversee` (`numTrav`, `date`, `heure`, `idBateau`, `code`) VALUES
(76, '2020-11-24', '14:30', 1, 25),
(143, '2020-11-26', '17:53', 2, 16),
(256, '2020-11-25', '9:42', 2, 15),
(367, '2020-11-25', '9:41', 3, 15);

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
  PRIMARY KEY (`code_uti`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`code_uti`, `nom_uti`, `mdp_uti`) VALUES
(1, 'Sazed', '0103');

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
  ADD CONSTRAINT `TARIFER_PERIODE_FK` FOREIGN KEY (`dateDeb`) REFERENCES `periode` (`dateDeb`);

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