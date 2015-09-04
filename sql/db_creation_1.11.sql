-- ATTENZIONE
-- Per creare su Aruba copiare la sezione contrassegnata dal
-- =====> INIZIO ARUBA
-- =====> FINE ARUBA

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `aisla_triathlon`
--
CREATE DATABASE IF NOT EXISTS `aisla_triathlon` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `aisla_triathlon`;


-- --------------------------------------------------------
-- =====> INIZIO ARUBA
--
-- Struttura della tabella `abilitazione_modalita_pagamento`
--

DROP TABLE IF EXISTS `abilitazione_modalita_pagamento`;
CREATE TABLE IF NOT EXISTS `abilitazione_modalita_pagamento` (
  `idGara` bigint(20) unsigned NOT NULL,
  `idModalitaPagamento` tinyint(3) unsigned NOT NULL,
  `finoAl` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `abilitazione_tipo_iscrizione`
--

DROP TABLE IF EXISTS `abilitazione_tipo_iscrizione`;
CREATE TABLE IF NOT EXISTS `abilitazione_tipo_iscrizione` (
  `idGara` bigint(20) unsigned NOT NULL,
  `idTipoIscrizione` tinyint(3) unsigned NOT NULL,
  `finoAl` datetime NOT NULL,
  `costo` decimal(7,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `abilitazione_tipo_richiesta_tesseramento`
--

DROP TABLE IF EXISTS `abilitazione_tipo_richiesta_tesseramento`;
CREATE TABLE IF NOT EXISTS `abilitazione_tipo_richiesta_tesseramento` (
  `idTipoRichiestaTesseramento` tinyint(4) unsigned NOT NULL,
  `idGara` bigint(20) unsigned NOT NULL,
  `finoAl` datetime NOT NULL,
  `costo` decimal(7,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `adesione_personale`
--

DROP TABLE IF EXISTS `adesione_personale`;
CREATE TABLE IF NOT EXISTS `adesione_personale` (
  `id` bigint(20) unsigned NOT NULL,
  `categoriaFitri` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `indirizzoLinea1` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `indirizzoLinea2` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `indirizzoCap` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `indirizzoCitta` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `indirizzoProvincia` varchar(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `indirizzoStato` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `idUtente` bigint(11) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `adesione_personale`
--

INSERT INTO `adesione_personale` (`id`, `categoriaFitri`, `indirizzoLinea1`, `indirizzoLinea2`, `indirizzoCap`, `indirizzoCitta`, `indirizzoProvincia`, `indirizzoStato`, `idUtente`) VALUES
(2, 'M1', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 2),
(3, 'M2', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 3),
(4, 'M2', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 4),
(5, 'S3', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 5),
(6, 'S4', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 6),
(7, 'S4', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 7),
(8, 'M1', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 8),
(9, 'M4', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 9),
(10, 'S2', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 10),
(11, 'M4', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 11),
(12, 'S4', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 12),
(13, 'M1', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 13),
(14, 'S1', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 14),
(15, 'M4', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 15),
(16, 'S4', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 16),
(17, 'S4', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 17),
(18, 'M4', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 18),
(19, 'S4', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 19),
(20, 'M1', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 20),
(21, 'S3', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 21),
(22, 'S2', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 22),
(23, 'M4', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 23),
(24, 'S2', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 24),
(25, 'M4', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 25),
(26, 'S3', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 26),
(27, 'S3', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 27),
(28, 'M2', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 28),
(29, 'S3', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 29),
(30, 'S2', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 30),
(31, 'M2', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 31),
(32, 'S3', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 32),
(33, 'S4', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 33),
(34, 'S2', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 34),
(35, 'S3', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 35),
(36, 'M3', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 36),
(37, 'M1', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 37),
(38, 'S3', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 38),
(39, 'S3', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 39),
(40, 'M3', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 40),
(41, 'M1', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 41),
(42, 'S3', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 42),
(43, 'S3', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 43),
(44, 'M3', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 44),
(45, 'S4', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 45),
(46, 'M2', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 46),
(47, 'S4', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 47),
(48, 'M1', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 48),
(49, 'S3', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 49),
(50, 'S3', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 50),
(51, 'M1', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 51),
(52, 'M2', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 52),
(53, 'M4', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 53),
(54, 'M1', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 54),
(55, 'M2', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 55),
(56, 'S3', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 56),
(57, 'S3', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 57),
(58, 'M1', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 58),
(59, 'S4', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 59),
(60, 'S4', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 60),
(61, 'S4', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 61),
(62, 'S3', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 62),
(63, 'S3', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 63),
(64, 'M1', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 64),
(65, 'M1', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 65),
(66, 'M2', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 66),
(67, 'M1', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 67),
(68, 'S3', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 68),
(69, 'M2', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 69),
(70, 'M3', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 70),
(71, 'M1', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 71),
(72, 'M3', 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 72);

-- --------------------------------------------------------

--
-- Struttura della tabella `adesione_personale__invito`
--

DROP TABLE IF EXISTS `adesione_personale__invito`;
CREATE TABLE IF NOT EXISTS `adesione_personale__invito` (
  `idAdesionePersonale` bigint(20) unsigned NOT NULL,
  `codiceInvito` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `adesione_personale__squadra`
--

DROP TABLE IF EXISTS `adesione_personale__squadra`;
CREATE TABLE IF NOT EXISTS `adesione_personale__squadra` (
  `idAdesionePersonale` bigint(20) unsigned NOT NULL,
  `idSquadra` bigint(20) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `adesione_personale__squadra`
--

INSERT INTO `adesione_personale__squadra` (`idAdesionePersonale`, `idSquadra`) VALUES
(58, 1),
(59, 1),
(60, 1),
(61, 2),
(62, 2),
(63, 2),
(64, 3),
(65, 3),
(66, 3),
(67, 4),
(68, 4),
(69, 4),
(70, 5),
(71, 5),
(72, 5);

-- --------------------------------------------------------

--
-- Struttura della tabella `categoria_fitri`
--

DROP TABLE IF EXISTS `categoria_fitri`;
CREATE TABLE IF NOT EXISTS `categoria_fitri` (
  `nome` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nomeEsteso` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `iniziaDaAnni` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `categoria_fitri`
--

INSERT INTO `categoria_fitri` (`nome`, `nomeEsteso`, `iniziaDaAnni`) VALUES
('CUC', 'Cuccioli', 8),
('ESO', 'Esordienti', 10),
('JUN', 'Junior', 18),
('M1', 'Master 1', 40),
('M2', 'Master 2', 45),
('M3', 'Master 3', 50),
('M4', 'Master 4', 55),
('M5', 'Master 5', 60),
('M6', 'Master 6', 65),
('M7', 'Master 7', 70),
('M8', 'Master 8', 75),
('MIN', 'Minicuccioli', 6),
('RAG', 'Ragazzi', 12),
('S1', 'Senior 1', 20),
('S2', 'Senior 2', 25),
('S3', 'Senior 3', 30),
('S4', 'Senior 4', 35),
('YOA', 'Youth A', 14),
('YOB', 'Youth B', 16);

-- --------------------------------------------------------

--
-- Struttura della tabella `codice_conclusione_gara`
--

DROP TABLE IF EXISTS `codice_conclusione_gara`;
CREATE TABLE IF NOT EXISTS `codice_conclusione_gara` (
  `nome` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descrizione_it` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descrizione_en` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `codice_conclusione_gara`
--

INSERT INTO `codice_conclusione_gara` (`nome`, `descrizione_it`, `descrizione_en`) VALUES
('COM', 'Completata', 'Completed'),
('DNF', 'Non finita (Did Not Finish)', 'Did Not Finish'),
('DNS', 'Non partito (Did Not Start)', 'Did Not Start'),
('DSQ', 'Squalificato (Disqualified)', 'Disqualified');

-- --------------------------------------------------------

--
-- Struttura della tabella `documento`
--

DROP TABLE IF EXISTS `documento`;
CREATE TABLE IF NOT EXISTS `documento` (
  `nomeFile` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `idRichiestaTesseramento` bigint(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `gara`
--

DROP TABLE IF EXISTS `gara`;
CREATE TABLE IF NOT EXISTS `gara` (
  `id` bigint(20) unsigned NOT NULL,
  `nome` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descrizione_it` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `descrizione_en` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `disputataIl` datetime NOT NULL,
  `idTipoGara` tinyint(10) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `gara`
--

INSERT INTO `gara` (`id`, `nome`, `descrizione_it`, `descrizione_en`, `disputataIl`, `idTipoGara`) VALUES
(1, 'TdM AISLA Sprint MTB Individuale', NULL, NULL, '2014-06-29 00:00:00', 1),
(2, 'TdM AISLA Olimpico MTB Individuale', NULL, NULL, '2014-06-29 00:00:00', 1),
(3, 'TdM AISLA Olimpico MTB In squadra', NULL, NULL, '2014-06-29 00:00:00', 2);

-- --------------------------------------------------------

--
-- Struttura della tabella `invito`
--

DROP TABLE IF EXISTS `invito`;
CREATE TABLE IF NOT EXISTS `invito` (
  `codice` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nome` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cognome` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `idIscrizione` bigint(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `iscrizione`
--

DROP TABLE IF EXISTS `iscrizione`;
CREATE TABLE IF NOT EXISTS `iscrizione` (
  `id` bigint(20) unsigned NOT NULL,
  `eseguitaIl` datetime NOT NULL,
  `pettorale` int(11) DEFAULT NULL,
  `motto` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `haImmagine` tinyint(1) NOT NULL,
  `idOrdine` bigint(11) unsigned NOT NULL,
  `idGara` bigint(11) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `iscrizione`
--

INSERT INTO `iscrizione` (`id`, `eseguitaIl`, `pettorale`, `motto`, `haImmagine`, `idOrdine`, `idGara`) VALUES
(2, '2014-06-29 00:00:00', 47, '', 0, 1, 1),
(3, '2014-06-29 00:00:00', 48, '', 0, 1, 1),
(4, '2014-06-29 00:00:00', 52, '', 0, 1, 1),
(5, '2014-06-29 00:00:00', 33, '', 0, 1, 1),
(6, '2014-06-29 00:00:00', 42, '', 0, 1, 1),
(7, '2014-06-29 00:00:00', 41, '', 0, 1, 1),
(8, '2014-06-29 00:00:00', 43, '', 0, 1, 1),
(9, '2014-06-29 00:00:00', 54, '', 0, 1, 1),
(10, '2014-06-29 00:00:00', 28, '', 0, 1, 1),
(11, '2014-06-29 00:00:00', 55, '', 0, 1, 1),
(12, '2014-06-29 00:00:00', 40, '', 0, 1, 1),
(13, '2014-06-29 00:00:00', 46, '', 0, 1, 1),
(14, '2014-06-29 00:00:00', 20, '', 0, 1, 1),
(15, '2014-06-29 00:00:00', 57, '', 0, 1, 1),
(16, '2014-06-29 00:00:00', 38, '', 0, 1, 1),
(17, '2014-06-29 00:00:00', 37, '', 0, 1, 1),
(18, '2014-06-29 00:00:00', 53, '', 0, 1, 1),
(19, '2014-06-29 00:00:00', 36, '', 0, 1, 1),
(20, '2014-06-29 00:00:00', 45, '', 0, 1, 1),
(21, '2014-06-29 00:00:00', 32, '', 0, 1, 1),
(22, '2014-06-29 00:00:00', 27, '', 0, 1, 1),
(23, '2014-06-29 00:00:00', 26, '', 0, 1, 1),
(24, '2014-06-29 00:00:00', 58, '', 0, 1, 1),
(25, '2014-06-29 00:00:00', 24, '', 0, 1, 1),
(26, '2014-06-29 00:00:00', 34, '', 0, 1, 1),
(27, '2014-06-29 00:00:00', 23, '', 0, 1, 1),
(28, '2014-06-29 00:00:00', 51, '', 0, 1, 1),
(29, '2014-06-29 00:00:00', 30, '', 0, 1, 1),
(30, '2014-06-29 00:00:00', 21, '', 0, 1, 1),
(31, '2014-06-29 00:00:00', 50, '', 0, 1, 1),
(32, '2014-06-29 00:00:00', 25, '', 0, 1, 1),
(33, '2014-06-29 00:00:00', 39, '', 0, 1, 1),
(34, '2014-06-29 00:00:00', 29, '', 0, 1, 1),
(35, '2014-06-29 00:00:00', 35, '', 0, 1, 1),
(36, '2014-06-29 00:00:00', 56, '', 0, 1, 1),
(37, '2014-06-29 00:00:00', 44, '', 0, 1, 1),
(38, '2014-06-29 00:00:00', 4, '', 0, 1, 2),
(39, '2014-06-29 00:00:00', 3, '', 0, 1, 2),
(40, '2014-06-29 00:00:00', 16, '', 0, 1, 2),
(41, '2014-06-29 00:00:00', 11, '', 0, 1, 2),
(42, '2014-06-29 00:00:00', 2, '', 0, 1, 2),
(43, '2014-06-29 00:00:00', 9, '', 0, 1, 2),
(44, '2014-06-29 00:00:00', 17, '', 0, 1, 2),
(45, '2014-06-29 00:00:00', 1, '', 0, 1, 2),
(46, '2014-06-29 00:00:00', 15, '', 0, 1, 2),
(47, '2014-06-29 00:00:00', 10, '', 0, 1, 2),
(48, '2014-06-29 00:00:00', 59, '', 0, 1, 2),
(49, '2014-06-29 00:00:00', 6, '', 0, 1, 2),
(50, '2014-06-29 00:00:00', 5, '', 0, 1, 2),
(51, '2014-06-29 00:00:00', 18, '', 0, 1, 2),
(52, '2014-06-29 00:00:00', 14, '', 0, 1, 2),
(53, '2014-06-29 00:00:00', 19, '', 0, 1, 2),
(54, '2014-06-29 00:00:00', 12, '', 0, 1, 2),
(55, '2014-06-29 00:00:00', 13, '', 0, 1, 2),
(56, '2014-06-29 00:00:00', 8, '', 0, 1, 2),
(57, '2014-06-29 00:00:00', 7, '', 0, 1, 2),
(58, '2014-06-29 00:00:00', 101, '', 0, 1, 3),
(59, '2014-06-29 00:00:00', 102, '', 0, 1, 3),
(60, '2014-06-29 00:00:00', 103, '', 0, 1, 3),
(61, '2014-06-29 00:00:00', 104, '', 0, 1, 3),
(62, '2014-06-29 00:00:00', 105, '', 0, 1, 3);

-- --------------------------------------------------------

--
-- Struttura della tabella `iscrizione__adesione_personale`
--

DROP TABLE IF EXISTS `iscrizione__adesione_personale`;
CREATE TABLE IF NOT EXISTS `iscrizione__adesione_personale` (
  `idIscrizione` bigint(20) unsigned NOT NULL,
  `idAdesionePersonale` bigint(20) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `iscrizione__adesione_personale`
--

INSERT INTO `iscrizione__adesione_personale` (`idIscrizione`, `idAdesionePersonale`) VALUES
(2, 2),
(3, 3),
(4, 4),
(5, 5),
(6, 6),
(7, 7),
(8, 8),
(9, 9),
(10, 10),
(11, 11),
(12, 12),
(13, 13),
(14, 14),
(15, 15),
(16, 16),
(17, 17),
(18, 18),
(19, 19),
(20, 20),
(21, 21),
(22, 22),
(23, 23),
(24, 24),
(25, 25),
(26, 26),
(27, 27),
(28, 28),
(29, 29),
(30, 30),
(31, 31),
(32, 32),
(33, 33),
(34, 34),
(35, 35),
(36, 36),
(37, 37),
(38, 38),
(39, 39),
(40, 40),
(41, 41),
(42, 42),
(43, 43),
(44, 44),
(45, 45),
(46, 46),
(47, 47),
(48, 48),
(49, 49),
(50, 50),
(51, 51),
(52, 52),
(53, 53),
(54, 54),
(55, 55),
(56, 56),
(57, 57);

-- --------------------------------------------------------

--
-- Struttura della tabella `iscrizione__squadra`
--

DROP TABLE IF EXISTS `iscrizione__squadra`;
CREATE TABLE IF NOT EXISTS `iscrizione__squadra` (
  `idIscrizione` bigint(20) unsigned NOT NULL,
  `idSquadra` bigint(20) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `iscrizione__squadra`
--

INSERT INTO `iscrizione__squadra` (`idIscrizione`, `idSquadra`) VALUES
(58, 1),
(59, 2),
(60, 3),
(61, 4),
(62, 5);

-- --------------------------------------------------------

--
-- Struttura della tabella `modalita_pagamento`
--

DROP TABLE IF EXISTS `modalita_pagamento`;
CREATE TABLE IF NOT EXISTS `modalita_pagamento` (
  `id` tinyint(3) unsigned NOT NULL,
  `nome_it` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descrizione_it` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nome_en` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descrizione_en` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `modalita_pagamento`
--

INSERT INTO `modalita_pagamento` (`id`, `nome_it`, `descrizione_it`, `nome_en`, `descrizione_en`) VALUES
(1, 'PayPal', NULL, 'PayPal', NULL),
(2, 'Bonifico Bancario', NULL, 'Bank Transfer', NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `ordine`
--

DROP TABLE IF EXISTS `ordine`;
CREATE TABLE IF NOT EXISTS `ordine` (
  `id` bigint(20) unsigned NOT NULL,
  `ricevutoIl` datetime NOT NULL,
  `totale` decimal(7,2) NOT NULL,
  `pagato` tinyint(1) NOT NULL,
  `ricevutaInviata` tinyint(1) NOT NULL,
  `ricevutaInviataIl` datetime DEFAULT NULL,
  `idModalitaPagamento` tinyint(3) unsigned NOT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `clienteIndirizzoLinea1` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `clienteIndirizzoLinea2` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `clienteIndirizzoCap` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `clienteIndirizzoCitta` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `clienteIndirizzoProvincia` varchar(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `clienteIndirizzoStato` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `idCliente` bigint(11) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `ordine`
--

INSERT INTO `ordine` (`id`, `ricevutoIl`, `totale`, `pagato`, `ricevutaInviata`, `ricevutaInviataIl`, `idModalitaPagamento`, `note`, `clienteIndirizzoLinea1`, `clienteIndirizzoLinea2`, `clienteIndirizzoCap`, `clienteIndirizzoCitta`, `clienteIndirizzoProvincia`, `clienteIndirizzoStato`, `idCliente`) VALUES
(1, '2014-06-29 00:00:00', '0.00', 1, 1, NULL, 1, NULL, 'importazione dati 2014', NULL, 'importazio', 'importazione dati 2014', NULL, 'importazione dati 2014', 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `richiesta_tesseramento`
--

DROP TABLE IF EXISTS `richiesta_tesseramento`;
CREATE TABLE IF NOT EXISTS `richiesta_tesseramento` (
  `id` bigint(20) unsigned NOT NULL,
  `eseguitaIl` datetime NOT NULL,
  `verificata` tinyint(1) NOT NULL,
  `idAdesionePersonale` bigint(11) unsigned NOT NULL,
  `idTipoRichiestaTesseramento` tinyint(3) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `richiesta_tesseramento`
--

INSERT INTO `richiesta_tesseramento` (`id`, `eseguitaIl`, `verificata`, `idAdesionePersonale`, `idTipoRichiestaTesseramento`) VALUES
(2, '2014-06-29 00:00:00', 1, 2, 2),
(3, '2014-06-29 00:00:00', 1, 3, 2),
(4, '2014-06-29 00:00:00', 1, 4, 2),
(5, '2014-06-29 00:00:00', 1, 5, 1),
(6, '2014-06-29 00:00:00', 1, 6, 1),
(7, '2014-06-29 00:00:00', 1, 7, 2),
(8, '2014-06-29 00:00:00', 1, 8, 2),
(9, '2014-06-29 00:00:00', 1, 9, 2),
(10, '2014-06-29 00:00:00', 1, 10, 2),
(11, '2014-06-29 00:00:00', 1, 11, 2),
(12, '2014-06-29 00:00:00', 1, 12, 2),
(13, '2014-06-29 00:00:00', 1, 13, 1),
(14, '2014-06-29 00:00:00', 1, 14, 2),
(15, '2014-06-29 00:00:00', 1, 15, 1),
(16, '2014-06-29 00:00:00', 1, 16, 1),
(17, '2014-06-29 00:00:00', 1, 17, 2),
(18, '2014-06-29 00:00:00', 1, 18, 2),
(19, '2014-06-29 00:00:00', 1, 19, 2),
(20, '2014-06-29 00:00:00', 1, 20, 1),
(21, '2014-06-29 00:00:00', 1, 21, 2),
(22, '2014-06-29 00:00:00', 1, 22, 2),
(23, '2014-06-29 00:00:00', 1, 23, 2),
(24, '2014-06-29 00:00:00', 1, 24, 2),
(25, '2014-06-29 00:00:00', 1, 25, 2),
(26, '2014-06-29 00:00:00', 1, 26, 2),
(27, '2014-06-29 00:00:00', 1, 27, 2),
(28, '2014-06-29 00:00:00', 1, 28, 2),
(29, '2014-06-29 00:00:00', 1, 29, 2),
(30, '2014-06-29 00:00:00', 1, 30, 2),
(31, '2014-06-29 00:00:00', 1, 31, 1),
(32, '2014-06-29 00:00:00', 1, 32, 2),
(33, '2014-06-29 00:00:00', 1, 33, 2),
(34, '2014-06-29 00:00:00', 1, 34, 2),
(35, '2014-06-29 00:00:00', 1, 35, 2),
(36, '2014-06-29 00:00:00', 1, 36, 1),
(37, '2014-06-29 00:00:00', 1, 37, 1),
(38, '2014-06-29 00:00:00', 1, 38, 2),
(39, '2014-06-29 00:00:00', 1, 39, 2),
(40, '2014-06-29 00:00:00', 1, 40, 2),
(41, '2014-06-29 00:00:00', 1, 41, 2),
(42, '2014-06-29 00:00:00', 1, 42, 2),
(43, '2014-06-29 00:00:00', 1, 43, 2),
(44, '2014-06-29 00:00:00', 1, 44, 2),
(45, '2014-06-29 00:00:00', 1, 45, 2),
(46, '2014-06-29 00:00:00', 1, 46, 2),
(47, '2014-06-29 00:00:00', 1, 47, 2),
(48, '2014-06-29 00:00:00', 1, 48, 2),
(49, '2014-06-29 00:00:00', 1, 49, 2),
(50, '2014-06-29 00:00:00', 1, 50, 2),
(51, '2014-06-29 00:00:00', 1, 51, 2),
(52, '2014-06-29 00:00:00', 1, 52, 2),
(53, '2014-06-29 00:00:00', 1, 53, 2),
(54, '2014-06-29 00:00:00', 1, 54, 2),
(55, '2014-06-29 00:00:00', 1, 55, 2),
(56, '2014-06-29 00:00:00', 1, 56, 2),
(57, '2014-06-29 00:00:00', 1, 57, 2),
(58, '2014-06-29 00:00:00', 1, 58, 1),
(59, '2014-06-29 00:00:00', 1, 59, 1),
(60, '2014-06-29 00:00:00', 1, 60, 1),
(61, '2014-06-29 00:00:00', 1, 61, 2),
(62, '2014-06-29 00:00:00', 1, 62, 2),
(63, '2014-06-29 00:00:00', 1, 63, 2),
(64, '2014-06-29 00:00:00', 1, 64, 1),
(65, '2014-06-29 00:00:00', 1, 65, 1),
(66, '2014-06-29 00:00:00', 1, 66, 1),
(67, '2014-06-29 00:00:00', 1, 67, 2),
(68, '2014-06-29 00:00:00', 1, 68, 2),
(69, '2014-06-29 00:00:00', 1, 69, 1),
(70, '2014-06-29 00:00:00', 1, 70, 1),
(71, '2014-06-29 00:00:00', 1, 71, 1),
(72, '2014-06-29 00:00:00', 1, 72, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `risultato`
--

DROP TABLE IF EXISTS `risultato`;
CREATE TABLE IF NOT EXISTS `risultato` (
  `id` bigint(20) unsigned NOT NULL,
  `codiceConclusioneGara` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `posizione` int(11) DEFAULT NULL,
  `posizioneInSesso` int(11) DEFAULT NULL,
  `posizioneInCategoria` int(11) DEFAULT NULL,
  `tempo` time DEFAULT NULL,
  `posizioneDopoNuoto` int(11) DEFAULT NULL,
  `tempoDopoNuoto` time DEFAULT NULL,
  `posizioneDopoBici` int(11) DEFAULT NULL,
  `tempoDopoBici` time DEFAULT NULL,
  `posizioneFrazioneBici` int(11) DEFAULT NULL,
  `tempoFrazioneBici` time DEFAULT NULL,
  `posizioneFrazioneCorsa` int(11) DEFAULT NULL,
  `tempoFrazioneCorsa` time DEFAULT NULL,
  `idIscrizione` bigint(11) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=576 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `risultato`
--

INSERT INTO `risultato` (`id`, `codiceConclusioneGara`, `posizione`, `posizioneInSesso`, `posizioneInCategoria`, `tempo`, `posizioneDopoNuoto`, `tempoDopoNuoto`, `posizioneDopoBici`, `tempoDopoBici`, `posizioneFrazioneBici`, `tempoFrazioneBici`, `posizioneFrazioneCorsa`, `tempoFrazioneCorsa`, `idIscrizione`) VALUES
(515, 'COM', 30, 30, 4, '01:52:18', 20, '00:18:26', 29, '01:20:03', 31, '01:01:37', 33, '00:32:15', 2),
(516, 'COM', 14, 14, 1, '01:24:58', 11, '00:16:53', 14, '01:00:32', 13, '00:43:39', 17, '00:24:26', 3),
(517, 'COM', 19, 19, 2, '01:29:50', 15, '00:17:21', 18, '01:04:51', 20, '00:47:30', 20, '00:24:59', 4),
(518, 'COM', 11, 11, 5, '01:19:31', 13, '00:17:04', 11, '00:58:18', 10, '00:41:14', 10, '00:21:13', 5),
(519, 'COM', 17, 17, 4, '01:29:04', 31, '00:22:30', 21, '01:07:27', 17, '00:44:57', 12, '00:21:37', 6),
(520, 'COM', 23, 23, 6, '01:36:58', 26, '00:21:46', 27, '01:14:20', 26, '00:52:34', 15, '00:22:38', 7),
(521, 'COM', 18, 18, 1, '01:29:43', 9, '00:15:57', 15, '01:00:51', 16, '00:44:54', 27, '00:28:52', 8),
(522, 'DNF', NULL, NULL, NULL, NULL, 36, '00:33:32', NULL, NULL, NULL, NULL, NULL, NULL, 9),
(523, 'COM', 16, 16, 2, '01:28:05', 5, '00:14:54', 17, '01:03:30', 23, '00:48:36', 18, '00:24:35', 10),
(524, 'COM', 22, 22, 3, '01:35:34', 27, '00:21:56', 25, '01:10:14', 21, '00:48:18', 21, '00:25:20', 11),
(525, 'COM', 7, 7, 1, '01:17:22', 23, '00:20:10', 8, '00:56:59', 2, '00:36:49', 5, '00:20:23', 12),
(526, 'COM', 28, 28, 3, '01:42:53', 25, '00:20:47', 28, '01:15:04', 27, '00:54:17', 25, '00:27:49', 13),
(527, 'COM', 1, 1, 0, '01:08:26', 1, '00:12:41', 1, '00:49:40', 3, '00:36:59', 2, '00:18:46', 14),
(528, 'COM', 34, 2, 0, '02:04:01', 30, '00:22:18', 33, '01:26:35', 33, '01:04:17', 34, '00:37:26', 15),
(529, 'COM', 15, 15, 3, '01:27:45', 4, '00:14:21', 16, '01:02:50', 22, '00:48:29', 19, '00:24:55', 16),
(530, 'COM', 10, 10, 2, '01:19:08', 18, '00:18:07', 12, '00:59:12', 9, '00:41:05', 4, '00:19:56', 17),
(531, 'COM', 13, 13, 2, '01:21:04', 14, '00:17:18', 13, '01:00:31', 12, '00:43:13', 6, '00:20:33', 18),
(532, 'COM', 27, 27, 7, '01:39:34', 22, '00:19:40', 24, '01:10:12', 25, '00:50:32', 28, '00:29:22', 19),
(533, 'COM', 32, 32, 5, '01:57:06', 33, '00:23:13', 31, '01:25:30', 32, '01:02:17', 32, '00:31:36', 20),
(534, 'COM', 9, 9, 4, '01:18:49', 16, '00:17:41', 9, '00:57:09', 8, '00:39:28', 13, '00:21:40', 21),
(535, 'COM', 8, 8, 1, '01:17:54', 7, '00:15:33', 7, '00:56:51', 11, '00:41:18', 8, '00:21:03', 22),
(536, 'COM', 26, 26, 4, '01:39:23', 19, '00:18:16', 22, '01:08:14', 24, '00:49:58', 30, '00:31:09', 23),
(537, 'COM', 29, 29, 3, '01:44:04', 24, '00:20:25', 30, '01:21:13', 30, '01:00:48', 16, '00:22:51', 24),
(538, 'COM', 12, 12, 1, '01:20:15', 3, '00:14:09', 10, '00:58:08', 14, '00:43:59', 14, '00:22:07', 25),
(539, 'COM', 31, 31, 7, '01:54:30', 35, '00:32:37', 34, '01:27:15', 28, '00:54:38', 24, '00:27:15', 26),
(540, 'COM', 4, 4, 1, '01:14:47', 10, '00:16:15', 4, '00:54:02', 4, '00:37:47', 7, '00:20:45', 27),
(541, 'DNF', NULL, NULL, NULL, NULL, 34, '00:23:44', NULL, NULL, NULL, NULL, NULL, NULL, 28),
(542, 'COM', 21, 21, 6, '01:35:03', 21, '00:19:01', 20, '01:06:29', 19, '00:47:28', 26, '00:28:34', 29),
(543, 'COM', 2, 2, 0, '01:11:13', 2, '00:13:26', 2, '00:51:56', 5, '00:38:30', 3, '00:19:17', 30),
(544, 'COM', 25, 25, 3, '01:38:37', 12, '00:16:59', 26, '01:12:16', 29, '00:55:17', 23, '00:26:21', 31),
(545, 'COM', 5, 5, 2, '01:15:19', 17, '00:17:52', 3, '00:53:49', 1, '00:35:57', 11, '00:21:30', 32),
(546, 'COM', 20, 20, 5, '01:32:26', 29, '00:22:12', 19, '01:06:27', 15, '00:44:15', 22, '00:25:59', 33),
(547, 'COM', 3, 3, 0, '01:13:11', 6, '00:15:22', 5, '00:54:45', 7, '00:39:23', 1, '00:18:26', 34),
(548, 'COM', 6, 6, 3, '01:15:52', 8, '00:15:52', 6, '00:54:48', 6, '00:38:56', 9, '00:21:04', 35),
(549, 'COM', 33, 1, 0, '01:57:44', 28, '00:22:04', 32, '01:26:26', 34, '01:04:22', 31, '00:31:18', 36),
(550, 'COM', 24, 24, 2, '01:38:36', 32, '00:23:01', 23, '01:08:52', 18, '00:45:51', 29, '00:29:44', 37),
(551, 'COM', 1, 1, 1, '02:14:47', 4, '00:26:12', 2, '01:37:32', 1, '01:11:20', 1, '00:37:15', 38),
(552, 'COM', 3, 3, 2, '02:20:10', 2, '00:24:30', 3, '01:37:38', 3, '01:13:08', 6, '00:42:32', 39),
(553, 'COM', 8, 8, 1, '02:33:59', 11, '00:31:18', 8, '01:46:39', 6, '01:15:21', 11, '00:47:20', 40),
(554, 'COM', 11, 11, 1, '02:51:24', 10, '00:31:12', 13, '02:01:05', 13, '01:29:53', 13, '00:50:19', 41),
(555, 'COM', 5, 5, 4, '02:25:11', 3, '00:25:07', 5, '01:43:00', 8, '01:17:53', 5, '00:42:11', 42),
(556, 'DNF', NULL, NULL, NULL, NULL, 14, '00:32:05', 12, '01:57:47', 12, '01:25:42', NULL, NULL, 43),
(557, 'COM', 18, 18, 3, '03:20:28', 14, '00:32:07', 16, '02:18:13', 17, '01:46:06', 17, '01:02:15', 44),
(558, 'COM', 2, 2, 1, '02:16:42', 1, '00:22:58', 1, '01:36:29', 4, '01:13:31', 2, '00:40:13', 45),
(559, 'COM', 13, 13, 2, '02:55:39', 18, '00:42:42', 14, '02:06:51', 11, '01:24:09', 12, '00:48:48', 46),
(560, 'COM', 9, 9, 2, '02:41:18', 16, '00:32:51', 10, '01:56:08', 10, '01:23:17', 8, '00:45:10', 47),
(561, 'COM', 12, 12, 2, '02:52:13', 6, '00:28:40', 12, '02:01:03', 14, '01:32:23', 15, '00:51:10', 48),
(562, 'COM', 7, 7, 5, '02:29:02', 9, '00:31:05', 7, '01:46:12', 5, '01:15:07', 7, '00:42:50', 49),
(563, 'COM', 4, 4, 3, '02:22:22', 8, '00:29:03', 4, '01:40:44', 2, '01:11:41', 4, '00:41:38', 50),
(564, 'COM', 17, 17, 2, '03:16:31', 5, '00:28:15', 18, '02:25:49', 18, '01:57:34', 14, '00:50:42', 51),
(565, 'COM', 16, 16, 3, '03:10:21', 17, '00:38:30', 17, '02:23:27', 16, '01:44:57', 9, '00:46:54', 52),
(566, 'COM', 14, 14, 1, '02:57:18', 13, '00:31:58', 9, '01:54:16', 9, '01:22:18', 18, '01:03:02', 53),
(567, 'COM', 15, 15, 3, '03:02:47', 15, '00:32:35', 15, '02:10:16', 15, '01:37:41', 16, '00:52:31', 54),
(568, 'COM', 6, 6, 1, '02:25:59', 7, '00:28:59', 6, '01:45:23', 7, '01:16:24', 3, '00:40:36', 55),
(569, 'DNF', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 56),
(570, 'COM', 10, 10, 6, '02:44:42', 12, '00:31:40', 11, '01:57:23', 12, '01:25:43', 10, '00:47:19', 57),
(571, 'COM', 4, NULL, NULL, '02:31:00', 4, '00:30:57', 5, '01:51:43', 4, '01:20:46', 3, '00:39:17', 58),
(572, 'COM', 1, NULL, NULL, '02:00:37', 1, '00:22:28', 1, '01:21:50', 1, '00:59:22', 2, '00:38:47', 59),
(573, 'COM', 2, NULL, NULL, '02:09:33', 2, '00:25:24', 2, '01:33:02', 2, '01:07:38', 1, '00:36:31', 60),
(574, 'COM', 5, NULL, NULL, '02:34:50', 5, '00:25:18', 4, '01:47:46', 5, '01:22:28', 4, '00:47:04', 61),
(575, 'COM', 3, NULL, NULL, '02:28:21', 3, '00:26:44', 3, '01:40:39', 3, '01:13:55', 5, '00:47:42', 62);

-- --------------------------------------------------------

--
-- Struttura della tabella `societa_fitri`
--

DROP TABLE IF EXISTS `societa_fitri`;
CREATE TABLE IF NOT EXISTS `societa_fitri` (
  `codice` int(11) NOT NULL,
  `nome` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `societa_fitri`
--

INSERT INTO `societa_fitri` (`codice`, `nome`) VALUES
(3, 'S.S. LAZIO TRIATHLON'),
(4, 'TRIATHLON OSTIA A.S.D.'),
(6, 'FERRARA TRIATHLON CLUB A.S.D.'),
(10, 'TRIATHLON TEAM BRIANZA'),
(17, 'TRAGUARDO VOLANTE RACING'),
(30, 'C.U.S. VERONA'),
(31, 'PADOVA TRIATHLON DUATHLON A.S.D.'),
(48, 'TRIO EVENTI'),
(51, 'PESARO TRIATHLON IRONMAN'),
(69, 'SAI FRECCE BIANCHE A.S.D.'),
(77, 'TRIATHLON NOVARA A.S.D.'),
(78, 'C.S. ESERCITO'),
(84, 'MINERVA ROMA - SEZ.TRIATHLON A.S.D.'),
(87, 'VALLE D''AOSTA TRIATH A.S.D.'),
(89, 'A.S.D. TORINO TRIATHLON'),
(96, 'MOLINARI TRIATHLON TEAM COMO A.S.D.'),
(1001, 'G.S. BARDOLINO'),
(1006, 'TORRINO TRIATHLON TEAM A.S.D.'),
(1018, 'SILCA ULTRALITE VITTORIO VENETO A.S.D.'),
(1028, 'ASS. SPORT. DIL.CA VIRTUS'),
(1029, 'C.U.S. PARMA A.S.D.'),
(1031, 'TRIATHLON ALTO ADIGE A.S.D.'),
(1032, 'A.S.D. TRIATHLON SAVONA'),
(1041, 'TRIATHLON RARI NANTES MAROSTICA S.S. A R. L.'),
(1050, 'POLISP GALILEO GIOVOLLEY A.S.D.'),
(1055, 'TERAMO TRIATHLON TEAM'),
(1079, 'RIVIERA TRIATHLON 1992 A.S.D.'),
(1085, 'TRIATHLON TEAM AVIA PERVIA MODENA'),
(1091, 'G.S. PASTA GRANAROLO A.S.D.'),
(1096, 'ANDORA TRIATHLON'),
(1098, 'ALBA TRIATHLON'),
(1099, 'VERSILIA SPORT A.S.D.'),
(1103, 'GRUPPO SPORTIVO FIAMME AZZURRE'),
(1111, 'FEDERCLUB TRIESTE SCAT CAPPONI T. A.S.D.'),
(1115, 'TRIATHLON CREMONA STRADIVARI S.S.D. R.L.'),
(1117, 'TRIATHLON TREVISO'),
(1132, 'ROAD RUNNERS CLUB MILANO TRIATHLON'),
(1138, 'TRIATHLON TRASIMENO A.S.D.'),
(1140, 'POL. DIL. IRONLARIO TRIATHLON CLUB'),
(1145, 'TRIATHLON GENOVA A.S.D.'),
(1152, 'C.U.S. TRENTO A.S.D.'),
(1164, 'C.U.S. UDINE A.S.D.'),
(1170, 'CE.MS. CENTRO MULT. PIACENZA TRIAT.'),
(1172, 'A.S.D. RHO TRIATHLON CLUB'),
(1174, 'ASD CNM TRIATHLON'),
(1177, 'TRIATHLON LECCO A.S.D.'),
(1180, 'PRO PATRIA MILANO SRL S.S.D.'),
(1182, 'POLISPORTIVA CENTESE A.S.D.'),
(1186, 'POLISPORTIVA NADIR PALERMO A.S.D.'),
(1195, 'SOC. NUOTO GROSSETO'),
(1202, 'CIRCOLO CANOTTIERI IRNO A.S.D.'),
(1204, 'SURFING SHOP SPORT PROMOTION A.S.D.'),
(1210, 'FIRENZE TRIATHLON A.S.D.'),
(1213, 'FRIESIAN TEAM'),
(1214, 'IRONBIELLA TRIATHLON A.S.D.'),
(1216, 'ONDAVERDE TRIATHLON A.S.D.'),
(1238, 'TRIATHLON DUATHLON RIMINI A.S.D.'),
(1241, 'G.P. TRIATHLON'),
(1242, 'SPORT EXTREME'),
(1251, 'IRON TEAM PALERMO A.S.D.'),
(1257, 'TRIATHLON TEAM TRAPANI A.S.D.'),
(1260, 'A.S.D. TRIATHLON TEAM'),
(1264, 'PISA TRIATHLON A.S.D.'),
(1266, 'G.S. MANERBA A.S.D.'),
(1268, 'A.S.D. SPEZIA TRIATHLON'),
(1270, 'C.U.S. FERRARA'),
(1292, 'C.S. CARABINIERI'),
(1298, 'SOCIETA'' SPORTIVA DDS'),
(1300, 'POLISP. EDERA FORLI'' A.D. - SEZ. TRIATHLON'),
(1305, 'BERIV MULTISPORT A.S.D.'),
(1317, 'ASD TRIATHLON PAVESE'),
(1328, 'TRETI TRIATHLON TEAM BRINDISI'),
(1340, 'ASS. POL. SINIS TRIATHLON ORISTANO'),
(1347, 'A.S.D. TRIATHLON BERGAMO'),
(1353, 'STEEL TRIATHLON A.S.D.'),
(1355, 'MAREMOLA TRIATHLON A.S.D.'),
(1357, 'ASS. SPORT. DIL. GREEN HILL'),
(1361, 'FREETIME TRIATHLON'),
(1366, 'CIRCOLO CANOTTIERI NAPOLI'),
(1370, 'A.S. SURVIVAL A.S.D.'),
(1371, 'PETER PAN TRIATHLON CIRCOLO MONTECITORIO'),
(1375, 'CUNEO TRIATHLON'),
(1382, 'C.C. NICOLAUS TRIATHLON'),
(1389, 'VIS CORTONA TRIATHLON A.S.D.'),
(1390, 'A.S.D. TRIATHLON SARONNO'),
(1402, 'GRUPPI SPORTIVI POLIZIA DI STATO - FIAMME ORO'),
(1406, 'PEPERONCINO TRIATHLON TEAM A.S.D.'),
(1429, 'FUEL TRIATHLON'),
(1440, 'PADOVANUOTO TRIATHLON A.S.D.'),
(1451, 'A.S. FREEZONE'),
(1452, 'BIANCHI 1770 MES 3 SPORTS A.S.D.'),
(1456, 'CIRCOLO SPORT.VO ADRIA GORIZIA TRIATH.'),
(1463, 'DIMENSIONE SPORT'),
(1475, 'C.U.S. BARI'),
(1478, 'A.S.D. DELFINO TRIATHLON CHIOGGIA'),
(1485, 'SPORTCLUB MERAN - SEZ.TRIATHLON'),
(1494, 'SBR3 A.S.D'),
(1495, 'ASV HOCHPUSTERTAL - ALTA PUSTERIA T. & SCHWIMMEN'),
(1496, 'KSV TRIATHLON'),
(1499, 'LAUFERCLUB BOZEN - SEZ. TRIATHLON'),
(1500, 'DOLOMITICA NUOTO C.T.T. A.S.D.'),
(1502, 'CENTRO ESTER S.S.D. SRL'),
(1509, 'SPORTING CLUB CAPANNE'),
(1510, 'PESCHIERA TRIATHLON A.S.D.'),
(1511, 'MULTISPORT CATANIA A.S.D.'),
(1514, 'VTB ATOMICA TRIATHLON A.S.D.'),
(1517, 'VILLACIDRO TRIATHLON A.S.D.'),
(1520, 'FLIPPER TRIATHLON ASCOLI A.S.D.'),
(1523, 'FREESTYLE TRIATHLON VALDINIEVOLE A.S.D.'),
(1526, '4 MORI TRIATHLON LIVORNO A.S.D.'),
(1533, 'SOCIETA'' ATLETICA BELLINZAGO A.S.D.'),
(1537, 'U.C. SANGIULIANESE'),
(1540, 'ASD TEAM HIBISCUS'),
(1545, 'VENEZIA TRIATHLON A.S.D.'),
(1554, 'VALLE GESSO SPORT'),
(1572, 'TRIATHLON FAENZA TEAM MULTISPORT'),
(1577, 'SDS SPECIALISTI DELLO SPORT'),
(1585, 'NUOTO LIVORNO TRIATHLON SSD'),
(1586, 'A.S.D. VICENZA TRIATHLON'),
(1587, 'TEAM UDINE TRIATHLON A.S.D.'),
(1589, 'JUNIOR CLUB A.S.D.'),
(1590, 'PIANETA ACQUA S.S.D.'),
(1596, 'C.U.S. CATANIA'),
(1597, 'ESSECI NUOTO A.S.D.'),
(1605, 'AQUARIUS A.S.D.'),
(1608, 'OLIMPICAORLE A.S.D.'),
(1614, 'LEGA CICLISTICA BRUGHERIO 2 A.S.D.'),
(1615, 'A.D. GRUPPO CICLISTICO FERALPI'),
(1616, 'TRISPORTS.IT TEAM A.S.D.'),
(1621, 'TRIATHLON TEAM SASSARI A.S.D.'),
(1624, 'SALENTO TRIATHLON A.S.D.'),
(1625, '1A MISTRAL TRIATHLON A.S.D.'),
(1626, 'LA FENICE TRIATHLON A.S.D.'),
(1632, 'ASV SCHWIMMCLUB BRIXEN - ASD BRESSANONE NUOTO'),
(1634, 'TRIATHLON DUATHLON CREMONA'),
(1635, 'ISLAND TEAM ASD'),
(1636, 'TRIATHLON AUTONOSATE A.S.D.'),
(1637, 'CIRCOLO CANOTTIERI ANIENE ASD'),
(1638, 'A.S.D. ERMES CAMPANIA'),
(1640, 'TRIATHLON 3V A.S.D.'),
(1642, '33 TRENTINI TRIATHLON ASD'),
(1647, 'A.S.D. IL DELFINO SPOLETO'),
(1651, 'A.S.D. CIRCOLO NAUTICO POSILLIPO'),
(1653, 'POLISPORTIVA PORTA SARAGOZZA'),
(1654, 'A.S.D. ZENA TRI TEAM'),
(1655, 'ASD TRIIRON'),
(1656, 'ASD AETHALIA TRIATHLON'),
(1659, 'A TEAM TRIATHLON ASD'),
(1660, 'ASD ASI TRIATHLON NOALE'),
(1665, 'S.S.D. A. SCHIANTARELLI ASOLA SRL'),
(1671, 'ASD POOL SPORT TRIESTE'),
(1672, 'ASD JUNIOR BRINDISI'),
(1674, 'POL. SAN VITO SOCIETA'' COOPERATIVA'),
(1677, 'A.S.D. FREE TRIATHLON'),
(1680, 'ATLETICA MDS PANARIAGROUP ASD'),
(1684, 'CENTRO UNIVERSITARIO SPORTIVO DI BRESCIA'),
(1686, 'TOP TRIATHLON FANO ASD'),
(1689, 'ASD 3.4 FUN'),
(1691, 'A.S.D. NUOTO LUGO'),
(1694, 'FUMANE TRIATHLON ASD'),
(1698, 'FUNSPORT SSD SRL'),
(1706, 'ATLAS ASD'),
(1708, 'TRIATHLON TEAM RAVENNA'),
(1716, 'ASD IL GABBIANO NAPOLI'),
(1719, 'A.S.D. ATLETICA LAMBRO MILANO'),
(1724, 'LUCCA TRIATHLON ASD'),
(1727, 'A.S.D. DA PAURA'),
(1728, 'A.S.D. METAL ROOF'),
(1730, 'A.S.D. POLISPORTIVA FOSSALTINA'),
(1731, 'CANOTTIERI MINCIO GRUPPI SPORTIVI S.S.D.'),
(1735, 'COLLI CICLI VELO SPORT CARPI A.S.D.'),
(1740, 'GSA BERNER ZEROWIND'),
(1741, 'A.S.D. RHODIGIUM TEAM'),
(1747, 'ATLETICA AVIS CASTEL SAN PIETRO A.S.D.'),
(1748, 'TRIATHLON TEAM RICCIONE'),
(1750, 'A.S.D. TRIATHLON BORDIGHERA'),
(1752, 'DUE PONTI S.S.D A.R.L.'),
(1755, 'A.S.D. ICE CLUB COMO'),
(1756, 'SOCIETA'' NUOTO CASTIGLIONE'),
(1757, '3 LIFE A.S.D.'),
(1760, 'NOLIMITSFRIENDS'),
(1762, 'ASD ESCAPE TO TUSCANY TRIATHLON'),
(1763, 'HYPPO KAMPOS TRIATHLON ASD'),
(1766, 'POL. PHISIO SPORT LAB A.S.D.'),
(1767, 'STAFFORA TRIATHLON A.S.D.'),
(1768, 'A.S.D. GUIDA SICURA'),
(1769, 'SSD MULTISPORT A R.L.'),
(1770, 'A3 ASS.NE SPORT.VA DIL.CA'),
(1771, 'NJOY TRIATHLON VARESE'),
(1773, 'OXYGEN TRIATHLON A.S.D.'),
(1774, 'SWIM BIKE RUN 3ATHLON ASD'),
(1775, 'LIVORNO TRIATHLON ASD'),
(1776, 'TRIATHLON GROSSETO A.S.D.'),
(1779, 'FREE MIND TEAM RAID MULTISPORT ADVENTURE ASD'),
(1781, 'A.S.D. TEAM TERMOLI TRIATHLON'),
(1782, 'A.S.D. START & GO'),
(1783, 'A.S.D. EASYTRE'),
(1784, 'A.S.D. TRIATHLON MONTI LEPINI'),
(1786, 'A.S.D. AREZZO NUOTO'),
(1787, 'OLIMPIC SWIM PRO ASD'),
(1789, 'ITALIAN TRIATHLON A.S.D.'),
(1792, 'ASD TEAM UCSA'),
(1794, 'SSD G.P. MODUGNO SRL'),
(1795, 'A.S.D. VIPP TRIATHLON CLUB'),
(1799, 'A.S.D. BARLETTA SPORTIVA'),
(1801, 'CUS CASERTA A.S.D.'),
(1803, 'G.F. PINARELLO SSD'),
(1806, 'A.S.D. FERSEN TRIATHLON'),
(1807, 'LEONARDO TRIATHLON MILAZZO A.S.D.'),
(1809, 'VILLA ADA GREEN RUNNER'),
(1813, 'ASD ATHON'),
(1814, 'ASD DORIA NUOTO 2000 LOANO'),
(1818, 'A.S.D. ATLANTIDE'),
(1819, 'AQUATICA TORINO S.S.D. R.L.'),
(1820, 'CUSIOCUP A.S.D.'),
(1821, 'A.S.D. F.C. PORTO 85 POLISPORTIVA'),
(1825, 'MODENA TRIATHLON ASD'),
(1826, 'A.S.D. HAPPY RUNNER CLUB'),
(1828, 'A.S.D. AIRONE TRIATHLON'),
(1829, 'CDP PERUGIA TRIATHLON'),
(1831, 'A.D. POLISPORTIVA ODYSSEUS MESSINA'),
(1832, 'ASD TRIATHLON MOTIVATION'),
(1835, 'A.S.D. 2SLOW'),
(1838, 'SARDEGNA KARALIS SPORT'),
(1839, 'WOMAN TRIATHLON ITALIA A.S.D.'),
(1841, 'A.S.D. FIT PROGRAM BY NAIADI'),
(1843, 'A.S.D. PRO PATRIA A.R.C. BUSTO ARSIZIO'),
(1844, 'A.S.D. G.S. WINNERBIKE GIRELLI CICLI DALLA ROSA MOBILI'),
(1849, 'EXTREMA A.S.D.'),
(1850, '47 ANNO DOMINI TRIATHLON ASD'),
(1851, 'A.S.D. NADIR ON THE ROAD PUTIGNANO'),
(1852, 'ROMA TRIATHLON A.S.D.'),
(1854, 'C.U.S. TORINO'),
(1856, 'GRUPPO POLISPORTIVO DILETTANTISTICO ASSEMINI'),
(1857, 'ASD TEAM ATHLETIC AMATORI MATERA'),
(1858, 'CENTRO NUOTO CEPAGATTI A.S.D.'),
(1860, 'INUIT TRIATHLON ABRUZZO'),
(1862, 'TRYLOGY A.S.D.'),
(1863, 'A.S.D. TRIEVOLUTION SPORT EVENTI'),
(1864, 'A.S.D. ACQUAZZURRA ATRI'),
(1867, 'CASTELFRANCO TRIATHLON A.S.D.'),
(1868, 'A.S.D. CANOTTIERI TICINO PAVIA'),
(1878, 'KINO MANA A.S.D.'),
(1879, 'TEAM MARBLEMAN A.S.D.'),
(1880, 'HARBOUR CLUB MILANO A.S.D.'),
(1881, 'ASD C.S.U. IDEATLETICA AURORA M'),
(1883, 'LOS TIGRES ASD'),
(1884, 'SPORTSLIFE A.S.D.'),
(1886, '707 S.S.D. A R.L.'),
(1887, 'PRATOGRANDE SPORT SSD SRL'),
(1888, 'SSV BRUNECK AMATEURSPORTVEREIN SEZ. TRIATHLON'),
(1889, 'A.S.D. ACQUAGYM'),
(1891, 'PODISTICA SOLIDARIETA'' ASD'),
(1893, 'A.S.D. MP LIFE TRIATHLON CATANIA'),
(1896, 'A.S.D. RUNNER VALBOSSA'),
(1897, 'ARIA SPORT S.S.D. A R.L.'),
(1898, 'MARCHE SPORT CLUB A.S.D.'),
(1902, 'S.S.D. CENTRO SPORTIVO SABINA TEVERE'),
(1903, 'A.S.D. VARANO LAKE'),
(1904, 'A.S.D. IL GREGGE RIBELLE SIENA'),
(1905, 'A.S.D. TEAM LADISPOLI CICLISMO TRIATHLON'),
(1906, 'A.S.D. MERIDIANA TRIATHLON TARANTO'),
(1907, 'A.S.D. TRIATHLON TRAIL TEAM VALTARO'),
(1908, 'SOGEIS SRL SPORTIVA DIL.CA'),
(1909, 'A.S.D. POLISPORTIVA COMUNALE RICCIONE'),
(1910, 'JALMICCO CORSE A.S.D.'),
(1912, 'A.S.D. POLISPORTIVA UISP VIVISPORT FOSSANO'),
(1914, 'CENTRO UNIVERSITARIO SPORTIVO DI PADOVA'),
(1915, 'A.S.D. REACTION'),
(1916, 'LEGNAGONUOTO S.S.D. A R.L.'),
(1918, 'MB TRIATHLON GARDA A.S.D.'),
(1920, 'ZONA CAMBIO TRIATHLON A.S.D.'),
(1921, 'A.S.D. ENNA TRIATHLON'),
(1922, 'NUOTO LORETO A.S.D.'),
(1923, 'A.S.D. ATL. LEG. E TRIATHLON CIVITANOVA TRACK CLUB'),
(1924, 'A.S.D. TRI TEAM SAVIGLIANO'),
(1925, 'A.S.D. PROGETTO VISTA TRIATHLON'),
(1927, 'PISA ROAD RUNNERS CLUB'),
(1928, 'OLIMPIA CAMERINO TRIATHLON A.S.D.'),
(1929, 'A.S.D. ANDIAMO'),
(1930, 'APD ANDREANA SACRA FAMIGLIA'),
(1931, 'CANAVESE TRIATHLON A.S.D.'),
(1933, 'OTRE'' S.S.D. A R.L.'),
(1935, 'NOVA TRIATHLON A.S.D.'),
(1937, 'A.S.D. CASSINIS CYCLING TEAM'),
(1938, 'T.T. CESENATICO A.S.D.'),
(1940, 'JUST TRAINING S.S.D. A R.L.'),
(1941, 'A.S.D. TEAM CICLISCOTTO POL. CICLISTICA ISCHIA'),
(1942, 'A.S.D. ATLETICA AVIS SAN SEPOLCRO'),
(1943, 'GESTISPORT SOC. COOP. SPORTIVA DIL.CA'),
(1944, 'NEL SINIS A.S.D.'),
(1945, 'A.P.D. DHEA SPORT'),
(1949, 'CSS S.R.L. COORDINAMENTO SERVIZI PER LO SPORT'),
(1955, 'ALPE ADRIA MULTISPORT A.S.D.'),
(1956, 'FORTE VILLAGE SPORTS ACADEMY'),
(1959, 'A.S.D. SARDEGNA SPORT ONLUS CAGLIARI'),
(1960, 'MERAVIGLIE TRIATHLON ASD'),
(1962, 'SPORT & WELLNESS SRL SSD'),
(1963, 'NUOTO ALTOPIANO 7C. TRIATHLON'),
(1964, 'ASD MARATONETI CITTADELLESI'),
(1965, 'BULLRING TRIATHLON'),
(1966, 'IRONMAX TRIATHLON TEAM A.S.D.'),
(1967, 'ATLETICA MANARA'),
(1968, 'PIACENZA TRIATHLON VIVO'),
(1969, 'ASD SPORTIAMO'),
(1970, 'ESC SSD SRL'),
(1971, 'ATLETICA IMOLA SACMI AVIS'),
(1972, 'B2K SWIM TEAM A.S.D.'),
(1973, 'SPORT & LIFE ASD'),
(1974, 'CIRCOLO CANOTTIERI PIEDILUCO ASD'),
(1975, 'FITNESS PER TERNI SSD'),
(1976, 'NO LIMITS SPORT A.S.D.'),
(1977, 'FORTE DEI MARMI TRIATHLON A.S.D.'),
(1978, 'TRI TEAM SPEZIA ASD'),
(1979, 'ASD TEAM SPORT ISOLA'),
(1980, 'A.S.D. CATANZARO NUOTO'),
(1981, 'CARRERA TRIATHLON A.S.D.'),
(1982, 'A.S.D. BUONCONSIGLIO NUOTO'),
(1983, 'TEAM DUEMME CORSE A.S.D.'),
(1985, 'A.S.D. O.A.S.I LAURA VICUNA'),
(1987, 'A.S.D. T.P. TEAM'),
(1988, 'A.S.D. VARESE TRIATHLON S.B.R.'),
(1989, 'A.S.D. RARI NANTES ADRIA MONFALCONE'),
(1990, 'HYDRO SPORT STEDA NUOTO SRL S.S.D.'),
(1991, 'TRILEDROENERGY A.S.D.'),
(1992, 'SPORT VILLAGE SSD A R.L. PESARO'),
(1993, 'A.S.D. TRIATHLON LIGNANO SABBIADORO'),
(1994, 'ASD TENNIS CLUB PALERMO 2'),
(1996, 'SPORTING CLUB BORGONUOVO SPD A R.L.'),
(1997, 'ASSOCIAZIONE SPORTIVA DILETTANTISTICA REGGIO EMILIA SPORT'),
(1998, 'GRUPPO SPORTIVO FANCELLO CICLI TERRANOVA ASD'),
(1999, 'ASD TEAM SPARTANS'),
(2000, 'A.S.D. VALTELLINA TRIATHLON'),
(2001, 'A.S.D. N.E.S.T.'),
(2002, 'ASD PROJECT ULTRAMAN'),
(2003, 'A.S.D. GRUPPO SPORTIVO MONDOBICI'),
(2004, 'ALL 4 CYCLING BDC-FORUM TEAM A.S.D.'),
(2005, 'A.S.D. ZEROTRENTA TRIATHLON'),
(2006, 'POLISPORTIVA CASA CULTURALE ASD'),
(2007, 'FORUM S.S.D'),
(2008, 'ASD AMICI DEL NUOTO DEI V.V.F. DI MODENA'),
(2009, 'FRATELLI ROTTA TRIATHLON CLUB ASD'),
(2010, 'C.S.D GROSSETO SPORT INSIEME'),
(2011, 'A.S.D PROMOSPORT G.S. ITALIA'),
(2012, 'A.P.D. BRIGANTI D''ABRUZZO TRIATHLON'),
(2013, 'A.P.D. ATLETICA MAZARA NUOVA GIOVENTU'),
(2014, 'CUS UNIVERSITA'' MESSINA ASD'),
(2015, 'VALXER TRIATHLON TEAM A.S.D.'),
(2016, 'ASD CHESINI'),
(2017, 'ALTAIR VILLAGE NUOTO E FITNESS SSD'),
(2018, 'A.S.D. FLOWERS TOWN'),
(2019, 'A.S.D. MSL TRIATHLON TEAM'),
(2020, 'ASD ATLETICA CASTELLANA'),
(2021, 'GRUPPO QUADRIFOGLIO S.S.D. A R.L.'),
(2022, 'SAN MARINO TRIATHLON ASD'),
(2023, 'ASD RUNNERS SALO'''),
(2024, 'RUN RAN RUN TRIATHLON ASD'),
(2025, 'A.S.D. TRISINNAI'),
(2026, 'CORONA D''ABRUZZO S.S.D. A R.L.'),
(2027, 'SKY LINE NUOTO S.S.D. A R.L.'),
(2028, 'MILLECULURE A.S.D.'),
(2029, 'IN SPORT SRL S.D.D.'),
(2030, 'IVREA TRIATHLON A.S.D.'),
(2031, 'A.S.D. KAS NUOTO'),
(2033, 'TEAM COMOBIKE ASD'),
(2034, 'SSD NUOTO GIOVINAZZO A R.L.');

-- --------------------------------------------------------

--
-- Struttura della tabella `squadra`
--

DROP TABLE IF EXISTS `squadra`;
CREATE TABLE IF NOT EXISTS `squadra` (
  `id` bigint(20) unsigned NOT NULL,
  `nome` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `squadra`
--

INSERT INTO `squadra` (`id`, `nome`) VALUES
(1, 'Ajeje Club'),
(2, 'Avis Sansepolcro'),
(3, 'EXTREME TEAM'),
(4, 'Pink Woman Triathlon'),
(5, 'WHY NOT');

-- --------------------------------------------------------

--
-- Struttura della tabella `tesseramento`
--

DROP TABLE IF EXISTS `tesseramento`;
CREATE TABLE IF NOT EXISTS `tesseramento` (
  `id` bigint(20) unsigned NOT NULL,
  `finoAl` datetime NOT NULL,
  `matricola` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stranieroSocieta` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stranieroStato` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `idTipoTesseramento` tinyint(3) unsigned NOT NULL,
  `idRichiestaTesseramento` bigint(11) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `tesseramento`
--

INSERT INTO `tesseramento` (`id`, `finoAl`, `matricola`, `stranieroSocieta`, `stranieroStato`, `idTipoTesseramento`, `idRichiestaTesseramento`) VALUES
(2, '2014-06-29 00:00:00', '1488204', NULL, NULL, 2, 2),
(3, '2014-06-29 00:00:00', '1487223', NULL, NULL, 2, 3),
(4, '2014-06-29 00:00:00', '1273497', NULL, NULL, 2, 4),
(5, '2014-06-29 00:00:00', NULL, NULL, NULL, 1, 5),
(6, '2014-06-29 00:00:00', NULL, NULL, NULL, 1, 6),
(7, '2014-06-29 00:00:00', '1487886', NULL, NULL, 2, 7),
(8, '2014-06-29 00:00:00', '1488123', NULL, NULL, 2, 8),
(9, '2014-06-29 00:00:00', '1488216', NULL, NULL, 2, 9),
(10, '2014-06-29 00:00:00', '1488189', NULL, NULL, 2, 10),
(11, '2014-06-29 00:00:00', '1488175', NULL, NULL, 2, 11),
(12, '2014-06-29 00:00:00', '1485203', NULL, NULL, 2, 12),
(13, '2014-06-29 00:00:00', NULL, NULL, NULL, 1, 13),
(14, '2014-06-29 00:00:00', '1165148', NULL, NULL, 2, 14),
(15, '2014-06-29 00:00:00', NULL, NULL, NULL, 1, 15),
(16, '2014-06-29 00:00:00', NULL, NULL, NULL, 1, 16),
(17, '2014-06-29 00:00:00', '1486333', NULL, NULL, 2, 17),
(18, '2014-06-29 00:00:00', '1059414', NULL, NULL, 2, 18),
(19, '2014-06-29 00:00:00', '1486200', NULL, NULL, 2, 19),
(20, '2014-06-29 00:00:00', NULL, NULL, NULL, 1, 20),
(21, '2014-06-29 00:00:00', '1484331', NULL, NULL, 2, 21),
(22, '2014-06-29 00:00:00', '1485048', NULL, NULL, 2, 22),
(23, '2014-06-29 00:00:00', '746977', NULL, NULL, 2, 23),
(24, '2014-06-29 00:00:00', '1486290', NULL, NULL, 2, 24),
(25, '2014-06-29 00:00:00', '1273021', NULL, NULL, 2, 25),
(26, '2014-06-29 00:00:00', '1488217', NULL, NULL, 2, 26),
(27, '2014-06-29 00:00:00', '1379006', NULL, NULL, 2, 27),
(28, '2014-06-29 00:00:00', '1487763', NULL, NULL, 2, 28),
(29, '2014-06-29 00:00:00', '1487964', NULL, NULL, 2, 29),
(30, '2014-06-29 00:00:00', '1485204', NULL, NULL, 2, 30),
(31, '2014-06-29 00:00:00', NULL, NULL, NULL, 1, 31),
(32, '2014-06-29 00:00:00', '1274788', NULL, NULL, 2, 32),
(33, '2014-06-29 00:00:00', '1488202', NULL, NULL, 2, 33),
(34, '2014-06-29 00:00:00', '1485049', NULL, NULL, 2, 34),
(35, '2014-06-29 00:00:00', '1273562', NULL, NULL, 2, 35),
(36, '2014-06-29 00:00:00', NULL, NULL, NULL, 1, 36),
(37, '2014-06-29 00:00:00', NULL, NULL, NULL, 1, 37),
(38, '2014-06-29 00:00:00', '1062904', NULL, NULL, 2, 38),
(39, '2014-06-29 00:00:00', '1274500', NULL, NULL, 2, 39),
(40, '2014-06-29 00:00:00', '746083', NULL, NULL, 2, 40),
(41, '2014-06-29 00:00:00', '1381590', NULL, NULL, 2, 41),
(42, '2014-06-29 00:00:00', '1272845', NULL, NULL, 2, 42),
(43, '2014-06-29 00:00:00', '1485054', NULL, NULL, 2, 43),
(44, '2014-06-29 00:00:00', '958995', NULL, NULL, 2, 44),
(45, '2014-06-29 00:00:00', '957580', NULL, NULL, 2, 45),
(46, '2014-06-29 00:00:00', '1381586', NULL, NULL, 2, 46),
(47, '2014-06-29 00:00:00', '1488056', NULL, NULL, 2, 47),
(48, '2014-06-29 00:00:00', '9813845', NULL, NULL, 2, 48),
(49, '2014-06-29 00:00:00', '1064649', NULL, NULL, 2, 49),
(50, '2014-06-29 00:00:00', '1169454', NULL, NULL, 2, 50),
(51, '2014-06-29 00:00:00', '1383111', NULL, NULL, 2, 51),
(52, '2014-06-29 00:00:00', '1379664', NULL, NULL, 2, 52),
(53, '2014-06-29 00:00:00', '1275604', NULL, NULL, 2, 53),
(54, '2014-06-29 00:00:00', '1380896', NULL, NULL, 2, 54),
(55, '2014-06-29 00:00:00', '1168453', NULL, NULL, 2, 55),
(56, '2014-06-29 00:00:00', '1272976', NULL, NULL, 2, 56),
(57, '2014-06-29 00:00:00', '1275901', NULL, NULL, 2, 57),
(58, '2014-06-29 00:00:00', NULL, NULL, NULL, 1, 58),
(59, '2014-06-29 00:00:00', NULL, NULL, NULL, 1, 59),
(60, '2014-06-29 00:00:00', NULL, NULL, NULL, 1, 60),
(61, '2014-06-29 00:00:00', '1484747', NULL, NULL, 2, 61),
(62, '2014-06-29 00:00:00', '1484333', NULL, NULL, 2, 62),
(63, '2014-06-29 00:00:00', '1487899', NULL, NULL, 2, 63),
(64, '2014-06-29 00:00:00', NULL, NULL, NULL, 1, 64),
(65, '2014-06-29 00:00:00', NULL, NULL, NULL, 1, 65),
(66, '2014-06-29 00:00:00', NULL, NULL, NULL, 1, 66),
(67, '2014-06-29 00:00:00', '1488068', NULL, NULL, 2, 67),
(68, '2014-06-29 00:00:00', '1488188', NULL, NULL, 2, 68),
(69, '2014-06-29 00:00:00', NULL, NULL, NULL, 1, 69),
(70, '2014-06-29 00:00:00', NULL, NULL, NULL, 1, 70),
(71, '2014-06-29 00:00:00', NULL, NULL, NULL, 1, 71),
(72, '2014-06-29 00:00:00', NULL, NULL, NULL, 1, 72);

-- --------------------------------------------------------

--
-- Struttura della tabella `tesseramento__societa_fitri`
--

DROP TABLE IF EXISTS `tesseramento__societa_fitri`;
CREATE TABLE IF NOT EXISTS `tesseramento__societa_fitri` (
  `idTesseramento` bigint(20) unsigned NOT NULL,
  `codiceSocietaFitri` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `tesseramento__societa_fitri`
--

INSERT INTO `tesseramento__societa_fitri` (`idTesseramento`, `codiceSocietaFitri`) VALUES
(54, 84),
(18, 1138),
(38, 1210),
(40, 1210),
(50, 1210),
(44, 1357),
(11, 1389),
(14, 1406),
(55, 1494),
(57, 1514),
(42, 1586),
(46, 1647),
(48, 1651),
(51, 1651),
(29, 1653),
(17, 1766),
(53, 1766),
(41, 1769),
(27, 1776),
(2, 1829),
(3, 1829),
(4, 1829),
(7, 1829),
(8, 1829),
(9, 1829),
(10, 1829),
(12, 1829),
(22, 1829),
(23, 1829),
(25, 1829),
(26, 1829),
(28, 1829),
(30, 1829),
(32, 1829),
(33, 1829),
(34, 1829),
(39, 1829),
(43, 1829),
(45, 1829),
(47, 1829),
(56, 1829),
(63, 1829),
(67, 1829),
(68, 1829),
(52, 1904),
(19, 1938),
(21, 1942),
(61, 1942),
(62, 1942),
(49, 2021),
(24, 2022),
(35, 2022);

-- --------------------------------------------------------

--
-- Struttura della tabella `tipo_gara`
--

DROP TABLE IF EXISTS `tipo_gara`;
CREATE TABLE IF NOT EXISTS `tipo_gara` (
  `id` tinyint(3) unsigned NOT NULL,
  `nome_it` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descrizione_it` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nome_en` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descrizione_en` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `tipo_gara`
--

INSERT INTO `tipo_gara` (`id`, `nome_it`, `descrizione_it`, `nome_en`, `descrizione_en`) VALUES
(1, 'Individuale', NULL, 'Individual', NULL),
(2, 'In squadra', NULL, 'Team', NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `tipo_iscrizione`
--

DROP TABLE IF EXISTS `tipo_iscrizione`;
CREATE TABLE IF NOT EXISTS `tipo_iscrizione` (
  `id` tinyint(3) unsigned NOT NULL,
  `nome_it` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descrizione_it` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nome_en` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descrizione_en` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `tipo_iscrizione`
--

INSERT INTO `tipo_iscrizione` (`id`, `nome_it`, `descrizione_it`, `nome_en`, `descrizione_en`) VALUES
(1, 'Iscrizione standard', NULL, 'Standard registration', NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `tipo_richiesta_tesseramento`
--

DROP TABLE IF EXISTS `tipo_richiesta_tesseramento`;
CREATE TABLE IF NOT EXISTS `tipo_richiesta_tesseramento` (
  `id` tinyint(3) unsigned NOT NULL,
  `nome_it` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descrizione_it` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nome_en` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descrizione_en` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `tipo_richiesta_tesseramento`
--

INSERT INTO `tipo_richiesta_tesseramento` (`id`, `nome_it`, `descrizione_it`, `nome_en`, `descrizione_en`) VALUES
(1, 'Tesseramento di Giornata', NULL, 'Day Pass', NULL),
(2, 'Tesseramento F.I.Tri', NULL, 'Italian Triathlon Federation (F.I.Tri) registration', NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `tipo_tesseramento`
--

DROP TABLE IF EXISTS `tipo_tesseramento`;
CREATE TABLE IF NOT EXISTS `tipo_tesseramento` (
  `id` tinyint(3) unsigned NOT NULL,
  `nome_it` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nome_en` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `tipo_tesseramento`
--

INSERT INTO `tipo_tesseramento` (`id`, `nome_it`, `nome_en`) VALUES
(1, 'Tesseramento di Giornata', 'Day Pass'),
(2, 'Tesseramento F.I.Tri', 'Italian Triathlon Federation (F.I.Tri) registration');

-- --------------------------------------------------------

--
-- Struttura della tabella `utente`
--

DROP TABLE IF EXISTS `utente`;
CREATE TABLE IF NOT EXISTS `utente` (
  `id` bigint(20) unsigned NOT NULL,
  `username` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gettoneAutenticazione` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gettoneAutenticazioneScadeIl` datetime DEFAULT NULL,
  `nome` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cognome` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sesso` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `natoIl` date DEFAULT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `eAmministratore` tinyint(1) NOT NULL,
  `facebookId` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `utente`
--

INSERT INTO `utente` (`id`, `username`, `password`, `gettoneAutenticazione`, `gettoneAutenticazioneScadeIl`, `nome`, `cognome`, `sesso`, `natoIl`, `email`, `telefono`, `eAmministratore`, `facebookId`) VALUES
(1, NULL, NULL, NULL, NULL, 'importazione dati 2014', 'importazione dati 2014', NULL, NULL, 'importazione dati 2014', NULL, 0, NULL),
(2, NULL, NULL, NULL, NULL, 'Francesco', 'Lombardi', 'M', '2014-06-29', 'lombardifrancesco73@libero.it', '3284310170', 0, NULL),
(3, NULL, NULL, NULL, NULL, 'Giovanni', 'Balucani', 'M', '2014-06-29', 'balucang@me.com', '3391697671', 0, NULL),
(4, NULL, NULL, NULL, NULL, 'Egidio', 'Tinarelli', 'M', '2014-06-29', 'egidio.tinarelli@gmail.com', '3489893399', 0, NULL),
(5, NULL, NULL, NULL, NULL, 'Matteo', 'Macaluso', 'M', '2014-06-29', 'curtielatek13@libero.it', '3395992448', 0, NULL),
(6, NULL, NULL, NULL, NULL, 'Marco', 'Nulli', 'M', '2014-06-29', 'micene22@hotmail.com', '3395281984', 0, NULL),
(7, NULL, NULL, NULL, NULL, 'Luca', 'Nobilini', 'M', '2014-06-29', 'nobilini@gmail.com', '3939624591', 0, NULL),
(8, NULL, NULL, NULL, NULL, 'Federico', 'Angelini Paroli', 'M', '2014-06-29', 'fede.angeliniparoli@agathos-ingegneria.it', '3498391644', 0, NULL),
(9, NULL, NULL, NULL, NULL, 'Fabio', 'Lai', 'M', '2014-06-29', 'fabiolai@ariamail.it', '3771807941', 0, NULL),
(10, NULL, NULL, NULL, NULL, 'Edoardo', 'Gonfiotti', 'M', '2014-06-29', 'edoardo.gonfiotti@gmail.com', '3336772487', 0, NULL),
(11, NULL, NULL, NULL, NULL, 'Gilberto', 'Milleri', 'M', '2014-06-29', 'gilberto.milleri@gmail.com', '3351218162', 0, NULL),
(12, NULL, NULL, NULL, NULL, 'Alessio', 'Mezzetti', 'M', '2014-06-29', 'alessiomezzetti@libero.it', '3474501465', 0, NULL),
(13, NULL, NULL, NULL, NULL, 'Stefano', 'Cruciani', 'M', '2014-06-29', 'stefanocruciani@iol.it', '3351449213', 0, NULL),
(14, NULL, NULL, NULL, NULL, 'Alessandro', 'Lopriore', 'M', '2014-06-29', 'a.lopriore.peperoncinoteam@gmail.com', '3926831321', 0, NULL),
(15, NULL, NULL, NULL, NULL, 'Elisabeth', 'Plaz', 'F', '2014-06-29', 'e.plaz@virgilio.it', '3486956046', 0, NULL),
(16, NULL, NULL, NULL, NULL, 'Paolo', 'Gualandi', 'M', '2014-06-29', 'paolovet77@gmail.com', '3470460632', 0, NULL),
(17, NULL, NULL, NULL, NULL, 'Marco', 'Cecchini', 'M', '2014-06-29', 'cecchini.marco@gmail.com', '3289220295', 0, NULL),
(18, NULL, NULL, NULL, NULL, 'Franco', 'Frenguelli', 'M', '2014-06-29', 'franco.frenguelli@cassedellumbria.it', '3397370632', 0, NULL),
(19, NULL, NULL, NULL, NULL, 'Alessandro', 'Berti', 'M', '2014-06-29', 'docberti@gmail.com', '3297916521', 0, NULL),
(20, NULL, NULL, NULL, NULL, 'Adriano', 'Chiacchieroni', 'M', '2014-06-29', 'adrianochiacchieroni@libero.it', '3933683466', 0, NULL),
(21, NULL, NULL, NULL, NULL, 'Giacomo', 'Gepponi', 'M', '2014-06-29', 'geppodroid@gmail.com', '3346484534', 0, NULL),
(22, NULL, NULL, NULL, NULL, 'Tommaso', 'Cardinali', 'M', '2014-06-29', 'tommasocardinali@libero.it', '3470959337', 0, NULL),
(23, NULL, NULL, NULL, NULL, 'Franco', 'Rastelli', 'M', '2014-06-29', 'eros_marsili@libero.it', '3407166038', 0, NULL),
(24, NULL, NULL, NULL, NULL, 'Davide', 'Bertuccini', 'M', '2014-06-29', 'store@foodscience.sm', '3334696055', 0, NULL),
(25, NULL, NULL, NULL, NULL, 'Mario', 'Pizzoferrato', 'M', '2014-06-29', 'mario.pizzoferrato@alice.it', '3284103929', 0, NULL),
(26, NULL, NULL, NULL, NULL, 'Raffaele', 'Mancinelli', 'M', '2014-06-29', 'r.mancinelli@libero.it', '3454169581', 0, NULL),
(27, NULL, NULL, NULL, NULL, 'Paolo', 'Merlini', 'M', '2014-06-29', 'merlipaol@hotmail.com', '3922879503', 0, NULL),
(28, NULL, NULL, NULL, NULL, 'Andrea', 'Severi', 'M', '2014-06-29', 'aseveri@libero.it', '3294377076', 0, NULL),
(29, NULL, NULL, NULL, NULL, 'Rene''', 'Abati', 'M', '2014-06-29', 'rene.abati@icloud.com', '3665695274', 0, NULL),
(30, NULL, NULL, NULL, NULL, 'Michele', 'Sberna', 'M', '2014-06-29', 'michele_sberna@libero.it', '3200654867', 0, NULL),
(31, NULL, NULL, NULL, NULL, 'Antonino', 'Panasiti', 'M', '2014-06-29', 'gimcocco@gmail.com', '3735500635', 0, NULL),
(32, NULL, NULL, NULL, NULL, 'Francesco', 'Armino', 'M', '2014-06-29', 'mito_ev@libero.it', '3385630172', 0, NULL),
(33, NULL, NULL, NULL, NULL, 'Claudio', 'Massetti', 'M', '2014-06-29', 'c.massetti@umbraacque.com', '3496309194', 0, NULL),
(34, NULL, NULL, NULL, NULL, 'Alessandro', 'Mezzetti', 'M', '2014-06-29', 'mealessandro@yahoo.it', '3314007645', 0, NULL),
(35, NULL, NULL, NULL, NULL, 'Andrea', 'Tamagnini', 'M', '2014-06-29', 'l.martini87@yahoo.com', '3355754962', 0, NULL),
(36, NULL, NULL, NULL, NULL, 'Anna', 'Bigozzi', 'F', '2014-06-29', 'anabig@libero.it', '3287648208', 0, NULL),
(37, NULL, NULL, NULL, NULL, 'Cristiano', 'Borgnini', 'M', '2014-06-29', 'c.borgnini@luigibacchi.it', '3491428617', 0, NULL),
(38, NULL, NULL, NULL, NULL, 'Enrico', 'Zucca', 'M', '2014-06-29', 'erick.1@hotmail.it', '3313657124', 0, NULL),
(39, NULL, NULL, NULL, NULL, 'Filippo', 'Guastella', 'M', '2014-06-29', 'bobo_3@hotmail.it', '3473602869', 0, NULL),
(40, NULL, NULL, NULL, NULL, 'Andrea', 'Banchelli', 'M', '2014-06-29', '1922andre@virgilio.it', '055588808', 0, NULL),
(41, NULL, NULL, NULL, NULL, 'Giorgio', 'Bertoli', 'M', '2014-06-29', 'gika-sub@hotmail.it', '3408775048', 0, NULL),
(42, NULL, NULL, NULL, NULL, 'Alessandro', 'Cernicchi', 'M', '2014-06-29', 'cernicchi@gmail.com', '3288257405', 0, NULL),
(43, NULL, NULL, NULL, NULL, 'Giovanni', 'Nardi Schultze', 'M', '2014-06-29', 'gnardi@schultze.it', '3498642929', 0, NULL),
(44, NULL, NULL, NULL, NULL, 'Luca', 'Bruno', 'M', '2014-06-29', 'lucabrunox@libero.it', '3381618331', 0, NULL),
(45, NULL, NULL, NULL, NULL, 'Filippo', 'Luccioli', 'M', '2014-06-29', 'lucciolif@tiscali.it', '3403424642', 0, NULL),
(46, NULL, NULL, NULL, NULL, 'Antonio', 'Mignozzetti', 'M', '2014-06-29', 'migno7@libero.it', '3313714304', 0, NULL),
(47, NULL, NULL, NULL, NULL, 'Tommaso', 'Costantini', 'M', '2014-06-29', 'tommaso1313@hotmail.com', '3474867193', 0, NULL),
(48, NULL, NULL, NULL, NULL, 'Marco', 'Paino', 'M', '2014-06-29', 'marcopaino@gmail.com', '3355866456', 0, NULL),
(49, NULL, NULL, NULL, NULL, 'Marco', 'Avallone', 'M', '2014-06-29', 'avallonem@gmail.com', '3771607158', 0, NULL),
(50, NULL, NULL, NULL, NULL, 'Andrea', 'Pucci', 'M', '2014-06-29', 'apucci@gmail.com', '3283170193', 0, NULL),
(51, NULL, NULL, NULL, NULL, 'Paolo', 'De Filippis', 'M', '2014-06-29', 'paolodefilippis14@libero.it', '3355398027', 0, NULL),
(52, NULL, NULL, NULL, NULL, 'Guido', 'Governi', 'M', '2014-06-29', 'guigov69@yahoo.it', '3289419604', 0, NULL),
(53, NULL, NULL, NULL, NULL, 'Giuseppe', 'Pedani', 'M', '2014-06-29', 'gpedan56@hotmail.it', '3285906359', 0, NULL),
(54, NULL, NULL, NULL, NULL, 'Andrea', 'Zuffi', 'M', '2014-06-29', 'andreazuffi@gmail.com', '3936087482', 0, NULL),
(55, NULL, NULL, NULL, NULL, 'Alessandro', 'Dalgas', 'M', '2014-06-29', 'alessandro.dalgas@gmail.com', '3358448599', 0, NULL),
(56, NULL, NULL, NULL, NULL, 'Eros', 'Marsili', 'M', '2014-06-29', 'eros_marsili@libero.it', '3407166038', 0, NULL),
(57, NULL, NULL, NULL, NULL, 'Iacopo', 'Gianassi', 'M', '2014-06-29', 'iacopo.gianassi@gmail.com', '3381707356', 0, NULL),
(58, NULL, NULL, NULL, NULL, 'Giorgio', 'Pallucco', 'M', '2014-06-29', 'giorgiopallucco@libero.it', '3297738724', 0, NULL),
(59, NULL, NULL, NULL, NULL, 'Riccardo', 'Langeli', 'M', '2014-06-29', 'riccardolangeli@virgilio.it', '3315793590', 0, NULL),
(60, NULL, NULL, NULL, NULL, 'Marco', 'De Angelis', 'M', '2014-06-29', 'marco.deajeje@gmail.com', '3495735299', 0, NULL),
(61, NULL, NULL, NULL, NULL, 'Massimo', 'Guerrini', 'M', '2014-06-29', 'guerrinimassimo78@gmail.com', '3333135506', 0, NULL),
(62, NULL, NULL, NULL, NULL, 'Giacomo', 'Dini', 'M', '2014-06-29', 'geppodroid@gmail.com', '3487818625', 0, NULL),
(63, NULL, NULL, NULL, NULL, 'Riccardo', 'Graziotti', 'M', '2014-06-29', 'riccardomtb@gmail.com', '3335993926', 0, NULL),
(64, NULL, NULL, NULL, NULL, 'Massimiliano', 'Minciotti', 'M', '2014-06-29', 'massimiliano.minciotti@gmail.com', '12345678', 0, NULL),
(65, NULL, NULL, NULL, NULL, 'Michele', 'Andreani', 'M', '2014-06-29', 'miky464@libero.it', '3498131880', 0, NULL),
(66, NULL, NULL, NULL, NULL, 'Alessio', 'Merolla', 'M', '2014-06-29', 'alessiomero@gmail.com', '3287025288', 0, NULL),
(67, NULL, NULL, NULL, NULL, 'Alessandra', 'Tortoioli', 'F', '2014-06-29', 'aleig73@libero.it', '3474700268', 0, NULL),
(68, NULL, NULL, NULL, NULL, 'Valentina', 'Lagana''', 'F', '2014-06-29', 'valelaghy@yahoo.it', '3289541791', 0, NULL),
(69, NULL, NULL, NULL, NULL, 'Anna', 'Tomassini', 'F', '2014-06-29', 'annatomassiniit@gmail.com', '3665651527', 0, NULL),
(70, NULL, NULL, NULL, NULL, 'Giancarlo', 'Galvanin', 'M', '2014-06-29', 'g.galvanin@agtingegneria.it', '3358350136', 0, NULL),
(71, NULL, NULL, NULL, NULL, 'Gabriele', 'Vannuccini', 'M', '2014-06-29', 'cannuccia.g@gmail.com', '3383868195', 0, NULL),
(72, NULL, NULL, NULL, NULL, 'Marco', 'Timi', 'M', '2014-06-29', 'marcotimi@libero.it', '330883008', 0, NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `verifica_pagamento`
--

DROP TABLE IF EXISTS `verifica_pagamento`;
CREATE TABLE IF NOT EXISTS `verifica_pagamento` (
  `id` bigint(20) unsigned NOT NULL,
  `idOrdine` bigint(20) unsigned NOT NULL,
  `idAmministratore` bigint(20) unsigned NOT NULL,
  `eseguitaIl` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `verifica_richiesta_tesseramento`
--

DROP TABLE IF EXISTS `verifica_richiesta_tesseramento`;
CREATE TABLE IF NOT EXISTS `verifica_richiesta_tesseramento` (
  `id` bigint(20) unsigned NOT NULL,
  `idAmministratore` bigint(20) unsigned NOT NULL,
  `idRichiestaTesseramento` bigint(20) unsigned NOT NULL,
  `eseguitaIl` datetime NOT NULL,
  `esito` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `versione_database`
--

DROP TABLE IF EXISTS `versione_database`;
CREATE TABLE IF NOT EXISTS `versione_database` (
  `versione` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `versione_database`
--

INSERT INTO `versione_database` (`versione`) VALUES
('1.11');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `abilitazione_modalita_pagamento`
--
ALTER TABLE `abilitazione_modalita_pagamento`
  ADD PRIMARY KEY (`idGara`,`idModalitaPagamento`,`finoAl`),
  ADD KEY `idModalitaPagamento` (`idModalitaPagamento`);

--
-- Indici per le tabelle `abilitazione_tipo_iscrizione`
--
ALTER TABLE `abilitazione_tipo_iscrizione`
  ADD PRIMARY KEY (`idGara`,`idTipoIscrizione`,`finoAl`),
  ADD KEY `idTipoIscrizione` (`idTipoIscrizione`);

--
-- Indici per le tabelle `abilitazione_tipo_richiesta_tesseramento`
--
ALTER TABLE `abilitazione_tipo_richiesta_tesseramento`
  ADD PRIMARY KEY (`idTipoRichiestaTesseramento`,`idGara`,`finoAl`),
  ADD KEY `idGara` (`idGara`);

--
-- Indici per le tabelle `adesione_personale`
--
ALTER TABLE `adesione_personale`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `categoriaFitri` (`categoriaFitri`),
  ADD KEY `idUtente` (`idUtente`);

--
-- Indici per le tabelle `adesione_personale__invito`
--
ALTER TABLE `adesione_personale__invito`
  ADD PRIMARY KEY (`idAdesionePersonale`,`codiceInvito`),
  ADD UNIQUE KEY `id_adesione_personale` (`idAdesionePersonale`),
  ADD UNIQUE KEY `codice_invito` (`codiceInvito`);

--
-- Indici per le tabelle `adesione_personale__squadra`
--
ALTER TABLE `adesione_personale__squadra`
  ADD PRIMARY KEY (`idAdesionePersonale`,`idSquadra`),
  ADD UNIQUE KEY `id_adesione_personale` (`idAdesionePersonale`),
  ADD KEY `id_squadra` (`idSquadra`);

--
-- Indici per le tabelle `categoria_fitri`
--
ALTER TABLE `categoria_fitri`
  ADD PRIMARY KEY (`nome`);

--
-- Indici per le tabelle `codice_conclusione_gara`
--
ALTER TABLE `codice_conclusione_gara`
  ADD PRIMARY KEY (`nome`);

--
-- Indici per le tabelle `documento`
--
ALTER TABLE `documento`
  ADD PRIMARY KEY (`nomeFile`,`idRichiestaTesseramento`),
  ADD KEY `idRichiestaTesseramento` (`idRichiestaTesseramento`);

--
-- Indici per le tabelle `gara`
--
ALTER TABLE `gara`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome` (`nome`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `tipo` (`idTipoGara`);

--
-- Indici per le tabelle `invito`
--
ALTER TABLE `invito`
  ADD PRIMARY KEY (`codice`),
  ADD KEY `idIscrizione` (`idIscrizione`);

--
-- Indici per le tabelle `iscrizione`
--
ALTER TABLE `iscrizione`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `idOrdine` (`idOrdine`),
  ADD KEY `idGara` (`idGara`);

--
-- Indici per le tabelle `iscrizione__adesione_personale`
--
ALTER TABLE `iscrizione__adesione_personale`
  ADD PRIMARY KEY (`idIscrizione`,`idAdesionePersonale`),
  ADD UNIQUE KEY `idIscrizione` (`idIscrizione`),
  ADD UNIQUE KEY `idAdesionePersonale` (`idAdesionePersonale`);

--
-- Indici per le tabelle `iscrizione__squadra`
--
ALTER TABLE `iscrizione__squadra`
  ADD PRIMARY KEY (`idIscrizione`,`idSquadra`),
  ADD UNIQUE KEY `idIscrizione` (`idIscrizione`),
  ADD UNIQUE KEY `idSquadra` (`idSquadra`);

--
-- Indici per le tabelle `modalita_pagamento`
--
ALTER TABLE `modalita_pagamento`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome_it` (`nome_it`),
  ADD UNIQUE KEY `nome_en` (`nome_en`);

--
-- Indici per le tabelle `ordine`
--
ALTER TABLE `ordine`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codice` (`id`),
  ADD KEY `idCliente` (`idCliente`),
  ADD KEY `idModalitaPagamento` (`idModalitaPagamento`);

--
-- Indici per le tabelle `richiesta_tesseramento`
--
ALTER TABLE `richiesta_tesseramento`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `idAdesionePersonale` (`idAdesionePersonale`),
  ADD KEY `idTipoRichiestaTesseramento` (`idTipoRichiestaTesseramento`);

--
-- Indici per le tabelle `risultato`
--
ALTER TABLE `risultato`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `codiceConclusioneGara` (`codiceConclusioneGara`),
  ADD KEY `idIscrizione` (`idIscrizione`);

--
-- Indici per le tabelle `societa_fitri`
--
ALTER TABLE `societa_fitri`
  ADD PRIMARY KEY (`codice`);

--
-- Indici per le tabelle `squadra`
--
ALTER TABLE `squadra`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Indici per le tabelle `tesseramento`
--
ALTER TABLE `tesseramento`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `idTipoTesseramento` (`idTipoTesseramento`),
  ADD KEY `idRichiestaTesseramento` (`idRichiestaTesseramento`);

--
-- Indici per le tabelle `tesseramento__societa_fitri`
--
ALTER TABLE `tesseramento__societa_fitri`
  ADD PRIMARY KEY (`idTesseramento`,`codiceSocietaFitri`),
  ADD UNIQUE KEY `id_tesseramento` (`idTesseramento`),
  ADD KEY `codice_societa_fitri` (`codiceSocietaFitri`);

--
-- Indici per le tabelle `tipo_gara`
--
ALTER TABLE `tipo_gara`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome_it` (`nome_it`),
  ADD UNIQUE KEY `nome_en` (`nome_en`);

--
-- Indici per le tabelle `tipo_iscrizione`
--
ALTER TABLE `tipo_iscrizione`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `tipo_richiesta_tesseramento`
--
ALTER TABLE `tipo_richiesta_tesseramento`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome_it` (`nome_it`),
  ADD UNIQUE KEY `nome_en` (`nome_en`);

--
-- Indici per le tabelle `tipo_tesseramento`
--
ALTER TABLE `tipo_tesseramento`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome_it` (`nome_it`),
  ADD UNIQUE KEY `nome_en` (`nome_en`);

--
-- Indici per le tabelle `utente`
--
ALTER TABLE `utente`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indici per le tabelle `verifica_pagamento`
--
ALTER TABLE `verifica_pagamento`
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `idOrdine` (`idOrdine`,`idAmministratore`,`eseguitaIl`);

--
-- Indici per le tabelle `verifica_richiesta_tesseramento`
--
ALTER TABLE `verifica_richiesta_tesseramento`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `idAmministratore` (`idAmministratore`),
  ADD KEY `idRichiestaTesseramento` (`idRichiestaTesseramento`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `adesione_personale`
--
ALTER TABLE `adesione_personale`
  MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=73;
--
-- AUTO_INCREMENT per la tabella `gara`
--
ALTER TABLE `gara`
  MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT per la tabella `iscrizione`
--
ALTER TABLE `iscrizione`
  MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=63;
--
-- AUTO_INCREMENT per la tabella `modalita_pagamento`
--
ALTER TABLE `modalita_pagamento`
  MODIFY `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT per la tabella `ordine`
--
ALTER TABLE `ordine`
  MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT per la tabella `richiesta_tesseramento`
--
ALTER TABLE `richiesta_tesseramento`
  MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=73;
--
-- AUTO_INCREMENT per la tabella `risultato`
--
ALTER TABLE `risultato`
  MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=576;
--
-- AUTO_INCREMENT per la tabella `squadra`
--
ALTER TABLE `squadra`
  MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT per la tabella `tesseramento`
--
ALTER TABLE `tesseramento`
  MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=73;
--
-- AUTO_INCREMENT per la tabella `tipo_gara`
--
ALTER TABLE `tipo_gara`
  MODIFY `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT per la tabella `tipo_iscrizione`
--
ALTER TABLE `tipo_iscrizione`
  MODIFY `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT per la tabella `tipo_richiesta_tesseramento`
--
ALTER TABLE `tipo_richiesta_tesseramento`
  MODIFY `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT per la tabella `tipo_tesseramento`
--
ALTER TABLE `tipo_tesseramento`
  MODIFY `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT per la tabella `utente`
--
ALTER TABLE `utente`
  MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=73;
--
-- AUTO_INCREMENT per la tabella `verifica_pagamento`
--
ALTER TABLE `verifica_pagamento`
  MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT per la tabella `verifica_richiesta_tesseramento`
--
ALTER TABLE `verifica_richiesta_tesseramento`
  MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT;
--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `abilitazione_modalita_pagamento`
--
ALTER TABLE `abilitazione_modalita_pagamento`
  ADD CONSTRAINT `abilitazione_modalita_pagamento_ibfk_1` FOREIGN KEY (`idGara`) REFERENCES `gara` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `abilitazione_modalita_pagamento_ibfk_2` FOREIGN KEY (`idModalitaPagamento`) REFERENCES `modalita_pagamento` (`id`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `abilitazione_tipo_iscrizione`
--
ALTER TABLE `abilitazione_tipo_iscrizione`
  ADD CONSTRAINT `abilitazione_tipo_iscrizione_ibfk_1` FOREIGN KEY (`idTipoIscrizione`) REFERENCES `tipo_iscrizione` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `abilitazione_tipo_iscrizione_ibfk_2` FOREIGN KEY (`idGara`) REFERENCES `gara` (`id`);

--
-- Limiti per la tabella `abilitazione_tipo_richiesta_tesseramento`
--
ALTER TABLE `abilitazione_tipo_richiesta_tesseramento`
  ADD CONSTRAINT `abilitazione_tipo_richiesta_tesseramento_ibfk_2` FOREIGN KEY (`idGara`) REFERENCES `gara` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `abilitazione_tipo_richiesta_tesseramento_ibfk_3` FOREIGN KEY (`idTipoRichiestaTesseramento`) REFERENCES `tipo_richiesta_tesseramento` (`id`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `adesione_personale`
--
ALTER TABLE `adesione_personale`
  ADD CONSTRAINT `adesione_personale_ibfk_1` FOREIGN KEY (`categoriaFitri`) REFERENCES `categoria_fitri` (`nome`) ON UPDATE CASCADE,
  ADD CONSTRAINT `adesione_personale_ibfk_3` FOREIGN KEY (`idUtente`) REFERENCES `utente` (`id`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `adesione_personale__invito`
--
ALTER TABLE `adesione_personale__invito`
  ADD CONSTRAINT `adesione_personale__invito_ibfk_1` FOREIGN KEY (`idAdesionePersonale`) REFERENCES `adesione_personale` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `adesione_personale__invito_ibfk_2` FOREIGN KEY (`codiceInvito`) REFERENCES `invito` (`codice`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `adesione_personale__squadra`
--
ALTER TABLE `adesione_personale__squadra`
  ADD CONSTRAINT `adesione_personale__squadra_ibfk_1` FOREIGN KEY (`idAdesionePersonale`) REFERENCES `adesione_personale` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `adesione_personale__squadra_ibfk_2` FOREIGN KEY (`idSquadra`) REFERENCES `squadra` (`id`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `documento`
--
ALTER TABLE `documento`
  ADD CONSTRAINT `documento_ibfk_1` FOREIGN KEY (`idRichiestaTesseramento`) REFERENCES `richiesta_tesseramento` (`id`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `gara`
--
ALTER TABLE `gara`
  ADD CONSTRAINT `gara_ibfk_1` FOREIGN KEY (`idTipoGara`) REFERENCES `tipo_gara` (`id`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `invito`
--
ALTER TABLE `invito`
  ADD CONSTRAINT `invito_ibfk_3` FOREIGN KEY (`idIscrizione`) REFERENCES `iscrizione` (`id`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `iscrizione`
--
ALTER TABLE `iscrizione`
  ADD CONSTRAINT `iscrizione_ibfk_1` FOREIGN KEY (`idOrdine`) REFERENCES `ordine` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `iscrizione_ibfk_2` FOREIGN KEY (`idGara`) REFERENCES `gara` (`id`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `iscrizione__squadra`
--
ALTER TABLE `iscrizione__squadra`
  ADD CONSTRAINT `iscrizione__squadra_ibfk_1` FOREIGN KEY (`idIscrizione`) REFERENCES `iscrizione` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `iscrizione__squadra_ibfk_2` FOREIGN KEY (`idSquadra`) REFERENCES `squadra` (`id`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `ordine`
--
ALTER TABLE `ordine`
  ADD CONSTRAINT `ordine_ibfk_1` FOREIGN KEY (`idCliente`) REFERENCES `utente` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `ordine_ibfk_2` FOREIGN KEY (`idModalitaPagamento`) REFERENCES `modalita_pagamento` (`id`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `richiesta_tesseramento`
--
ALTER TABLE `richiesta_tesseramento`
  ADD CONSTRAINT `richiesta_tesseramento_ibfk_2` FOREIGN KEY (`idAdesionePersonale`) REFERENCES `adesione_personale` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `richiesta_tesseramento_ibfk_3` FOREIGN KEY (`idTipoRichiestaTesseramento`) REFERENCES `tipo_richiesta_tesseramento` (`id`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `risultato`
--
ALTER TABLE `risultato`
  ADD CONSTRAINT `risultato_ibfk_1` FOREIGN KEY (`codiceConclusioneGara`) REFERENCES `codice_conclusione_gara` (`nome`) ON UPDATE CASCADE,
  ADD CONSTRAINT `risultato_ibfk_2` FOREIGN KEY (`idIscrizione`) REFERENCES `iscrizione` (`id`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `tesseramento`
--
ALTER TABLE `tesseramento`
  ADD CONSTRAINT `tesseramento_ibfk_3` FOREIGN KEY (`idRichiestaTesseramento`) REFERENCES `richiesta_tesseramento` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `tesseramento_ibfk_4` FOREIGN KEY (`idTipoTesseramento`) REFERENCES `tipo_tesseramento` (`id`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `tesseramento__societa_fitri`
--
ALTER TABLE `tesseramento__societa_fitri`
  ADD CONSTRAINT `tesseramento__societa_fitri_ibfk_1` FOREIGN KEY (`idTesseramento`) REFERENCES `tesseramento` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `tesseramento__societa_fitri_ibfk_2` FOREIGN KEY (`codiceSocietaFitri`) REFERENCES `societa_fitri` (`codice`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `verifica_richiesta_tesseramento`
--
ALTER TABLE `verifica_richiesta_tesseramento`
  ADD CONSTRAINT `verifica_richiesta_tesseramento_ibfk_1` FOREIGN KEY (`idAmministratore`) REFERENCES `utente` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `verifica_richiesta_tesseramento_ibfk_2` FOREIGN KEY (`idRichiestaTesseramento`) REFERENCES `richiesta_tesseramento` (`id`) ON UPDATE CASCADE;
  
-- =====> FINE ARUBA

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
