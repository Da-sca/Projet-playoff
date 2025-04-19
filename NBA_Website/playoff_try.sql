-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 26, 2025 at 09:25 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `playoff_try`
--

-- --------------------------------------------------------

--
-- Table structure for table `arbitres`
--

CREATE TABLE `arbitres` (
  `id_arbitre` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `age` int(11) DEFAULT NULL CHECK (`age` > 18),
  `experience` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `arbitres`
--

INSERT INTO `arbitres` (`id_arbitre`, `nom`, `age`, `experience`) VALUES
(1, 'Lamine', 34, 3),
(2, 'Jean marc', 45, 23);

-- --------------------------------------------------------

--
-- Table structure for table `equipe`
--

CREATE TABLE `equipe` (
  `id_equipe` int(11) NOT NULL,
  `nom_equipe` varchar(256) NOT NULL,
  `ville` varchar(256) NOT NULL,
  `nombre_de_victoire` int(5) UNSIGNED NOT NULL DEFAULT 0,
  `nombre_de_defaite` int(5) UNSIGNED NOT NULL DEFAULT 0,
  `Conference` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `equipe`
--

INSERT INTO `equipe` (`id_equipe`, `nom_equipe`, `ville`, `nombre_de_victoire`, `nombre_de_defaite`, `Conference`) VALUES
(1, 'Lakers', 'Los Angeles', 49, 34, 0),
(2, 'Warriors', 'San Francisco', 41, 25, 0),
(3, 'Celtics', 'Boston', 51, 15, 1),
(4, 'Bulls', 'Chicago', 36, 30, 1),
(5, 'Miami Heat', 'Miami', 43, 22, 1),
(6, 'Knicks', 'New York', 26, 60, 1),
(7, 'Hawks', 'Atlanta', 47, 20, 1),
(8, 'Nuggets', 'Denver', 33, 30, 0),
(9, 'Clippers', 'LA', 28, 43, 0),
(10, 'Bucks', 'Milwaukee', 51, 10, 1),
(11, 'Timberwolves', 'Minnesota', 31, 15, 0),
(12, 'Phoenix Suns', 'Arizona', 33, 19, 0),
(13, 'Sacramento Kings', 'Californie', 44, 23, 0),
(14, 'Orlando magic', 'Orlando', 26, 30, 1),
(15, 'Seattle Supersonics', 'Seattle', 31, 40, 0),
(16, 'Vancouver Grizzlies', 'Vancouver', 24, 34, 1);

-- --------------------------------------------------------

--
-- Table structure for table `joueurs`
--

CREATE TABLE `joueurs` (
  `id_joueur` int(11) NOT NULL,
  `nom` varchar(256) NOT NULL,
  `age` int(11) NOT NULL,
  `Id_equipe` int(11) NOT NULL,
  `Passe` int(11) NOT NULL,
  `Points` int(11) NOT NULL,
  `faute` int(11) NOT NULL,
  `duel_gagne` int(11) NOT NULL,
  `Titulaire` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `joueurs`
--

INSERT INTO `joueurs` (`id_joueur`, `nom`, `age`, `Id_equipe`, `Passe`, `Points`, `faute`, `duel_gagne`, `Titulaire`) VALUES
(10, 'LeBron James', 39, 1, 200, 1500, 50, 100, 0),
(11, 'Anthony Davis', 31, 1, 180, 1400, 40, 90, 1),
(12, 'Dog', 28, 1, 160, 1200, 30, 80, 0),
(13, 'Austin Reaves', 26, 1, 140, 1100, 20, 70, 1),
(14, 'Rui Hachimura', 26, 1, 130, 1000, 25, 65, 1),
(15, 'Jarred Vanderbilt', 25, 1, 120, 900, 15, 60, 1),
(16, 'Gabe Vincent', 28, 1, 110, 850, 10, 55, 1),
(17, 'Christian Wood', 28, 1, 100, 800, 20, 50, 0),
(18, 'Cam Reddish', 25, 1, 90, 750, 15, 45, 0),
(19, 'Max Christie', 22, 1, 85, 700, 10, 40, 0),
(20, 'Jaxson Hayes', 26, 1, 80, 650, 10, 35, 0),
(21, 'Taurean Prince', 30, 1, 75, 600, 8, 30, 0),
(22, 'Colin Castleton', 24, 1, 70, 550, 7, 25, 0),
(23, 'Alex Fudge', 22, 1, 65, 500, 5, 20, 0),
(24, 'Scotty Pippen Jr.', 24, 1, 60, 450, 5, 15, 0),
(25, 'Stephen Curry', 36, 2, 180, 1600, 30, 90, 1),
(26, 'Klay Thompson', 34, 2, 150, 1300, 35, 85, 1),
(27, 'Draymond Green', 34, 2, 140, 1000, 45, 95, 1),
(28, 'Andrew Wiggins', 29, 2, 130, 1200, 25, 80, 1),
(29, 'Chris Paul', 39, 2, 125, 1100, 20, 75, 1),
(30, 'Kevon Looney', 28, 2, 110, 900, 18, 70, 0),
(31, 'Jonathan Kuminga', 22, 2, 105, 850, 15, 65, 0),
(32, 'Moses Moody', 23, 2, 100, 800, 10, 60, 0),
(33, 'Gary Payton II', 31, 2, 95, 750, 10, 55, 0),
(34, 'Dario Saric', 30, 2, 90, 700, 8, 50, 0),
(35, 'Cory Joseph', 33, 2, 85, 650, 7, 45, 0),
(36, 'Trayce Jackson-Davis', 24, 2, 80, 600, 6, 40, 0),
(37, 'Brandin Podziemski', 22, 2, 75, 550, 5, 35, 0),
(38, 'Lester Quinones', 24, 2, 70, 500, 5, 30, 0),
(39, 'Usman Garuba', 23, 2, 65, 450, 5, 25, 0),
(40, 'Jayson Tatum', 26, 3, 150, 1400, 40, 80, 0),
(41, 'Jaylen Brown', 27, 3, 140, 1300, 35, 75, 0),
(42, 'Kristaps Porzingis', 29, 3, 130, 1200, 30, 70, 0),
(43, 'Derrick White', 30, 3, 120, 1100, 25, 65, 0),
(44, 'Jrue Holiday', 34, 3, 115, 1050, 22, 60, 0),
(45, 'Al Horford', 38, 3, 110, 1000, 20, 55, 0),
(46, 'Sam Hauser', 26, 3, 105, 950, 18, 50, 0),
(47, 'Payton Pritchard', 26, 3, 100, 900, 15, 45, 0),
(48, 'Luke Kornet', 29, 3, 95, 850, 12, 40, 0),
(49, 'Oshae Brissett', 26, 3, 90, 800, 10, 35, 0),
(50, 'Svi Mykhailiuk', 27, 3, 85, 750, 8, 30, 0),
(51, 'Neemias Queta', 25, 3, 80, 700, 7, 25, 0),
(52, 'JD Davison', 23, 3, 75, 650, 6, 20, 0),
(53, 'Jordan Walsh', 22, 3, 70, 600, 5, 15, 0),
(54, 'Dalano Banton', 24, 3, 65, 550, 4, 10, 0),
(55, 'Zach LaVine', 29, 4, 130, 1300, 35, 70, 0),
(56, 'DeMar DeRozan', 34, 4, 125, 1250, 32, 65, 0),
(57, 'Nikola Vucevic', 33, 4, 120, 1200, 30, 60, 0),
(58, 'Patrick Williams', 22, 4, 110, 1100, 28, 55, 0),
(59, 'Coby White', 24, 4, 105, 1050, 25, 50, 0),
(60, 'Ayo Dosunmu', 24, 4, 100, 1000, 22, 45, 0),
(61, 'Andre Drummond', 30, 4, 95, 950, 20, 40, 0),
(62, 'Jevon Carter', 28, 4, 90, 900, 18, 35, 0),
(63, 'Dalen Terry', 23, 4, 85, 850, 15, 30, 0),
(64, 'Torrey Craig', 33, 4, 80, 800, 12, 25, 0),
(65, 'Julian Phillips', 22, 4, 75, 750, 10, 20, 0),
(66, 'Terry Taylor', 24, 4, 70, 700, 8, 15, 0),
(67, 'Onuralp Bitim', 25, 4, 65, 650, 7, 10, 0),
(68, 'Adama Sanogo', 23, 4, 60, 600, 5, 8, 0),
(69, 'Carlik Jones', 25, 4, 55, 550, 4, 5, 0),
(70, 'Jimmy Butler', 34, 5, 140, 1300, 35, 75, 0),
(71, 'Bam Adebayo', 26, 5, 130, 1200, 30, 70, 0),
(72, 'Tyler Herro', 24, 5, 125, 1150, 28, 65, 0),
(73, 'Duncan Robinson', 29, 5, 120, 1100, 25, 60, 0),
(74, 'Kyle Lowry', 38, 5, 115, 1050, 22, 55, 0),
(75, 'Caleb Martin', 28, 5, 110, 1000, 20, 50, 0),
(76, 'Jaime Jaquez Jr.', 23, 5, 105, 950, 18, 45, 0),
(77, 'Josh Richardson', 30, 5, 100, 900, 15, 40, 0),
(78, 'Kevin Love', 35, 5, 95, 850, 12, 35, 0),
(79, 'Nikola Jović', 21, 5, 90, 800, 10, 30, 0),
(80, 'Orlando Robinson', 24, 5, 85, 750, 8, 25, 0),
(81, 'Thomas Bryant', 27, 5, 80, 700, 7, 20, 0),
(82, 'Haywood Highsmith', 26, 5, 75, 650, 6, 15, 0),
(83, 'Dru Smith', 25, 5, 70, 600, 5, 10, 0),
(84, 'RJ Hampton', 23, 5, 65, 550, 4, 5, 0),
(85, 'Julius Randle', 29, 6, 140, 1300, 35, 75, 0),
(86, 'Jalen Brunson', 27, 6, 130, 1200, 30, 70, 0),
(87, 'RJ Barrett', 24, 6, 125, 1150, 28, 65, 0),
(88, 'Mitchell Robinson', 26, 6, 120, 1100, 25, 60, 0),
(89, 'Josh Hart', 29, 6, 115, 1050, 22, 55, 0),
(90, 'Donte DiVincenzo', 27, 6, 110, 1000, 20, 50, 0),
(91, 'Immanuel Quickley', 25, 6, 105, 950, 18, 45, 0),
(92, 'Isaiah Hartenstein', 26, 6, 100, 900, 15, 40, 0),
(93, 'Quentin Grimes', 24, 6, 95, 850, 12, 35, 0),
(94, 'Evan Fournier', 31, 6, 90, 800, 10, 30, 0),
(95, 'Jericho Sims', 25, 6, 85, 750, 8, 25, 0),
(96, 'Miles McBride', 24, 6, 80, 700, 7, 20, 0),
(97, 'Ryan Arcidiacono', 30, 6, 75, 650, 6, 15, 0),
(98, 'Charlie Brown Jr.', 26, 6, 70, 600, 5, 10, 0),
(99, 'Jacob Toppin', 23, 6, 65, 550, 4, 5, 0),
(100, 'Trae Young', 25, 7, 150, 1400, 40, 80, 0),
(101, 'Dejounte Murray', 28, 7, 140, 1300, 35, 75, 0),
(102, 'Bogdan Bogdanović', 31, 7, 130, 1200, 30, 70, 0),
(103, 'Clint Capela', 29, 7, 120, 1100, 25, 65, 0),
(104, 'DeAndre Hunter', 26, 7, 115, 1050, 22, 60, 0),
(105, 'Saddiq Bey', 25, 7, 110, 1000, 20, 55, 0),
(106, 'Jalen Johnson', 23, 7, 105, 950, 18, 50, 0),
(107, 'Onyeka Okongwu', 24, 7, 100, 900, 15, 45, 0),
(108, 'Patty Mills', 35, 7, 95, 850, 12, 40, 0),
(109, 'Garrison Mathews', 27, 7, 90, 800, 10, 35, 0),
(110, 'Trent Forrest', 25, 7, 85, 750, 8, 30, 0),
(111, 'Wesley Matthews', 38, 7, 80, 700, 7, 25, 0),
(112, 'Vit Krejci', 24, 7, 75, 650, 6, 20, 0),
(113, 'Tyrese Martin', 23, 7, 70, 600, 5, 15, 0),
(114, 'Seth Lundy', 22, 7, 65, 550, 4, 10, 0),
(115, 'Nikola Jokić', 29, 8, 180, 1600, 30, 90, 0),
(116, 'Jamal Murray', 27, 8, 150, 1300, 35, 85, 0),
(117, 'Michael Porter Jr.', 26, 8, 140, 1200, 30, 80, 0),
(118, 'Aaron Gordon', 28, 8, 130, 1100, 25, 75, 0),
(119, 'Kentavious Caldwell-Pope', 31, 8, 125, 1050, 22, 70, 0),
(120, 'Reggie Jackson', 34, 8, 110, 1000, 20, 65, 0),
(121, 'Christian Braun', 23, 8, 105, 950, 18, 60, 0),
(122, 'Zeke Nnaji', 24, 8, 100, 900, 15, 55, 0),
(123, 'Justin Holiday', 35, 8, 95, 850, 12, 50, 0),
(124, 'Peyton Watson', 22, 8, 90, 800, 10, 45, 0),
(125, 'Julian Strawther', 22, 8, 85, 750, 8, 40, 0),
(126, 'Vlatko Čančar', 27, 8, 80, 700, 7, 35, 0),
(127, 'DeAndre Jordan', 35, 8, 75, 650, 6, 30, 0),
(128, 'Hunter Tyson', 24, 8, 70, 600, 5, 25, 0),
(129, 'Braxton Key', 26, 8, 65, 550, 4, 20, 0),
(130, 'Kawhi Leonard', 32, 9, 150, 1400, 35, 80, 1),
(131, 'Paul George', 33, 9, 140, 1300, 30, 75, 1),
(132, 'James Harden', 34, 9, 130, 1200, 25, 70, 1),
(133, 'Russell Westbrook', 35, 9, 120, 1100, 22, 65, 1),
(134, 'Ivica Zubac', 27, 9, 115, 1050, 20, 60, 1),
(135, 'Norman Powell', 31, 9, 110, 1000, 18, 55, 0),
(136, 'Terance Mann', 28, 9, 105, 950, 15, 50, 0),
(137, 'Bones Hyland', 24, 9, 100, 900, 12, 45, 0),
(138, 'Mason Plumlee', 34, 9, 95, 850, 10, 40, 0),
(139, 'Amir Coffey', 27, 9, 90, 800, 8, 35, 0),
(140, 'Brandon Boston Jr.', 23, 9, 85, 750, 7, 30, 0),
(141, 'Kobe Brown', 22, 9, 80, 700, 6, 25, 0),
(142, 'Jason Preston', 25, 9, 75, 650, 5, 20, 0),
(143, 'Moussa Diabaté', 23, 9, 70, 600, 4, 15, 0),
(144, 'Xavier Moon', 29, 9, 65, 550, 3, 10, 0),
(145, 'Giannis Antetokounmpo', 29, 10, 180, 1600, 30, 90, 0),
(146, 'Damian Lillard', 34, 10, 150, 1300, 35, 85, 0),
(147, 'Khris Middleton', 33, 10, 140, 1200, 30, 80, 0),
(148, 'Brook Lopez', 36, 10, 130, 1100, 25, 75, 0),
(149, 'Bobby Portis', 29, 10, 125, 1050, 22, 70, 0),
(150, 'Jae Crowder', 34, 10, 110, 1000, 20, 65, 0),
(151, 'Malik Beasley', 28, 10, 105, 950, 18, 60, 0),
(152, 'Pat Connaughton', 31, 10, 100, 900, 15, 55, 0),
(153, 'MarJon Beauchamp', 23, 10, 95, 850, 12, 50, 0),
(154, 'Cameron Payne', 30, 10, 90, 800, 10, 45, 0),
(155, 'Robin Lopez', 36, 10, 85, 750, 8, 40, 0),
(156, 'Thanasis Antetokounmpo', 31, 10, 80, 700, 7, 35, 0),
(157, 'AJ Green', 24, 10, 75, 650, 6, 30, 0),
(158, 'Chris Livingston', 22, 10, 70, 600, 5, 25, 0),
(159, 'TyTy Washington', 23, 10, 65, 550, 4, 20, 0),
(160, 'Anthony Edwards', 22, 11, 150, 1200, 30, 80, 0),
(161, 'Karl-Anthony Towns', 28, 11, 140, 1300, 25, 85, 0),
(162, 'Rudy Gobert', 31, 11, 130, 1100, 20, 75, 0),
(163, 'Mike Conley', 36, 11, 120, 1000, 18, 70, 0),
(164, 'Jaden McDaniels', 23, 11, 110, 900, 15, 65, 0),
(165, 'Devin Booker', 27, 12, 160, 1400, 35, 85, 0),
(166, 'Kevin Durant', 35, 12, 150, 1300, 30, 80, 0),
(167, 'Bradley Beal', 30, 12, 140, 1200, 25, 75, 0),
(168, 'Deandre Ayton', 25, 12, 130, 1100, 20, 70, 0),
(169, 'Chris Paul', 38, 12, 120, 1000, 18, 65, 0),
(170, 'De\'Aaron Fox', 26, 13, 150, 1300, 30, 80, 0),
(171, 'Domantas Sabonis', 28, 13, 140, 1200, 25, 75, 0),
(172, 'Harrison Barnes', 31, 13, 130, 1100, 20, 70, 0),
(173, 'Keegan Murray', 23, 13, 120, 1000, 15, 65, 0),
(174, 'Malik Monk', 26, 13, 110, 900, 18, 60, 0),
(190, 'Paolo Banchero', 21, 14, 150, 1200, 25, 70, 0),
(191, 'Franz Wagner', 22, 14, 140, 1100, 20, 65, 0),
(192, 'Wendell Carter Jr.', 24, 14, 130, 1000, 18, 60, 0),
(193, 'Jalen Suggs', 22, 14, 120, 950, 15, 55, 0),
(194, 'Markelle Fultz', 25, 14, 110, 900, 12, 50, 0),
(195, 'Cole Anthony', 23, 14, 100, 850, 10, 45, 0),
(196, 'Jonathan Isaac', 26, 14, 90, 800, 8, 40, 0),
(197, 'Moritz Wagner', 26, 14, 85, 750, 7, 35, 0),
(198, 'Gary Harris', 29, 14, 80, 700, 6, 30, 0),
(199, 'Chuma Okeke', 25, 14, 75, 650, 5, 25, 0),
(200, 'Ray Allen', 48, 15, 160, 1400, 30, 80, 0),
(201, 'Gary Payton', 55, 15, 150, 1300, 25, 75, 0),
(202, 'Rashard Lewis', 44, 15, 140, 1200, 20, 70, 0),
(203, 'Shawn Kemp', 54, 15, 130, 1100, 18, 65, 0),
(204, 'Detlef Schrempf', 61, 15, 120, 1000, 15, 60, 0),
(205, 'Nate McMillan', 59, 15, 110, 950, 12, 55, 0),
(206, 'Hersey Hawkins', 57, 15, 100, 900, 10, 50, 0),
(207, 'Vin Baker', 52, 15, 90, 850, 8, 45, 0),
(208, 'Sam Perkins', 62, 15, 85, 800, 7, 40, 0),
(209, 'Dale Ellis', 63, 15, 80, 750, 6, 35, 0),
(210, 'Shareef Abdur-Rahim', 47, 16, 150, 1300, 25, 75, 0),
(211, 'Mike Bibby', 45, 16, 140, 1200, 20, 70, 0),
(212, 'Stromile Swift', 44, 16, 130, 1100, 18, 65, 0),
(213, 'Bryant Reeves', 50, 16, 120, 1000, 15, 60, 0),
(214, 'Jason Williams', 48, 16, 110, 950, 12, 55, 0),
(215, 'Pau Gasol', 43, 16, 100, 900, 10, 50, 0),
(216, 'Lorenzen Wright', 48, 16, 90, 850, 8, 45, 0),
(217, 'Tony Massenburg', 56, 16, 85, 800, 7, 40, 0),
(218, 'Doug West', 57, 16, 80, 750, 6, 35, 0),
(219, 'Greg Anthony', 56, 16, 75, 700, 5, 30, 0);

-- --------------------------------------------------------

--
-- Table structure for table `joueurs_stats`
--

CREATE TABLE `joueurs_stats` (
  `id` int(11) NOT NULL,
  `id_joueur` int(11) NOT NULL,
  `saison` year(4) NOT NULL,
  `points_par_match` int(11) NOT NULL,
  `passes_decisives` int(11) NOT NULL,
  `rebonds` int(11) NOT NULL,
  `interceptions` int(11) NOT NULL,
  `contres` int(11) NOT NULL,
  `id_match` int(11) NOT NULL,
  `Fautes` int(11) NOT NULL,
  `id_round` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `joueurs_stats`
--

INSERT INTO `joueurs_stats` (`id`, `id_joueur`, `saison`, `points_par_match`, `passes_decisives`, `rebonds`, `interceptions`, `contres`, `id_match`, `Fautes`, `id_round`) VALUES
(666, 10, '0000', 34, 45, 54, 23, 0, 766, 0, 0),
(667, 11, '0000', 23, 334, 4, 34, 0, 766, 0, 0),
(668, 12, '0000', 23, 34, 34, 34, 0, 766, 0, 0),
(669, 13, '0000', 23, 33, 34, 34, 0, 766, 0, 0),
(670, 14, '0000', 0, 0, 0, 0, 0, 766, 0, 0),
(671, 15, '0000', 0, 0, 0, 0, 0, 766, 0, 0),
(672, 16, '0000', 0, 0, 0, 0, 0, 766, 0, 0),
(673, 17, '0000', 0, 0, 0, 0, 0, 766, 0, 0),
(674, 18, '0000', 0, 0, 0, 0, 0, 766, 0, 0),
(675, 19, '0000', 0, 0, 0, 0, 0, 766, 0, 0),
(676, 20, '0000', 0, 0, 0, 0, 0, 766, 0, 0),
(677, 21, '0000', 0, 0, 0, 0, 0, 766, 0, 0),
(678, 22, '0000', 0, 0, 0, 0, 0, 766, 0, 0),
(679, 23, '0000', 0, 0, 0, 0, 0, 766, 0, 0),
(680, 24, '0000', 0, 0, 0, 0, 0, 766, 0, 0),
(681, 130, '0000', 0, 0, 0, 0, 0, 766, 0, 0),
(682, 131, '0000', 0, 0, 0, 0, 0, 766, 0, 0),
(683, 132, '0000', 0, 0, 0, 0, 0, 766, 0, 0),
(684, 133, '0000', 0, 0, 0, 0, 0, 766, 0, 0),
(685, 134, '0000', 0, 0, 0, 0, 0, 766, 0, 0),
(686, 135, '0000', 0, 0, 0, 0, 0, 766, 0, 0),
(687, 136, '0000', 0, 0, 0, 0, 0, 766, 0, 0),
(688, 137, '0000', 0, 0, 0, 0, 0, 766, 0, 0),
(689, 138, '0000', 0, 0, 0, 0, 0, 766, 0, 0),
(690, 139, '0000', 0, 0, 0, 0, 0, 766, 0, 0),
(691, 140, '0000', 0, 0, 0, 0, 0, 766, 0, 0),
(692, 141, '0000', 0, 0, 0, 0, 0, 766, 0, 0),
(693, 142, '0000', 0, 0, 0, 0, 0, 766, 0, 0),
(694, 143, '0000', 0, 0, 0, 0, 0, 766, 0, 0),
(695, 144, '0000', 0, 0, 0, 0, 0, 766, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `matches`
--

CREATE TABLE `matches` (
  `id_match` int(11) NOT NULL,
  `Location_match` varchar(256) NOT NULL,
  `Equipe1` int(11) DEFAULT NULL,
  `Equipe2` int(11) DEFAULT NULL,
  `id_arbitre` int(11) DEFAULT NULL,
  `Date_match` date NOT NULL,
  `Equipe1_victorieuse` int(11) NOT NULL,
  `Equipe2_victorieuse` int(11) NOT NULL,
  `Conference` tinyint(1) NOT NULL DEFAULT 0,
  `id_round` int(11) NOT NULL DEFAULT 0,
  `phase` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `matches`
--

INSERT INTO `matches` (`id_match`, `Location_match`, `Equipe1`, `Equipe2`, `id_arbitre`, `Date_match`, `Equipe1_victorieuse`, `Equipe2_victorieuse`, `Conference`, `id_round`, `phase`) VALUES
(766, 'Los Angeles', 1, 9, 0, '2025-03-12', 1, 0, 0, 1, 1),
(767, 'Boston', 3, 16, NULL, '0000-00-00', 0, 0, 1, 0, 1),
(768, 'Californie', 13, 15, NULL, '0000-00-00', 0, 0, 0, 0, 1),
(769, 'Milwaukee', 10, 14, NULL, '0000-00-00', 0, 0, 1, 0, 1),
(770, 'San Francisco', 2, 11, NULL, '0000-00-00', 0, 0, 0, 0, 1),
(771, 'Atlanta', 7, 6, NULL, '0000-00-00', 0, 0, 1, 0, 1),
(772, 'Denver', 8, 12, NULL, '0000-00-00', 0, 0, 0, 0, 1),
(773, 'Miami', 5, 4, NULL, '0000-00-00', 0, 0, 1, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `matchs_stats`
--

CREATE TABLE `matchs_stats` (
  `id` int(11) NOT NULL,
  `id_match` int(11) NOT NULL,
  `saison` year(4) NOT NULL,
  `score_equipe1` int(11) NOT NULL,
  `score_equipe2` int(11) NOT NULL,
  `score_quart_temps1_equipe1` int(11) NOT NULL,
  `score_quart_temps1_equipe2` int(11) DEFAULT NULL,
  `score_quart_temps2_equipe1` int(11) NOT NULL,
  `score_quart_temps2_equipe2` int(11) NOT NULL,
  `score_quart_temps3_equipe1` int(11) NOT NULL,
  `score_quart_temps3_equipe2` int(11) NOT NULL,
  `score_quart_temps4_equipe1` int(11) NOT NULL,
  `score_quart_temps4_equipe2` int(11) NOT NULL,
  `id_round` int(11) NOT NULL,
  `Location_match` varchar(256) NOT NULL,
  `phase` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `matchs_stats`
--

INSERT INTO `matchs_stats` (`id`, `id_match`, `saison`, `score_equipe1`, `score_equipe2`, `score_quart_temps1_equipe1`, `score_quart_temps1_equipe2`, `score_quart_temps2_equipe1`, `score_quart_temps2_equipe2`, `score_quart_temps3_equipe1`, `score_quart_temps3_equipe2`, `score_quart_temps4_equipe1`, `score_quart_temps4_equipe2`, `id_round`, `Location_match`, `phase`) VALUES
(186, 766, '2025', 34, 45, 0, 0, 0, 0, 0, 0, 0, 0, 1, 'Los Angeles', 1);

-- --------------------------------------------------------

--
-- Table structure for table `trainers`
--

CREATE TABLE `trainers` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `team_id` int(11) DEFAULT NULL,
  `is_admin` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trainers`
--

INSERT INTO `trainers` (`id`, `username`, `password`, `team_id`, `is_admin`) VALUES
(1, 'trainer1', '$2y$10$jX.AGnur.eVmsWbehM93A.R2.SDL0mx6jjD/ZXmLJFxCrWKsOrT0O', 1, 0),
(2, 'trainer2', '$2y$10$2liBYWZRry7fa0K3KSzOfe2OKtCFsPyOTLJvQUghhb7ONVP.E9Fie', 2, 0),
(3, 'trainer3', '$2y$10$qji/e4SMo.D7y4uhhz9JMec2Lc0/7/2GADGGIZO442YC118bcg4rG', 3, 0),
(4, 'trainer4', '$2y$10$xiZms6W3R3JMdL/V2yWpQOgo4LWR7hPsTPeblD8JbX2NsWAb9XCRO', 4, 0),
(5, 'trainer5', '$2y$10$uTIYRp9i74MrQ3Sa8yUsMe8xfm4ES42TKXVD5upuVim/Ese0RIFAm', 5, 0),
(6, 'trainer6', '$2y$10$UBeZXEXxIr5NO7fImAtNEuVXTTcxraOvpZ4YDRsOh/zhuyjG5azsy', 6, 0),
(7, 'trainer7', '$2y$10$kHnfxoOwoxjtQTPq6eKz8uSLGoR87OZxToV3hfuzhec5KfnEzZd2K', 7, 0),
(8, 'trainer8', '$2y$10$2WCo8ACm1i0ZKO4.57CmseV1C6CJ6WwkGJotxtGUBtybeukuwBPWe', 8, 0),
(9, 'trainer9', '$2y$10$165ggq7SDM1p.0BkeLr/LufClQtcr78LhMm0lXzOBkZS168Pc8uma', 9, 0),
(10, 'trainer10', '$2y$10$a2QHcwYOQ4CbwjVS2JBHVuUrqDGwW.Jv9VkT/foeE84/wmTJhVQ.G', 10, 0),
(11, 'trainer11', '$2y$10$rXS/3CDIqff.xx67Pj6SJ.k1tAWF4AxBhjzJ8OJl4wyzYZiZKLW92', 11, 0),
(12, 'trainer12', '$2y$10$iaHvvMtcxcm9pYCD1O3BzOMldp1YRSENajYXkkgfkKBUguM9ZXDXS', 12, 0),
(13, 'trainer13', '$2y$10$Gf3QDVoxlyOde63ZkeSg5.1LKv/Yd1tsfxp9XbOtqqQ4fJcNDuLj2', 13, 0),
(14, 'trainer14', '$2y$10$613gjfpfcVh2Rmloedar1.r.zT6eOCvRoWSiwJrNfYpasAABcGGei', 14, 0),
(15, 'trainer15', '$2y$10$sGJrcoyL8y1VJXY5mdw3uucT0BGwKHYtIF1XhBZl1.6CW4GSYHYHm', 15, 0),
(16, 'Jean marc', '$2y$10$lVD2.13XoeyEWKicU0owPexWLQdgIFArZI2ahatZr3Sg1B4Ps4Vnm', 16, 0),
(17, 'admin', '$2y$10$BNyroUnNNCD1iLGPnsausung4udFbXMwVC6Kj9LpSrfCaS9D3qNO2', 1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `arbitres`
--
ALTER TABLE `arbitres`
  ADD PRIMARY KEY (`id_arbitre`);

--
-- Indexes for table `equipe`
--
ALTER TABLE `equipe`
  ADD PRIMARY KEY (`id_equipe`),
  ADD UNIQUE KEY `nom_equipe` (`nom_equipe`);

--
-- Indexes for table `joueurs`
--
ALTER TABLE `joueurs`
  ADD PRIMARY KEY (`id_joueur`),
  ADD KEY `Id_equipe` (`Id_equipe`);

--
-- Indexes for table `joueurs_stats`
--
ALTER TABLE `joueurs_stats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_joueur` (`id_joueur`),
  ADD KEY `id_match` (`id_match`);

--
-- Indexes for table `matches`
--
ALTER TABLE `matches`
  ADD PRIMARY KEY (`id_match`);

--
-- Indexes for table `matchs_stats`
--
ALTER TABLE `matchs_stats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_match` (`id_match`);

--
-- Indexes for table `trainers`
--
ALTER TABLE `trainers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `team_id` (`team_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `arbitres`
--
ALTER TABLE `arbitres`
  MODIFY `id_arbitre` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `joueurs_stats`
--
ALTER TABLE `joueurs_stats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=696;

--
-- AUTO_INCREMENT for table `matches`
--
ALTER TABLE `matches`
  MODIFY `id_match` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=774;

--
-- AUTO_INCREMENT for table `matchs_stats`
--
ALTER TABLE `matchs_stats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=187;

--
-- AUTO_INCREMENT for table `trainers`
--
ALTER TABLE `trainers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `joueurs_stats`
--
ALTER TABLE `joueurs_stats`
  ADD CONSTRAINT `joueurs_stats_ibfk_1` FOREIGN KEY (`id_joueur`) REFERENCES `joueurs` (`id_joueur`) ON DELETE CASCADE,
  ADD CONSTRAINT `joueurs_stats_ibfk_2` FOREIGN KEY (`id_match`) REFERENCES `matches` (`id_match`) ON DELETE CASCADE;

--
-- Constraints for table `matchs_stats`
--
ALTER TABLE `matchs_stats`
  ADD CONSTRAINT `matchs_stats_ibfk_1` FOREIGN KEY (`id_match`) REFERENCES `matches` (`id_match`) ON DELETE CASCADE;

--
-- Constraints for table `trainers`
--
ALTER TABLE `trainers`
  ADD CONSTRAINT `trainers_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `equipe` (`id_equipe`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
