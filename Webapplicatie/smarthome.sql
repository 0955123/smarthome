-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Gegenereerd op: 27 jun 2019 om 14:34
-- Serverversie: 5.7.19
-- PHP-versie: 5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `smarthome`
--
CREATE DATABASE IF NOT EXISTS `smarthome` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `smarthome`;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `classrooms`
--

DROP TABLE IF EXISTS `classrooms`;
CREATE TABLE IF NOT EXISTS `classrooms` (
  `classroom` varchar(50) NOT NULL,
  PRIMARY KEY (`classroom`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `classrooms`
--

INSERT INTO `classrooms` (`classroom`) VALUES
('1'),
('5.4');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `mqtt_messages`
--

DROP TABLE IF EXISTS `mqtt_messages`;
CREATE TABLE IF NOT EXISTS `mqtt_messages` (
  `classroom` varchar(10) NOT NULL,
  `topic` varchar(50) NOT NULL,
  `message` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`classroom`,`topic`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `mqtt_messages`
--

INSERT INTO `mqtt_messages` (`classroom`, `topic`, `message`) VALUES
('1', 'carbondioxide', '660.00'),
('1', 'humidity', ' 54.00'),
('1', 'status', 'cold'),
('1', 'temperature', ' 29.00'),
('5.4', 'carbondioxide', '402.00'),
('5.4', 'humidity', '55.00'),
('5.4', 'status', 'cold'),
('5.4', 'temperature', '26.00');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` varchar(50) NOT NULL,
  `password` varchar(60) NOT NULL,
  `token` varchar(24) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `users`
--

INSERT INTO `users` (`id`, `password`, `token`, `name`) VALUES
('admin', '$2y$10$sYWGy/7E7fo5CqxUeHbKHu7rkjEnDYPrGZOkRIvn8U6DSJLEwon9a', 'cB6LoU9uAypGostT3E8LgEVq', 'Admin'),
('leraar', '$2y$10$6HnpQ/IcIgv4jr.FEtWXwuxfAEadQl9z6j9e8JIwUf69lpBPQSks2', 'avke01WPtNITsDfUH6iq7EFR', 'Leraar naam');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
