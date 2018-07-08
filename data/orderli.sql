-- phpMyAdmin SQL Dump
-- version 4.2.12deb2+deb8u2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 15. Apr 2018 um 12:16
-- Server Version: 5.5.59-0+deb8u1
-- PHP-Version: 7.0.29-1~dotdeb+8.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: orderli
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle menu_categories
--

CREATE TABLE IF NOT EXISTS menu_categories (

  id int(11) NOT NULL auto_increment,
  name varchar(255) DEFAULT '' COLLATE utf8mb4_bin NOT NULL,
  description text DEFAULT '' COLLATE utf8mb4_bin NOT NULL,
  restaurant_id int(11) DEFAULT '0' NOT NULL,

  PRIMARY KEY (id)

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Daten für Tabelle menu_categories
--

INSERT INTO menu_categories (id, name, description, restaurant_id) VALUES
(1, 'Vorspeisen / Suppen', '', 2),
(2, 'Gebratener Reis', '', 2),
(3, 'Gebratene Nudeln', '', 2),
(4, 'Hühnerfleisch', '', 2),
(5, 'Schweinefleisch', '', 2),
(6, 'Rindfleisch', '', 2),
(7, 'Knusprige Ente', '', 2),
(8, 'Meeresfrüchte', '', 2),
(9, 'Thailändische Spezialitäten', '', 2),
(10, 'Vegetarische Gerichte', '', 2),
(11, 'Neue Gerichte', '', 2),
(12, 'Extras', '', 2),
(13, 'Dessert', '', 2),
(14, 'Getränke', '', 2);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle menu
--

CREATE TABLE IF NOT EXISTS menu (

  id int(11) NOT NULL auto_increment,
  menu_id varchar(255) DEFAULT '' COLLATE utf8_unicode_ci NOT NULL,

  # menu (1:1)
  menu_category int(11) DEFAULT '0' NOT NULL,

  name varchar(255) DEFAULT '' COLLATE utf8mb4_bin NOT NULL,
  description mediumtext COLLATE utf8mb4_bin NOT NULL,
  price decimal(6,2) DEFAULT '0.00' NOT NULL,

  # restaurants (1:1)
  restaurant_id int(11) DEFAULT '0' NOT NULL,

  PRIMARY KEY (id)

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Daten für Tabelle menu
--

INSERT INTO menu (id, menu_id, menu_category, name, description, price, restaurant_id) VALUES
(1, '1', 1, 'Salat', 'mit Verschiedenem asiatischem Gemüse u. Schrimps', '3.50', 2),
(2, '2', 1, 'Gebackene Wan-Tan', '6 Stk. Teigtaschen mit gem. Hackfleisch', '3.00', 2),
(3, '3', 1, 'Vegetarische Frühlingsrollen', 'mit Süßsauer (6Stk.)', '2.00', 2),
(4, '4', 1, 'Frühlingsrollen', 'mit versch. Gemüse und Schweinefleisch', '2.00', 2),
(5, '5', 1, 'Krabbenchips', '', '1.50', 2),
(6, '6', 1, 'Pekingsuppe', '(sauer und scharf)', '2.00', 2),
(7, '7', 1, 'Sauer-scharfe Suppe', 'mit Sojakeimen, Hühnerfleisch u. Zitronengras', '2.50', 2),
(8, '8', 1, 'Wan-Tan Suppe', 'mit Gemüse', '2.50', 2),
(9, '9', 1, 'Tom Yam Gung', 'Berühmte traditionelle Thai-Suppe m. Krabben 🌶', '3.50', 2),
(10, '10', 2, 'Gebratener Reis', 'mit Gemüse u. Eiern', '4.50', 2),
(11, '11', 2, 'Gebratener Reis', 'mit Hühnerfleisch, Gemüse u. Eiern', '5.00', 2),
(12, '12', 2, 'Gebratener Reis', 'mit Schweinefleisch, Gemüse u. Eiern', '5.00', 2),
(13, '13', 2, 'Gebratener Reis', 'mit Rindfleisch, Gemüse u. Eiern', '5.50', 2),
(14, '14', 2, 'Gebratener Reis', 'mit Hühnerfleisch, Shrimps, Gemüse u. Eiern', '7.00', 2),
(15, '15', 2, 'Gebratener Reis', 'mit Ente, Shrimps, Hühner-, Schweinefleisch, Gemüse u. Eiern', '7.50', 2),
(16, '16', 2, 'Gebratener Reis', 'mit Curry, Hühnerfleisch, Gemüse u. Eiern 🌶', '5.50', 2),
(17, '17', 2, 'Gebratener Reis', 'mit Curry, Schweinefleisch, Gemüse u. Eiern 🌶', '5.50', 2),
(18, '18', 2, 'Gebratener Reis', 'mit Curry, Rindfleisch, Gemüse u. Eiern 🌶', '6.00', 2),
(19, '19', 2, 'Nasi Goreng', 'gebratener Reis mit Curry, Hühnerfleisch, Shrimps, Gemüse u. Eiern (leicht scharf)', '7.50', 2),
(20, '20', 2, 'Gebratener Reis', 'mit Curry und Ente, Shrimps, Hühner-, Schweinefleisch, Gemüse, Eiern', '8.00', 2),
(21, '21', 3, 'Gebratene Nudeln', 'mit Gemüse u. Eiern', '4.50', 2),
(22, '22', 3, 'Gebratene Nudeln', 'mit Hühnerfleisch, Gemüse u. Eiern', '5.00', 2),
(23, '23', 3, 'Gebratene Nudeln', 'mit Schweinefleisch, Gemüse u. Eiern', '5.00', 2),
(24, '24', 3, 'Gebratene Nudeln', 'mit Rindfleisch, Gemüse u. Eiern', '5.50', 2),
(25, '25', 3, 'Gebratene Nudeln', 'mit Hühnerfleisch, Shrimps, Gemüse u. Eiern', '7.00', 2),
(26, '26', 3, 'Gebratene Nudeln', 'mit Ente, Shrimps, Hühner-, Schweinefleisch, Gemüse u. Eiern', '7.50', 2),
(27, '27', 3, 'Gebratene Nudeln', 'mit Curry, Hühnerfleisch, Gemüse u. Eiern 🌶', '5.50', 2),
(28, '28', 3, 'Gebratene Nudeln', 'mit Curry, Schweinefleisch, Gemüse u. Eiern 🌶', '5.50', 2),
(29, '29', 3, 'Gebratene Nudeln', 'mit Curry, Rindfleisch, Gemüse u. Eiern 🌶', '6.00', 2),
(30, '30', 3, 'Bami Goreng', 'gebratene Nudeln mit Curry, Hühnerfleisch, Shrimps, Gemüse u. Eiern (leicht scharf)', '7.50', 2),
(31, '31', 3, 'Gebratene Nudeln', 'mit Curry und Ente, Shrimps, Hühner-, Schweinefleisch, Gemüse, Eiern', '8.00', 2),
(32, '32', 4, 'Gebratenes Hühnerfleisch', 'mit Curry und Gemüse 🌶', '6.00', 2),
(33, '33', 4, 'Paniertes Hühnerbrustfilet', 'mit Süßsauer Soße, Gemüse', '6.00', 2),
(34, '34', 4, 'Gebratenes Hühnerbrustfilet', 'mit Brokkoli, Knoblauch, Zitronengraß und Gemüse 🌶', '6.50', 2),
(35, '35', 4, 'Paniertes Hühnerbrustfilet', 'mit Erdnuss Soße', '6.00', 2),
(36, '36', 4, 'Gebratenes Hühnerfleisch', 'mit Sa-Cha, Knoblauch u. Gemüse 🌶', '6.00', 2),
(37, '37', 4, 'Hühnerfleisch Chopsuey', 'mit versch. Gemüse', '6.00', 2),
(38, '38', 4, 'Gebratenes Hühnerfleisch', 'mit Ingwer und Gemüse 🌶', '6.50', 2),
(39, '39', 5, 'Gebratenes Schweinefleisch', 'mit Curry und Gemüse 🌶', '6.00', 2),
(40, '40', 5, 'Paniertes Schweinefleisch', 'mit Süßsauer Soße, Gemüse', '6.00', 2),
(41, '41', 5, 'Gebratenes Schweinefleisch', 'mit Brokkoli, Knoblauch, Zitronengraß und Gemüse 🌶', '6.50', 2),
(42, '42', 5, 'Gebratenes Schweinefleisch', 'mit Sa-Cha, Knoblauch u. Gemüse 🌶', '6.00', 2),
(43, '43', 5, 'Schweinefleisch Chopsuey', 'mit versch. Gemüse', '6.00', 2),
(44, '44', 5, 'Gebratenes Schweinefleisch', 'mit Ingwer und Gemüse 🌶', '6.50', 2),
(45, '45', 6, 'Gebratenes Rindfleisch', 'mit Sa-Cha, Knoblauch u. Gemüse 🌶', '6.50', 2),
(46, '46', 6, 'Gebratenes Rindfleisch', 'mit Curry und Gemüse 🌶', '6.50', 2),
(47, '47', 6, 'Gebratenes Rindfleisch', 'mit Zwiebeln, Knoblauch und Gemüse 🌶', '6.50', 2),
(48, '48', 6, 'Rindfleisch Chopsuey', 'mit versch. Gemüse', '6.50', 2),
(49, '49', 6, 'Gebratenes Rindfleisch', 'mit Ingwer und Gemüse 🌶', '7.00', 2),
(50, '50', 7, 'Knusprige Ente', 'mit Curry und Gemüse 🌶', '8.00', 2),
(51, '51', 7, 'Knusprige Ente', 'mit Ananas- Süßsauer soße u. Gemüse', '8.00', 2),
(52, '52', 7, 'Knusprige Ente', 'mit Erdnuss Soße und Gemüse', '8.00', 2),
(53, '53', 7, 'Ente Chopsuey', 'Ente knusprig mit versch. Gemüse', '8.00', 2),
(54, '54', 7, 'Knusprige Ente', 'mit Sa-Cha, Knoblauch und Gemüse 🌶', '8.00', 2),
(55, '55', 7, 'Knusprige Ente', 'mit rotem Thai-Curry Soße u. Gemüse 🌶', '8.50', 2),
(56, '56', 7, 'Knusprige Ente', 'mit Ingwer und Gemüse 🌶', '8.00', 2),
(57, '57', 8, 'Garnelen', '(paniert) mit Süßsauer Soße u. Gemüse', '9.50', 2),
(58, '58', 8, 'Garnelen', 'mit Curry und Gemüse 🌶', '9.50', 2),
(59, '59', 8, 'Garnelen', 'mit Sa-Cha, Knoblauch und Gemüse 🌶', '9.50', 2),
(60, '60', 8, 'Garnelen', 'mit Ingwer und Gemüse 🌶🌶', '10.00', 2),
(61, '61', 9, 'Gebratenes Hühnerfleisch 🌶', 'mit Pfeffer, Grünen Bohnen, Knoblauch u. Gemüse', '7.00', 2),
(62, '62', 9, 'Gebratenes Scheinefleisch 🌶', 'mit Pfeffer, Grünen Bohnen, Knoblauch u. Gemüse', '7.00', 2),
(63, '63', 9, 'Gebratenes Rinfleisch', 'mit versch. Gemüse, Brokkoli, Paprika, Chapignons in Austern Soße', '7.50', 2),
(64, '64', 9, 'Gebratenes Hühnerfleisch', 'mit rotem Thai-Curry,🌶 Zitronenblätter und Kokosmilch u. Gemüse', '7.50', 2),
(65, '65', 9, 'Gebratenes Schweinefleisch', 'mit rotem Thai-Curry, Zitronenblätter und Kokosmilch u. Gemüse 🌶', '7.50', 2),
(66, '66', 9, 'Gebratene Garnelen 🌶', 'mit Pfeffer, Grünen Bohnen, Knoblauch u. Gemüse', '9.50', 2),
(67, '67', 9, 'Gebratenes Rindfleisch', 'mit rotem Thai-Curry,🌶 Zitronenblätter und Kokosmilch u. Gemüse', '8.00', 2),
(68, '68', 9, 'Gebratener Tintenfisch 🌶', 'mit Bambus, Paprika, Zwiebeln, Knoblauch u. Gemüse', '7.00', 2),
(69, '69', 9, 'Gebratener Tintenfisch 🌶', 'mit Pfeffer, Grünen Bohnen, Knoblauch u. Gemüse', '7.00', 2),
(70, '70', 9, 'Gebratener Tintenfisch', 'mit Schrimps, Schweine-, und Hühnerfleisch, Gemüse 🌶🌶', '8.50', 2),
(71, '71', 10, 'Gebratenes Gemüse', 'mit Chinakohl, Brokkoli, Zitronengras u. Sellerie', '5.50', 2),
(72, '72', 10, 'Tofu', 'mit versch. Gemüse', '5.50', 2),
(73, '73', 10, 'Chopsuey Gemüse', '', '5.50', 2),
(74, '74', 10, 'Brokkoli', 'gebraten mit Ingwer', '5.50', 2),
(75, '75', 10, 'Tofu', 'gebraten mit Brokkoli, Bambus u. Curry 🌶', '6.00', 2),
(76, '76', 11, 'Vietnamiesische Frühlingsrollen', '(2 Stk.)', '3.00', 2),
(77, '77', 11, 'Knuspriges Hähnchen', 'mit Gemüse und süßsaure oder Erdnuss Soße, dazu Reis', '6.50', 2),
(78, '78', 11, 'Gebratene Nudeln', 'mit knusprigem Hähnchen u. süßsaure oder Erdnuss Soße', '6.50', 2),
(79, '79', 11, 'Asia-Nudelsuppe', 'mit Hähnchen oder Rindfleisch und Gemüse', '5.50', 2),
(80, '80', 11, 'Knusprige Ente', 'auf gebr. Nudeln mit Gemüse', '8.00', 2),
(81, '81', 11, 'Gebratene jap. Udon-Nudeln', 'mit Gemüse', '6.50', 2),
(82, '82', 11, 'Gebratene jap. Udon-Nudeln', 'mit Hühnerfleisch u. Gemüse', '7.50', 2),
(83, '83', 11, 'Gebratene Reisbandnudeln', 'mit Hühnerfleisch u. Gemüse', '6.50', 2),
(84, '84', 11, 'Gebratene Reisbandnudeln', 'mit Rindfleisch u. Gemüse', '7.50', 2),
(85, '85', 11, 'PHO VIETNAM', 'Traditionelle Vietnamesische Reisbandnudelsuppe mit exotischen Gewürzen, Frühlingszwiebeln, Koriander und Hühnerfleisch oder Rindfleisch', '7.50', 2),
(86, '', 12, 'Gebratener Reis', 'als Beilage statt Reis', '1.50', 2),
(87, '', 12, 'Gebratene Nudeln', 'als Beilage statt Reis', '1.50', 2),
(88, '', 12, 'Portion Erdnuss Soße', '', '0.50', 2),
(89, '', 12, 'Portion süßsaure Soße', '', '0.50', 2),
(90, '', 12, 'Portion Thai-Curry', '', '1.00', 2),
(91, '', 12, 'Portion gekochter Reis', '', '2.00', 2),
(92, 'D1', 13, 'Gebackene Banane', 'mit Honig', '2.50', 2),
(93, 'D1', 13, 'Gebackene Ananas', 'mit Honig', '2.50', 2),
(94, '', 14, 'Cola', 'Dose 0,33l', '1.50', 2),
(95, '', 14, 'Fanta', 'Dose 0,33l', '1.50', 2),
(96, '', 14, 'Sprite', 'Dose 0,33l', '1.50', 2),
(97, '', 14, 'Tafelwasser', 'Dose 0,33l', '1.50', 2),
(98, '', 14, 'Warsteiner', 'Flasche 0,50l', '2.00', 2),
(99, '', 14, 'Bitburger', 'Flasche 0,50l', '2.00', 2),
(100, '', 14, 'Kaffee', '', '1.50', 2),
(101, '', 14, 'Grapefruit Fruchtsaft', 'Dose 0,33l', '2.00', 2),
(102, '', 14, 'Zitronen Fruchtsaft', 'Dose 0,33l', '2.00', 2),
(103, '', 14, 'Orangen Fruchtsaft', 'Dose 0,33l', '2.00', 2);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle order_sessions
--

CREATE TABLE IF NOT EXISTS order_sessions (

  id int(11) NOT NULL auto_increment,
  hash varchar(255) DEFAULT '' NOT NULL,
  restaurant_id int(11) DEFAULT '0' NOT NULL,
  driver_name varchar(255) DEFAULT '' COLLATE utf8_unicode_ci NOT NULL,
  driver_mail varchar(255) DEFAULT '' COLLATE utf8_unicode_ci NOT NULL,
  recepient_mail varchar(255) DEFAULT '' COLLATE utf8_unicode_ci NOT NULL,
  init_time timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL,
  expire_time timestamp DEFAULT '0000-00-00 00:00:00' NOT NULL,
  closed bit DEFAULT 0 NOT NULL,

  PRIMARY KEY (id)

) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle orders
--

CREATE TABLE IF NOT EXISTS orders (

  id int(11) NOT NULL auto_increment,
  hash varchar(255) DEFAULT '' NOT NULL,
  menu_id text DEFAULT '' NOT NULL,
  orderer_name varchar(255) DEFAULT '' COLLATE utf8_unicode_ci NOT NULL,
  orderer_mail varchar(255) DEFAULT '' COLLATE utf8_unicode_ci NOT NULL,
  order_time timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL,
  extras varchar(255) DEFAULT '' COLLATE utf8_unicode_ci NOT NULL

  PRIMARY KEY (id)

) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle restaurants
--

CREATE TABLE IF NOT EXISTS restaurants (

  id int(11) NOT NULL auto_increment,
  name varchar(255) DEFAULT '' COLLATE utf8_unicode_ci NOT NULL,
  adr_street varchar(255) DEFAULT '' COLLATE utf8_unicode_ci NOT NULL,
  adr_number int(11) DEFAULT '0' NOT NULL,
  adr_zip int(11) DEFAULT '0' NOT NULL,
  adr_city varchar(255) DEFAULT '' COLLATE utf8_unicode_ci NOT NULL,

  PRIMARY KEY (id)

) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle restaurants
--

INSERT INTO restaurants (id, name, adr_street, adr_number, adr_zip, adr_city) VALUES
(1, 'Freestyle', 'Magnetic Peak', '', 96790, 'Hawaii'),
(2, 'Dong-Do', 'Johannes-Kepler-Straße', 15, 55129, 'Mainz');


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
