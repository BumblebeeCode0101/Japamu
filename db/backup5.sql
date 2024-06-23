-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Erstellungszeit: 23. Jun 2024 um 13:20
-- Server-Version: 10.4.28-MariaDB
-- PHP-Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `japamu`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `comments`
--

CREATE TABLE `comments` (
  `id` text NOT NULL,
  `reference_type` varchar(250) NOT NULL,
  `reference` text NOT NULL,
  `text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `creator` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `comments`
--

INSERT INTO `comments` (`id`, `reference_type`, `reference`, `text`, `created_at`, `creator`) VALUES
('66730fc263162', 'post', '66730edbadeb7', 'Freu dich lieber, weil du Schulfrei kriegst wenn ich sterbe.', '2024-06-19 17:05:06', '66730f82418bc'),
('66730ff047a19', 'comment', '66730fc263162', 'Danke, Papa! :)\r\n\r\nDas ist sehr nett von dir!', '2024-06-19 17:05:52', '6672ff22acfcf');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `posts`
--

CREATE TABLE `posts` (
  `id` text NOT NULL,
  `creator` text NOT NULL,
  `title` varchar(250) NOT NULL,
  `subtitle` varchar(250) NOT NULL,
  `content` text NOT NULL,
  `visibility` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `posts`
--

INSERT INTO `posts` (`id`, `creator`, `title`, `subtitle`, `content`, `visibility`, `created_at`) VALUES
('66730edbadeb7', '6672ff22acfcf', 'Mein Tag heute', 'Tagebuch Seite 1', '<p><em>Liebes Tagebuch,</em></p>\r\n<p>heute habe ich meine Hausaufgaben wieder nicht gemacht und heute wieder frustriert, weil mein Coding Projekt wieder mal ins stocken geraten ist.</p>\r\n<p><em>Traurige Welt! :-(</em></p>', 1, '2024-06-19 17:01:15');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

CREATE TABLE `users` (
  `id` text NOT NULL,
  `tag` varchar(250) NOT NULL,
  `name` varchar(250) NOT NULL,
  `password` varchar(250) NOT NULL,
  `description` text NOT NULL,
  `follower` bigint(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `users`
--

INSERT INTO `users` (`id`, `tag`, `name`, `password`, `description`, `follower`, `created_at`) VALUES
('6672ff22acfcf', '@Japamu', 'Japamu', '$2y$10$0LO5gKz4nqmzC17rezjRRuYhImcszrZgBuoZgoDlWYVbL.MnR6vBy', '', 0, '2024-06-19 15:54:10'),
('66730f82418bc', '@DerRabenvater', 'Der Rabenvater', '$2y$10$PWm9yKso./In58.1PycTfO0VZvGT.Ab.lyNy/4Y39bVwGic1ifkW.', 'Der Papa vom Coder, der sich immer selbst schlecht macht :-)', 0, '2024-06-19 17:04:02');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
