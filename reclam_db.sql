-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 27 nov. 2025 à 16:52
-- Version du serveur : 8.0.31
-- Version de PHP : 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `reclam_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `affectations`
--

DROP TABLE IF EXISTS `affectations`;
CREATE TABLE IF NOT EXISTS `affectations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `reclamation_id` int NOT NULL,
  `agent_id` int NOT NULL,
  `date_affectation` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `reclamation_id` (`reclamation_id`),
  KEY `agent_id` (`agent_id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `affectations`
--

INSERT INTO `affectations` (`id`, `reclamation_id`, `agent_id`, `date_affectation`) VALUES
(1, 1, 4, '2025-11-26 19:58:55'),
(2, 2, 5, '2025-11-26 19:58:55'),
(3, 3, 4, '2025-11-26 19:58:55'),
(4, 4, 6, '2025-11-26 19:58:55'),
(5, 5, 4, '2025-11-26 19:58:55'),
(6, 6, 5, '2025-11-26 19:58:55'),
(7, 7, 6, '2025-11-26 19:58:55'),
(8, 8, 4, '2025-11-26 19:58:55'),
(9, 9, 5, '2025-11-26 19:58:55'),
(10, 10, 6, '2025-11-26 19:58:55'),
(11, 9, 6, '2025-11-26 21:38:14'),
(12, 9, 7, '2025-11-26 21:39:40'),
(13, 1, 6, '2025-11-27 16:32:37');

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `date_creation` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `nom`, `description`, `date_creation`) VALUES
(1, 'Livraison', 'Problèmes liés à la livraison', '2025-11-26 23:09:27'),
(2, 'Paiement', 'Problèmes avec les transactions', '2025-11-26 23:09:27'),
(3, 'Technique', 'Erreurs ou bugs techniques', '2025-11-26 23:09:27'),
(4, 'Service Client', 'Qualité du support', '2025-11-26 23:09:27'),
(5, 'Produit', 'Défauts ou anomalies du produit', '2025-11-26 23:09:27'),
(7, 'Abonnement', 'Problèmes avec les souscriptions', '2025-11-26 23:09:27'),
(8, 'Remboursement', 'Demandes de remboursement', '2025-11-26 23:09:27'),
(12, 'Compte', 'compte', '2025-11-27 16:51:43'),
(10, 'Autre', 'Autres types de réclamations', '2025-11-26 23:09:27');

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `reclamation_id` int NOT NULL,
  `utilisateur_id` int NOT NULL,
  `message` text NOT NULL,
  `date_creation` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `reclamation_id` (`reclamation_id`),
  KEY `utilisateur_id` (`utilisateur_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `messages`
--

INSERT INTO `messages` (`id`, `reclamation_id`, `utilisateur_id`, `message`, `date_creation`) VALUES
(1, 1, 7, 'Bonjour, je veux savoir où est ma livraison.', '2025-11-26 19:58:16'),
(2, 2, 8, 'Avez-vous trouvé mon colis ?', '2025-11-26 19:58:16'),
(3, 2, 4, 'Nous sommes en train de vérifier.', '2025-11-26 19:58:16'),
(4, 3, 9, 'Problème de paiement résolu merci.', '2025-11-26 19:58:16'),
(5, 4, 10, 'L’application plante encore.', '2025-11-26 19:58:16'),
(6, 4, 5, 'Merci, nous allons corriger rapidement.', '2025-11-26 19:58:16'),
(7, 5, 7, 'Personne ne répond au support.', '2025-11-26 19:58:16'),
(8, 6, 8, 'Produit reçu cassé, demande d’échange.', '2025-11-26 19:58:16'),
(9, 6, 5, 'Nous allons procéder à un remplacement.', '2025-11-26 19:58:16'),
(10, 7, 9, 'Il manque un accessoire.', '2025-11-26 19:58:16');

-- --------------------------------------------------------

--
-- Structure de la table `reclamations`
--

DROP TABLE IF EXISTS `reclamations`;
CREATE TABLE IF NOT EXISTS `reclamations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `utilisateur_id` int NOT NULL,
  `categorie_id` int NOT NULL,
  `sous_categorie_id` int NOT NULL,
  `objet` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `piece_jointe` varchar(255) DEFAULT NULL,
  `priorite` enum('faible','moyenne','haute') DEFAULT 'moyenne',
  `statut` enum('non_assignee','en_cours','resolue') DEFAULT 'non_assignee',
  `date_creation` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `date_mise_a_jour` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `utilisateur_id` (`utilisateur_id`),
  KEY `categorie_id` (`categorie_id`),
  KEY `sous_categorie_id` (`sous_categorie_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `reclamations`
--

INSERT INTO `reclamations` (`id`, `utilisateur_id`, `categorie_id`, `sous_categorie_id`, `objet`, `description`, `piece_jointe`, `priorite`, `statut`, `date_creation`, `date_mise_a_jour`) VALUES
(1, 7, 1, 1, 'Retard de livraison', 'Mon colis a pris 5 jours de retard.', NULL, 'moyenne', 'en_cours', '2025-11-26 19:58:08', '2025-11-27 16:32:37'),
(2, 8, 1, 2, 'Colis perdu', 'Le livreur ne trouve pas mon colis.', NULL, 'haute', 'en_cours', '2025-11-26 19:58:08', NULL),
(3, 9, 2, 4, 'Erreur de paiement', 'Le montant débité n’est pas correct.', NULL, 'moyenne', 'resolue', '2025-11-26 19:58:08', NULL),
(4, 10, 3, 6, 'Bug critique', 'L’application se ferme toute seule.', NULL, 'haute', 'en_cours', '2025-11-26 19:58:08', NULL),
(5, 7, 4, 8, 'Service lent', 'Support très lent à répondre.', NULL, 'faible', 'non_assignee', '2025-11-26 19:58:08', NULL),
(6, 8, 5, 9, 'Produit cassé', 'Produit reçu endommagé.', NULL, 'haute', 'non_assignee', '2025-11-26 19:58:08', NULL),
(7, 9, 5, 10, 'Accessoire manquant', 'Il manque un chargeur dans la boite.', NULL, 'moyenne', 'en_cours', '2025-11-26 19:58:08', NULL),
(8, 10, 2, 5, 'Carte refusée', 'Impossible de payer.', NULL, 'haute', 'resolue', '2025-11-26 19:58:08', NULL),
(9, 7, 3, 7, 'Erreur 500', 'Erreur serveur lors du paiement.', NULL, 'moyenne', 'en_cours', '2025-11-26 19:58:08', NULL),
(10, 8, 1, 3, 'Emballage déchiré', 'L’emballage est endommagé.', NULL, 'faible', 'non_assignee', '2025-11-26 19:58:08', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `remarques_reclamation`
--

DROP TABLE IF EXISTS `remarques_reclamation`;
CREATE TABLE IF NOT EXISTS `remarques_reclamation` (
  `id` int NOT NULL AUTO_INCREMENT,
  `reclamation_id` int NOT NULL,
  `utilisateur_id` int NOT NULL,
  `statut` varchar(50) NOT NULL,
  `remarque` text NOT NULL,
  `date_creation` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `reclamation_id` (`reclamation_id`),
  KEY `utilisateur_id` (`utilisateur_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `remarques_reclamation`
--

INSERT INTO `remarques_reclamation` (`id`, `reclamation_id`, `utilisateur_id`, `statut`, `remarque`, `date_creation`) VALUES
(1, 1, 4, 'en_cours', 'Client contacté.', '2025-11-26 19:58:31'),
(2, 2, 5, 'en_cours', 'Recherche du colis en cours.', '2025-11-26 19:58:31'),
(3, 3, 4, 'resolue', 'Problème corrigé par le service technique.', '2025-11-26 19:58:31'),
(4, 4, 6, 'en_cours', 'Analyse du bug en cours.', '2025-11-26 19:58:31'),
(5, 5, 4, 'non_assignee', 'En attente de prise en charge.', '2025-11-26 19:58:31'),
(6, 6, 5, 'en_cours', 'Produit cassé signalé au fournisseur.', '2025-11-26 19:58:31'),
(7, 7, 6, 'en_cours', 'Vérification du stock en cours.', '2025-11-26 19:58:31'),
(8, 8, 4, 'resolue', 'Paiement réussi.', '2025-11-26 19:58:31'),
(9, 9, 5, 'en_cours', 'Incident serveur confirmé.', '2025-11-26 19:58:31'),
(10, 10, 6, 'non_assignee', 'Emballage vérifié par le livreur.', '2025-11-26 19:58:31');

-- --------------------------------------------------------

--
-- Structure de la table `sous_categories`
--

DROP TABLE IF EXISTS `sous_categories`;
CREATE TABLE IF NOT EXISTS `sous_categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `categorie_id` int NOT NULL,
  `nom` varchar(120) NOT NULL,
  `date_creation` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `categorie_id` (`categorie_id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `sous_categories`
--

INSERT INTO `sous_categories` (`id`, `categorie_id`, `nom`, `date_creation`) VALUES
(1, 1, 'Retard de livraison', '2025-11-26 19:57:56'),
(2, 1, 'Colis perdu', '2025-11-26 19:57:56'),
(3, 1, 'Emballage endommagé', '2025-11-26 19:57:56'),
(4, 2, 'Erreur de paiement', '2025-11-26 19:57:56'),
(5, 2, 'Carte refusée', '2025-11-26 19:57:56'),
(6, 3, 'Bug de l’application', '2025-11-26 19:57:56'),
(7, 3, 'Erreur 500', '2025-11-26 19:57:56'),
(8, 4, 'Réponse lente', '2025-11-26 19:57:56'),
(9, 5, 'Produit défectueux', '2025-11-26 19:57:56'),
(10, 5, 'Accessoires manquants', '2025-11-26 19:57:56');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom_complet` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `role` enum('admin','agent','user') NOT NULL DEFAULT 'user',
  `telephone` varchar(30) DEFAULT NULL,
  `adresse` varchar(255) DEFAULT NULL,
  `date_creation` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `nom_complet`, `email`, `mot_de_passe`, `role`, `telephone`, `adresse`, `date_creation`) VALUES
(1, 'Ali Tazi', 'ali@example.com', 'hashed7', 'user', '0678123456', 'Marrakech', '2025-11-26 19:57:44'),
(2, 'Fatima Zahra', 'fatima@example.com', 'hashed8', 'user', '0689123456', 'Casablanca', '2025-11-26 19:57:44'),
(3, 'Youssef Majid', 'youssef@example.com', 'hashed9', 'user', '0691234567', 'Rabat', '2025-11-26 19:57:44'),
(4, 'Imane Chafiq', 'imane@example.com', 'hashed10', 'user', '0601234567', 'Agadir', '2025-11-26 19:57:44'),
(5, 'Hamza jllbbb', 'hamza@example.com', 'hashed4', 'agent', '0645678123', 'Agadir', '2025-11-26 20:00:13'),
(6, 'Mouad Limouni', 'mouad@example.com', 'hashed5', 'agent', '0656781234', 'Tanger', '2025-11-26 20:00:13'),
(7, 'Sara Berrada', 'sara.agent@example.com', 'hashed6', 'agent', '0667812345', 'Fès', '2025-11-26 20:00:13');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
