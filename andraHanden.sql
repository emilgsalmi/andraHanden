-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Värd: localhost:8889
-- Tid vid skapande: 16 jun 2023 kl 09:54
-- Serverversion: 5.7.39
-- PHP-version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databas: `andraHanden`
--

-- --------------------------------------------------------

--
-- Tabellstruktur `items`
--

CREATE TABLE `items` (
  `item_id` int(11) NOT NULL,
  `item_name` varchar(255) DEFAULT NULL,
  `seller_id` int(11) DEFAULT NULL,
  `sold` tinyint(4) DEFAULT '0',
  `sale_amount` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumpning av Data i tabell `items`
--

INSERT INTO `items` (`item_id`, `item_name`, `seller_id`, `sold`, `sale_amount`) VALUES
(1, 'Wornout Jeans Levi\'s', 1, 1, '250.00'),
(2, 'Trucker Cap', 2, 1, '100.00'),
(3, 'Sweatshirt with band logo', 3, 1, '150.00'),
(4, 'Poloshirt ', 2, 1, '150.00'),
(5, 'Adidas shoes st42', 1, 1, '400.00'),
(6, 'Wornout old Skool Vans in white', 4, 1, '300.00'),
(13, 'Bomber Jacket', 1, 1, '600.00'),
(16, 'Jeans Jacket', 7, 1, '550.00'),
(17, 'Leather Jacket', 2, 1, '1000.00'),
(30, 'Black T-Shirt', 3, 1, '100.00'),
(31, 'White T-Shirt', 12, 1, '100.00'),
(32, ' OverShirt Black', 13, 1, '350.00'),
(33, 'Nike', 14, 1, '350.00'),
(34, 'Black Hoodie', 13, 1, '450.00'),
(35, 'Tshirt', 15, 1, '50.00');

-- --------------------------------------------------------

--
-- Tabellstruktur `sellers`
--

CREATE TABLE `sellers` (
  `seller_id` int(11) NOT NULL,
  `seller_name` varchar(45) DEFAULT NULL,
  `total_sale_amount` decimal(10,2) DEFAULT NULL,
  `total_sold_items` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumpning av Data i tabell `sellers`
--

INSERT INTO `sellers` (`seller_id`, `seller_name`, `total_sale_amount`, `total_sold_items`) VALUES
(1, 'Markus Bolsson', '1250.00', 3),
(2, 'Charles Dacken', '1250.00', 3),
(3, 'Adam Adamo', '250.00', 2),
(4, 'Kevin Valentino', '300.00', 1),
(7, 'Emil Salami', '550.00', 1),
(12, 'Giovanni Rojas', '100.00', 1),
(13, 'John Strandberg', '800.00', 2),
(14, 'Alan Strandberg', '350.00', 1),
(15, 'Nicklas', '50.00', 1);

--
-- Index för dumpade tabeller
--

--
-- Index för tabell `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `FK_sellers_id` (`seller_id`);

--
-- Index för tabell `sellers`
--
ALTER TABLE `sellers`
  ADD PRIMARY KEY (`seller_id`);

--
-- AUTO_INCREMENT för dumpade tabeller
--

--
-- AUTO_INCREMENT för tabell `items`
--
ALTER TABLE `items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT för tabell `sellers`
--
ALTER TABLE `sellers`
  MODIFY `seller_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Restriktioner för dumpade tabeller
--

--
-- Restriktioner för tabell `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `FK_sellers_id` FOREIGN KEY (`seller_id`) REFERENCES `sellers` (`seller_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
