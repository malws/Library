-- phpMyAdmin SQL Dump
-- version 3.5.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Czas wygenerowania: 03 Cze 2016, 14:59
-- Wersja serwera: 5.5.17-log
-- Wersja PHP: 5.4.11

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Baza danych: `Biblioteka`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `bibliotekarz`
--

CREATE TABLE IF NOT EXISTS `bibliotekarz` (
  `id_bibliotekarz` int(6) NOT NULL AUTO_INCREMENT,
  `login_bibliotekarz` varchar(128) COLLATE utf8_polish_ci NOT NULL,
  `password` varchar(128) COLLATE utf8_polish_ci NOT NULL,
  `permission` tinyint(2) NOT NULL,
  `imie` text COLLATE utf8_polish_ci NOT NULL,
  `nazwisko` text COLLATE utf8_polish_ci NOT NULL,
  `adres` text COLLATE utf8_polish_ci NOT NULL,
  PRIMARY KEY (`id_bibliotekarz`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=28 ;

--
-- Zrzut danych tabeli `bibliotekarz`
--

INSERT INTO `bibliotekarz` (`id_bibliotekarz`, `login_bibliotekarz`, `password`, `permission`, `imie`, `nazwisko`, `adres`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 2, 'Jan', 'Nowak', 'ul.Bananowa 2'),
(27, '001b', 'a7d5cc3c5cd8c093486fed88f61f2780', 1, 'Tadeusz', 'Sosna', 'ul. Amarantowa 7, 34-435 Kraków');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `czytelnik`
--

CREATE TABLE IF NOT EXISTS `czytelnik` (
  `id_czytelnik` int(11) NOT NULL AUTO_INCREMENT,
  `login_czytelnik` text COLLATE utf8_polish_ci NOT NULL,
  `password_czytelnik` text COLLATE utf8_polish_ci NOT NULL,
  `imie` text COLLATE utf8_polish_ci NOT NULL,
  `nazwisko` text COLLATE utf8_polish_ci NOT NULL,
  `adres` text COLLATE utf8_polish_ci NOT NULL,
  PRIMARY KEY (`id_czytelnik`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=11 ;

--
-- Zrzut danych tabeli `czytelnik`
--

INSERT INTO `czytelnik` (`id_czytelnik`, `login_czytelnik`, `password_czytelnik`, `imie`, `nazwisko`, `adres`) VALUES
(10, '001', 'dc5c7986daef50c1e02ab09b442ee34f', 'Zofia', 'Modrzewiecka', 'ul. Jagodowa 17e, 46-054, Katowice');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `egzemplarz`
--

CREATE TABLE IF NOT EXISTS `egzemplarz` (
  `id_egzemplarz` int(11) NOT NULL AUTO_INCREMENT,
  `id_ksiazka` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_egzemplarz`),
  KEY `id_ksiazka` (`id_ksiazka`),
  KEY `id_ksiazka_2` (`id_ksiazka`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=54 ;

--
-- Zrzut danych tabeli `egzemplarz`
--

INSERT INTO `egzemplarz` (`id_egzemplarz`, `id_ksiazka`, `status`) VALUES
(50, 25, 1),
(51, 26, 0),
(52, 27, 0);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `kara`
--

CREATE TABLE IF NOT EXISTS `kara` (
  `id_kara` int(11) NOT NULL AUTO_INCREMENT,
  `id_wypozyczenie` int(11) NOT NULL,
  `data_kary` date NOT NULL,
  `kwota` float NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_kara`),
  KEY `id_wypozyczenie` (`id_wypozyczenie`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `ksiazka`
--

CREATE TABLE IF NOT EXISTS `ksiazka` (
  `id_ksiazka` int(11) NOT NULL AUTO_INCREMENT,
  `autor` text COLLATE utf8_polish_ci NOT NULL,
  `tytul` text COLLATE utf8_polish_ci NOT NULL,
  `wydanie_ksiazki` int(11) NOT NULL,
  `strony_ksiazki` int(11) NOT NULL,
  `wydawnictwo` text COLLATE utf8_polish_ci NOT NULL,
  `gatunek` text COLLATE utf8_polish_ci NOT NULL,
  PRIMARY KEY (`id_ksiazka`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=29 ;

--
-- Zrzut danych tabeli `ksiazka`
--

INSERT INTO `ksiazka` (`id_ksiazka`, `autor`, `tytul`, `wydanie_ksiazki`, `strony_ksiazki`, `wydawnictwo`, `gatunek`) VALUES
(25, 'Isaacson Walter', 'Einstein. Jego życie, jego wszechświat', 1, 790, 'Wydawnictwo W.A.B.', 'Biografia'),
(26, 'Moffett Patricia', 'Połącz kropki. Niesamowite miejsca', 1, 96, 'Wydawnictwo MUZA S.A. ', 'Poradniki'),
(27, 'Przemysław Rudź', 'Atlas nieba. Przewodnik po gwiazdozbiorach', 1, 240, 'SBM Renata Gmitrzak', 'Leksykon');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `rezerwacja`
--

CREATE TABLE IF NOT EXISTS `rezerwacja` (
  `id_rezerwacja` int(11) NOT NULL AUTO_INCREMENT,
  `id_egzemplarz` int(11) NOT NULL,
  `id_czytelnik` int(11) NOT NULL,
  `data_poczatek` date NOT NULL,
  `data_koniec` date NOT NULL,
  PRIMARY KEY (`id_rezerwacja`),
  KEY `id_egzemplarz` (`id_egzemplarz`,`id_czytelnik`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=46 ;

--
-- Zrzut danych tabeli `rezerwacja`
--

INSERT INTO `rezerwacja` (`id_rezerwacja`, `id_egzemplarz`, `id_czytelnik`, `data_poczatek`, `data_koniec`) VALUES
(45, 50, 10, '2016-05-24', '2016-07-03');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `wypozyczenie`
--

CREATE TABLE IF NOT EXISTS `wypozyczenie` (
  `id_wypozyczenie` int(11) NOT NULL AUTO_INCREMENT,
  `id_egzemplarz` int(11) NOT NULL,
  `id_czytelnik` int(11) NOT NULL,
  `data_wypozyczenia` date NOT NULL,
  `data_zwrotu` date NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_wypozyczenie`),
  KEY `id_egzemplarz` (`id_egzemplarz`,`id_czytelnik`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=26 ;

--
-- Zrzut danych tabeli `wypozyczenie`
--

INSERT INTO `wypozyczenie` (`id_wypozyczenie`, `id_egzemplarz`, `id_czytelnik`, `data_wypozyczenia`, `data_zwrotu`, `status`) VALUES
(24, 50, 10, '2016-05-13', '2016-05-13', 1),
(25, 51, 10, '2016-05-13', '2016-05-13', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
