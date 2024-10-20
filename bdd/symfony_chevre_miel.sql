-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : dim. 20 oct. 2024 à 14:32
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `symfony_chevre_miel`
--

-- --------------------------------------------------------

--
-- Structure de la table `blogs`
--

CREATE TABLE `blogs` (
  `id` int(11) NOT NULL,
  `utilisateur_id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `ingredients` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT '(DC2Type:json)' CHECK (json_valid(`ingredients`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `blogs`
--

INSERT INTO `blogs` (`id`, `utilisateur_id`, `title`, `description`, `file_name`, `ingredients`) VALUES
(10, 2, 'Pizza Dominos', 'La pizza était bonne, même si elle était un peux grasse, pour le prix on peux trouver mieux !', '16993077-671258fe68974.png', '[\"fromage\",\"pepperoni\",\"champignon\"]'),
(11, 2, 'pizza reine Paris', 'C\'était trop bon ! j\'y retournerais volontier dans cette pizzeria, c\'etait juste incroyabe!  je recommande !!!!!', 'reine-67125a0d922a8.jpg', '[\"champignon\",\"jambon\",\"sauce tomate\",\"olive\"]'),
(12, 7, 'Plus jamais dans cet pizzeria !!!', 'La pizza n\'est pas garnie du tout, je trouve ça inacceptable !!! je recommande pas du tout ce restaraurant il est à éviter !!!', '20221129-1054-img-8712-6e64c-67125c9510a8c.jpg', '[\"fromage\",\"champignon\",\"brocoli\",\"jambon\"]'),
(16, 10, 'Chèvre miel, une pépite !', 'La pizza est super bonne, bonne dose de miel, le fromage de chèvre est super bon, la pate à pizza incroyable, bref je recommande fortement cette pizza de la Madonne à Hennbont !!!', 'la-chevre-miel-pizzeria-carcassonne-la-vieille-italie-67143d2dd9dad.jpg', '[\"chevre\",\"miel\",\"olive\"]'),
(17, 3, 'Pizza marie Blachère', 'Assez bonne, le rapport qualité prix de la pizza jambon est correcte, elle ne vaux pas une d\'un restaurant, mais pour le prix la pizza reste super correcte', '20170405201315jpg-5979a9463ed8c-67144708a2f72.jpg', '[\"jambon\",\"fromage\",\"olive\",\"sauce tomate\"]');

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20241017121118', '2024-10-17 14:11:45', 21),
('DoctrineMigrations\\Version20241017223526', '2024-10-18 00:38:20', 83),
('DoctrineMigrations\\Version20241018001926', '2024-10-18 02:19:30', 18),
('DoctrineMigrations\\Version20241018002154', '2024-10-18 02:22:04', 9),
('DoctrineMigrations\\Version20241018122737', '2024-10-18 14:29:29', 50),
('DoctrineMigrations\\Version20241018130401', '2024-10-18 15:04:07', 9);

-- --------------------------------------------------------

--
-- Structure de la table `messenger_messages`
--

CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL,
  `body` longtext NOT NULL,
  `headers` longtext NOT NULL,
  `queue_name` varchar(190) NOT NULL,
  `created_at` datetime NOT NULL,
  `available_at` datetime NOT NULL,
  `delivered_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mdp` varchar(255) NOT NULL,
  `surnom` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id`, `email`, `mdp`, `surnom`) VALUES
(2, 'Marcelle@gmail.com', '1234', 'Marcelle'),
(3, 'test2@test2.test2', '1234', 'Gabin'),
(4, 'test3@test3.test3', '1234', 'test3'),
(7, 'test3@test.test', '1234', 'Jean'),
(10, 'victorhugo@pizza.fr', 'victorhugo', 'Victor Hugo'),
(11, 'gabinlegrand56@gmail.com', '1234', 'Gabin');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_F41BCA70FB88E14F` (`utilisateur_id`);

--
-- Index pour la table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `blogs`
--
ALTER TABLE `blogs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `blogs`
--
ALTER TABLE `blogs`
  ADD CONSTRAINT `FK_F41BCA70FB88E14F` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
