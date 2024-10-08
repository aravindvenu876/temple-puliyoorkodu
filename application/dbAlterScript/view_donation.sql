-- phpMyAdmin SQL Dump
-- version 4.8.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 21, 2019 at 01:52 PM
-- Server version: 10.1.31-MariaDB
-- PHP Version: 7.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `temple_software`
--

-- --------------------------------------------------------

--
-- Structure for view `view_donation`
--

CREATE VIEW `view_donation`  AS  select `tbl1`.`id` AS `id`,`tbl1`.`status` AS `status`,`tbl2`.`category` AS `category_eng`,`tbl3`.`category` AS `category_alt`,`tbl4`.`temple` AS `temple_eng`,`tbl5`.`temple` AS `temple_alt`,`tbl1`.`temple_id` AS `temple_id` from ((((`donation_category` `tbl1` join `donation_category_lang` `tbl2` on((`tbl2`.`donation_category_id` = `tbl1`.`id`))) join `donation_category_lang` `tbl3` on((`tbl3`.`donation_category_id` = `tbl1`.`id`))) join `temple_master_lang` `tbl4` on((`tbl4`.`temple_id` = `tbl1`.`temple_id`))) join `temple_master_lang` `tbl5` on((`tbl5`.`temple_id` = `tbl1`.`temple_id`))) where ((`tbl2`.`lang_id` = 1) and (`tbl3`.`lang_id` = 2) and (`tbl4`.`lang_id` = 1) and (`tbl5`.`lang_id` = 2) and (`tbl1`.`status` <> 2)) ;

--
-- VIEW  `view_donation`
-- Data: None
--

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
