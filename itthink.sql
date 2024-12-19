-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 19, 2024 at 10:02 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `itthink`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id_categorie` int NOT NULL,
  `nom_categorie` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id_categorie`, `nom_categorie`) VALUES
(1, 'full_stack'),
(3, 'cory_writing'),
(4, 'transating'),
(5, 'virtual_assistant'),
(8, 'data_entry');

-- --------------------------------------------------------

--
-- Table structure for table `freelances`
--

CREATE TABLE `freelances` (
  `id_freelance` int NOT NULL,
  `nom_freelance` varchar(255) NOT NULL,
  `competences` text,
  `id_utilisateur` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `freelances`
--

INSERT INTO `freelances` (`id_freelance`, `nom_freelance`, `competences`, `id_utilisateur`) VALUES
(4, 'ayoub', 'developement web', 8);

-- --------------------------------------------------------

--
-- Table structure for table `offres`
--

CREATE TABLE `offres` (
  `id_offre` int NOT NULL,
  `montant` decimal(10,2) NOT NULL,
  `delai` int NOT NULL,
  `id_freelance` int DEFAULT NULL,
  `id_projet` int DEFAULT NULL,
  `status` varchar(55) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `offres`
--

INSERT INTO `offres` (`id_offre`, `montant`, `delai`, `id_freelance`, `id_projet`, `status`) VALUES
(5, '120.00', 5, 4, 4, '');

-- --------------------------------------------------------

--
-- Table structure for table `projets`
--

CREATE TABLE `projets` (
  `id_projet` int NOT NULL,
  `titre_projet` varchar(255) NOT NULL,
  `DESCRIPTION` text,
  `id_categorie` int DEFAULT NULL,
  `id_sous_categorie` int DEFAULT NULL,
  `id_utilisateur` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `projets`
--

INSERT INTO `projets` (`id_projet`, `titre_projet`, `DESCRIPTION`, `id_categorie`, `id_sous_categorie`, `id_utilisateur`) VALUES
(4, 'Application mobile', 'Une application mobile pour une agence de Marketing Digital', 1, 1, 9);

-- --------------------------------------------------------

--
-- Table structure for table `souscategorie`
--

CREATE TABLE `souscategorie` (
  `id_sous_categorie` int NOT NULL,
  `nom_sous_categorie` varchar(255) NOT NULL,
  `id_categorie` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `souscategorie`
--

INSERT INTO `souscategorie` (`id_sous_categorie`, `nom_sous_categorie`, `id_categorie`) VALUES
(1, 'front_end', 1),
(2, 'back_end', 1),
(4, 'arabic', 4),
(5, 'english', 4),
(6, 'back_office', 5),
(7, 'front_office', 5),
(8, 'direct-response', 3),
(9, 'marketing', 3),
(13, 'Pack_microsoft', 8);

-- --------------------------------------------------------

--
-- Table structure for table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id_utilisateur` int NOT NULL,
  `nom_utilisateur` varchar(255) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` varchar(55) DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id_utilisateur`, `nom_utilisateur`, `mot_de_passe`, `email`, `role`) VALUES
(7, 'admin', '$2y$10$n8l3H2DRtAQgcZG6T5zGYemOWbUUKljW6/1vvrS5ef9/dfc49Ti1O', 'admin@admin.com', 'admin'),
(8, 'ayoub', '$2y$10$JJPOpyfojdKLEnbSTAakOudMgn.0kyUkPoyUVYJquJcvGC/1Liu6q', 'ayoub@gmail.com', 'freelancer'),
(9, 'jebbouri', '$2y$10$/HCGdv/zY/S2YFmYCJ.JXOHW0rmPSbrb62Dn6bv8H2gu1z.D4MF5C', 'jebbouri@gmail.com', 'user'),
(10, 'oussama', '$2y$10$atyvS9XZoLSRodnIGqwBZ.Yd8DqHwtH1nxydGUhumhMX5u1NSLVRe', 'oussama@gmail.com', 'user'),
(12, 'rashida', '$2y$10$F161Rmr4ogkWSq.wrAC46uUA7s5UumlV0o4kzJ8vKGzoUnRO3dxpi', 'rashida@gmail.com', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id_categorie`);

--
-- Indexes for table `freelances`
--
ALTER TABLE `freelances`
  ADD PRIMARY KEY (`id_freelance`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Indexes for table `offres`
--
ALTER TABLE `offres`
  ADD PRIMARY KEY (`id_offre`),
  ADD KEY `id_freelance` (`id_freelance`),
  ADD KEY `id_projet` (`id_projet`);

--
-- Indexes for table `projets`
--
ALTER TABLE `projets`
  ADD PRIMARY KEY (`id_projet`),
  ADD KEY `id_categorie` (`id_categorie`),
  ADD KEY `id_sous_categorie` (`id_sous_categorie`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Indexes for table `souscategorie`
--
ALTER TABLE `souscategorie`
  ADD PRIMARY KEY (`id_sous_categorie`),
  ADD KEY `id_categorie` (`id_categorie`);

--
-- Indexes for table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id_utilisateur`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id_categorie` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `freelances`
--
ALTER TABLE `freelances`
  MODIFY `id_freelance` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `offres`
--
ALTER TABLE `offres`
  MODIFY `id_offre` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `projets`
--
ALTER TABLE `projets`
  MODIFY `id_projet` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `souscategorie`
--
ALTER TABLE `souscategorie`
  MODIFY `id_sous_categorie` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id_utilisateur` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `freelances`
--
ALTER TABLE `freelances`
  ADD CONSTRAINT `freelances_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `offres`
--
ALTER TABLE `offres`
  ADD CONSTRAINT `offres_ibfk_1` FOREIGN KEY (`id_freelance`) REFERENCES `freelances` (`id_freelance`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `offres_ibfk_2` FOREIGN KEY (`id_projet`) REFERENCES `projets` (`id_projet`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `projets`
--
ALTER TABLE `projets`
  ADD CONSTRAINT `projets_ibfk_1` FOREIGN KEY (`id_categorie`) REFERENCES `categories` (`id_categorie`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `projets_ibfk_2` FOREIGN KEY (`id_sous_categorie`) REFERENCES `souscategorie` (`id_sous_categorie`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `projets_ibfk_3` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `souscategorie`
--
ALTER TABLE `souscategorie`
  ADD CONSTRAINT `souscategorie_ibfk_1` FOREIGN KEY (`id_categorie`) REFERENCES `categories` (`id_categorie`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
