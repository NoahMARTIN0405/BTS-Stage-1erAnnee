-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 19 juin 2025 à 14:52
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `db_actia`
--

-- --------------------------------------------------------

--
-- Structure de la table `absence`
--

CREATE TABLE `absence` (
  `id_absence` int(11) NOT NULL,
  `type_absence` varchar(50) DEFAULT NULL,
  `date_absence` date DEFAULT NULL,
  `id_utilisateur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `engagement`
--

CREATE TABLE `engagement` (
  `idengagement` int(11) NOT NULL,
  `date_engagement` date DEFAULT NULL,
  `qte_engagement` int(11) DEFAULT NULL,
  `code_ax` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `production`
--

CREATE TABLE `production` (
  `idproduction` int(11) NOT NULL,
  `date_production` date DEFAULT NULL,
  `qte_production` int(11) DEFAULT NULL,
  `code_ax` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

CREATE TABLE `produit` (
  `code_ax` varchar(50) NOT NULL,
  `code_movex` varchar(50) DEFAULT NULL,
  `designation_produit` varchar(50) DEFAULT NULL,
  `reference_commerciale` varchar(50) DEFAULT NULL,
  `stock_secu_attendu` int(11) NOT NULL,
  `stock_secu_reel` int(11) NOT NULL,
  `commentaire_stock` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`code_ax`, `code_movex`, `designation_produit`, `reference_commerciale`, `stock_secu_attendu`, `stock_secu_reel`, `commentaire_stock`) VALUES
('95007487', 'AD967933', 'PPRO PPU BUSEK (ACS)', 'F0100401-04', 0, 0, NULL),
('95007546', 'KIP0000001PLAT', 'PPRO KIM PLAT', 'MOD-0001,3 KIM1 V1,3', 0, 0, NULL),
('95007547', 'KIP0000001', 'PPRO KIM1', 'MOD-0001,3 KIM1 V1,3', 0, 0, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `usertype`
--

CREATE TABLE `usertype` (
  `id_usertype` int(11) NOT NULL,
  `lib_usertype` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `usertype`
--

INSERT INTO `usertype` (`id_usertype`, `lib_usertype`, `description`) VALUES
(1, 'Utilisateur', ''),
(2, 'Administrateur', ' '),
(3, 'Super-Administrateur', '');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id_utilisateur` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `mdp` varchar(255) NOT NULL,
  `nom` varchar(50) DEFAULT NULL,
  `prenom` varchar(50) DEFAULT NULL,
  `unite_production` varchar(50) DEFAULT NULL,
  `secteur` varchar(50) DEFAULT NULL,
  `nom_prenom_manager` varchar(50) DEFAULT NULL,
  `type_emploi` varchar(50) DEFAULT NULL,
  `type_contrat` varchar(50) DEFAULT NULL,
  `type_equipe` varchar(50) DEFAULT NULL,
  `statut` varchar(50) DEFAULT NULL,
  `id_usertype` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id_utilisateur`, `username`, `mdp`, `nom`, `prenom`, `unite_production`, `secteur`, `nom_prenom_manager`, `type_emploi`, `type_contrat`, `type_equipe`, `statut`, `id_usertype`) VALUES
(1, 'jdupont', 'motdepasse123', 'Dupont', 'Jean', 'Production A', 'Secteur 5', 'Martin Paul', 'Temps plein', 'CDI', 'Equipe 3', 'Actif', 1),
(2, 'msmith', 'azerty1234', 'Smith', 'Marie', 'Unité B', 'Secteur 2', 'Lemoine Clara', 'Temps partiel', 'CDD', 'Equipe 1', 'Inactif', 2),
(3, 'tnguyen', 'azerty1234', 'Nguyen', 'Thao', 'Usine C', 'Secteur 8', 'Durand Jean', 'Consultant', 'Intérim', 'Equipe 2', 'Actif', 3);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `absence`
--
ALTER TABLE `absence`
  ADD PRIMARY KEY (`id_absence`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `engagement`
--
ALTER TABLE `engagement`
  ADD PRIMARY KEY (`idengagement`),
  ADD KEY `code_ax` (`code_ax`);

--
-- Index pour la table `production`
--
ALTER TABLE `production`
  ADD PRIMARY KEY (`idproduction`),
  ADD KEY `code_ax` (`code_ax`);

--
-- Index pour la table `produit`
--
ALTER TABLE `produit`
  ADD PRIMARY KEY (`code_ax`);

--
-- Index pour la table `usertype`
--
ALTER TABLE `usertype`
  ADD PRIMARY KEY (`id_usertype`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id_utilisateur`),
  ADD KEY `id_usertype` (`id_usertype`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `absence`
--
ALTER TABLE `absence`
  MODIFY `id_absence` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `engagement`
--
ALTER TABLE `engagement`
  MODIFY `idengagement` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `production`
--
ALTER TABLE `production`
  MODIFY `idproduction` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `usertype`
--
ALTER TABLE `usertype`
  MODIFY `id_usertype` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id_utilisateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `absence`
--
ALTER TABLE `absence`
  ADD CONSTRAINT `absence_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`);

--
-- Contraintes pour la table `engagement`
--
ALTER TABLE `engagement`
  ADD CONSTRAINT `engagement_ibfk_1` FOREIGN KEY (`code_ax`) REFERENCES `produit` (`code_ax`);

--
-- Contraintes pour la table `production`
--
ALTER TABLE `production`
  ADD CONSTRAINT `production_ibfk_1` FOREIGN KEY (`code_ax`) REFERENCES `produit` (`code_ax`);

--
-- Contraintes pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD CONSTRAINT `utilisateur_ibfk_1` FOREIGN KEY (`id_usertype`) REFERENCES `usertype` (`id_usertype`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
