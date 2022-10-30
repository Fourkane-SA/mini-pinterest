-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : sam. 02 mai 2020 à 10:23
-- Version du serveur :  10.4.11-MariaDB
-- Version de PHP : 7.4.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `mini-pinterest`
--
CREATE DATABASE IF NOT EXISTS `mini-pinterest` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `mini-pinterest`;

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

CREATE TABLE `categorie` (
  `catId` int(255) NOT NULL,
  `nomCat` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `categorie`
--

INSERT INTO `categorie` (`catId`, `nomCat`) VALUES
(1, 'animaux'),
(2, 'avions');

-- --------------------------------------------------------

--
-- Structure de la table `compte`
--

CREATE TABLE `compte` (
  `utilisateur` varchar(40) CHARACTER SET utf8 NOT NULL,
  `motDePasse` char(60) CHARACTER SET utf8 NOT NULL,
  `idUtilisateur` int(250) NOT NULL,
  `priver` tinyint(1) NOT NULL,
  `heureConnexion` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `compte`
--

INSERT INTO `compte` (`utilisateur`, `motDePasse`, `idUtilisateur`, `priver`, `heureConnexion`) VALUES
('admin', '$2y$10$7hYSzN.BadpJsv5rXGlG5.maRYpdP6LZZPWHdIUasgDIMyVZLMM2C', 0, 0, '2020-05-02 10:12:44'),
('user', '$2y$10$3cLatLk770zqW5B0j7SpXuUfts5cMghfBnJq7nqVqaTOJsxsnCOFi', 1, 0, '2020-05-02 10:12:11'),
('Patrick', '$2y$10$ZNAE.K5xFUnOt4SrdLEiHuj8iWas4XK3o4/2knXaBNr/tlsv.KWwG', 2, 1, '2020-05-02 10:11:06'),
('Violla', '$2y$10$FZQ60JEYCUNw.WFo518.M..p4eixP.pp9Kes/q1OhByMdvnZ/Uz.e', 3, 0, '2020-05-02 10:19:08'),
('Hyppo', '$2y$10$gV.Z9TA.QUPQq6Zl8lSIU.KQzNbK0qcMHk2M9YTWSk4rHFX82xB3a', 4, 0, '2020-05-01 17:15:05');

-- --------------------------------------------------------

--
-- Structure de la table `photo`
--

CREATE TABLE `photo` (
  `photoId` int(255) NOT NULL,
  `nomFich` varchar(250) CHARACTER SET utf8 NOT NULL,
  `description` varchar(250) CHARACTER SET utf8 NOT NULL,
  `catId` int(255) NOT NULL,
  `idUtilisateur` int(250) NOT NULL,
  `heurePublication` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `photo`
--

INSERT INTO `photo` (`photoId`, `nomFich`, `description`, `catId`, `idUtilisateur`, `heurePublication`) VALUES
(22, 'flying-v.jpg', 'Un prototype d\'avion', 2, 0, 'samedi 02 mai 08:13:13'),
(35, 'Koala.jpg', 'Un Koala', 1, 2, 'samedi 02 mai 08:13:13'),
(38, 'Ramphastos.jpg', 'Un Toucan', 1, 3, 'samedi 02 mai 08:13:13'),
(42, 'Iguane.jpg', 'Un Iguane', 1, 4, 'samedi 02 mai 08:13:13'),
(43, 'Furtif.jpg', 'Un avion furtif', 2, 0, 'samedi 02 mai 08:13:13'),
(44, 'Guépard.jpg', 'Un Guépard', 1, 4, 'samedi 02 mai 08:13:13'),
(45, 'cobra_5029090.jpg', 'Un Cobra', 1, 3, 'samedi 02 mai 08:13:13'),
(48, 'RhinocéRhoff.jpg', 'Un Rhinocéros', 1, 1, 'samedi 02 mai 08:13:13'),
(64, 'Avion1.jpg', 'Un avion', 2, 2, 'samedi 02 mai 10:11:50'),
(65, 'Vautour.jpg', 'Un vantour', 1, 1, 'samedi 02 mai 10:12:27');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`catId`);

--
-- Index pour la table `compte`
--
ALTER TABLE `compte`
  ADD PRIMARY KEY (`idUtilisateur`);

--
-- Index pour la table `photo`
--
ALTER TABLE `photo`
  ADD PRIMARY KEY (`photoId`),
  ADD KEY `catId_fk` (`catId`),
  ADD KEY `idUtilisateur_fk` (`idUtilisateur`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `photo`
--
ALTER TABLE `photo`
  MODIFY `photoId` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
