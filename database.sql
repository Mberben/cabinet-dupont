-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : mar. 20 oct. 2026 à 11:13
-- Version du serveur : 10.4.28-MariaDB
-- Version de PHP : 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `cabinet_dupont`
--

-- --------------------------------------------------------

--
-- Structure de la table `appointments`
--

DROP TABLE IF EXISTS `appointments`;
CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `appointment_date` datetime NOT NULL,
  `status` enum('pending','confirmed','cancelled') NOT NULL DEFAULT 'pending',
  `message` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `patient_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `appointments`
--

INSERT INTO `appointments` (`id`, `appointment_date`, `status`, `message`, `created_at`, `patient_id`, `service_id`) VALUES
(1, '2026-10-12 09:30:00', 'pending', 'Douleur à une molaire', '2026-10-05 17:39:17', 1, 1),
(2, '2026-10-19 09:00:00', 'pending', NULL, '2026-10-19 00:04:07', 1, 1),
(3, '2026-10-19 09:30:00', 'confirmed', NULL, '2026-10-19 00:05:25', 1, 1),
(4, '2026-10-20 09:00:00', 'pending', NULL, '2026-10-19 09:46:23', 1, 1),
(5, '2026-10-20 11:00:00', 'cancelled', NULL, '2026-10-19 09:46:36', 1, 3),
(6, '2026-10-21 09:00:00', 'confirmed', NULL, '2026-10-20 10:06:19', 1, 1),
(8, '2026-10-23 13:00:00', 'cancelled', NULL, '2026-10-20 10:12:35', 1, 3);

-- --------------------------------------------------------

--
-- Structure de la table `news`
--

DROP TABLE IF EXISTS `news`;
CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `published_at` datetime NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `news`
--

INSERT INTO `news` (`id`, `title`, `content`, `image`, `published_at`, `user_id`) VALUES
(1, 'Le cabinet s\'équipe d\'une radio 3D', 'Nous disposons désormais d\'un appareil de radiographie 3D, pour des diagnostics plus précis et moins irradiants.', 'img_6acdead8986665.50281282.jpg', '2026-10-12 11:58:03', 2);

-- --------------------------------------------------------

--
-- Structure de la table `opening_hours`
--

DROP TABLE IF EXISTS `opening_hours`;
CREATE TABLE `opening_hours` (
  `id` int(11) NOT NULL,
  `day_of_week` tinyint(4) NOT NULL CHECK (`day_of_week` between 1 and 7),
  `open_time` time DEFAULT NULL,
  `close_time` time DEFAULT NULL,
  `is_closed` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `opening_hours`
--

INSERT INTO `opening_hours` (`id`, `day_of_week`, `open_time`, `close_time`, `is_closed`) VALUES
(1, 1, '08:00:00', '12:00:00', 0),
(2, 1, '14:00:00', '18:00:00', 0),
(3, 2, '08:00:00', '12:00:00', 0),
(4, 2, '14:00:00', '18:00:00', 0),
(5, 3, '08:00:00', '12:00:00', 0),
(6, 4, '08:00:00', '12:00:00', 0),
(7, 4, '14:00:00', '18:00:00', 0),
(8, 5, '08:00:00', '18:00:00', 0),
(10, 6, '09:00:00', '12:00:00', 0),
(11, 7, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Structure de la table `services`
--

DROP TABLE IF EXISTS `services`;
CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `duration` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `services`
--

INSERT INTO `services` (`id`, `name`, `description`, `duration`, `image`) VALUES
(1, 'Soins dentaires courants', 'Consultation, détartrage, traitement des caries.', 30, NULL),
(2, 'Orthodontie', 'Correction de l\'alignement des dents.', 45, NULL),
(3, 'Implantologie', 'Pose d\'implants dentaires.', 60, NULL),
(7, 'Chirurgie dentaire', 'Extractions, chirurgie des dents de sagesse et petites interventions.', 70, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `role` enum('admin','staff','patient') NOT NULL DEFAULT 'patient',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `email`, `password`, `phone`, `role`, `created_at`) VALUES
(1, 'Julie', 'Martin', 'julie.martin@example.com', '$2y$10$fSpkn8qeLeFNnqfwEhdIwuiBpsM5HG7xksPQcwiL0DckeQBXtCwZa', '0612345678', 'patient', '2026-10-05 17:39:17'),
(2, 'Jean', 'Dupont', 'dr.dupont@example.com', '$2y$10$YegVLp7coWKpQpDna0Yw6eRqzsWp1mTnGLkd7dGWszB0QJxoLcUr.', NULL, 'admin', '2026-10-08 14:58:25');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_appointment_patient` (`patient_id`),
  ADD KEY `fk_appointment_service` (`service_id`);

--
-- Index pour la table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_news_user` (`user_id`);

--
-- Index pour la table `opening_hours`
--
ALTER TABLE `opening_hours`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `opening_hours`
--
ALTER TABLE `opening_hours`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `fk_appointment_patient` FOREIGN KEY (`patient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_appointment_service` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`);

--
-- Contraintes pour la table `news`
--
ALTER TABLE `news`
  ADD CONSTRAINT `fk_news_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
