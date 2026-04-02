-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 01, 2026 at 12:09 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.5.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `heladeria`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('dgary-o-cache-spatie.permission.cache', 'a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:146:{i:0;a:4:{s:1:\"a\";i:1;s:1:\"b\";s:16:\"ViewAny:Category\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:1;a:4:{s:1:\"a\";i:2;s:1:\"b\";s:13:\"View:Category\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:2;a:4:{s:1:\"a\";i:3;s:1:\"b\";s:15:\"Create:Category\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:3;a:4:{s:1:\"a\";i:4;s:1:\"b\";s:15:\"Update:Category\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:4;a:4:{s:1:\"a\";i:5;s:1:\"b\";s:15:\"Delete:Category\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:5;a:4:{s:1:\"a\";i:6;s:1:\"b\";s:16:\"Restore:Category\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:6;a:4:{s:1:\"a\";i:7;s:1:\"b\";s:20:\"ForceDelete:Category\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:7;a:4:{s:1:\"a\";i:8;s:1:\"b\";s:23:\"ForceDeleteAny:Category\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:8;a:4:{s:1:\"a\";i:9;s:1:\"b\";s:19:\"RestoreAny:Category\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:9;a:4:{s:1:\"a\";i:10;s:1:\"b\";s:18:\"Replicate:Category\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:10;a:4:{s:1:\"a\";i:11;s:1:\"b\";s:16:\"Reorder:Category\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:11;a:4:{s:1:\"a\";i:12;s:1:\"b\";s:14:\"ViewAny:Coupon\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:12;a:4:{s:1:\"a\";i:13;s:1:\"b\";s:11:\"View:Coupon\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:13;a:4:{s:1:\"a\";i:14;s:1:\"b\";s:13:\"Create:Coupon\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:14;a:4:{s:1:\"a\";i:15;s:1:\"b\";s:13:\"Update:Coupon\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:15;a:4:{s:1:\"a\";i:16;s:1:\"b\";s:13:\"Delete:Coupon\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:16;a:4:{s:1:\"a\";i:17;s:1:\"b\";s:14:\"Restore:Coupon\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:17;a:4:{s:1:\"a\";i:18;s:1:\"b\";s:18:\"ForceDelete:Coupon\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:18;a:4:{s:1:\"a\";i:19;s:1:\"b\";s:21:\"ForceDeleteAny:Coupon\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:19;a:4:{s:1:\"a\";i:20;s:1:\"b\";s:17:\"RestoreAny:Coupon\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:20;a:4:{s:1:\"a\";i:21;s:1:\"b\";s:16:\"Replicate:Coupon\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:21;a:4:{s:1:\"a\";i:22;s:1:\"b\";s:14:\"Reorder:Coupon\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:22;a:4:{s:1:\"a\";i:23;s:1:\"b\";s:16:\"ViewAny:Customer\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:23;a:4:{s:1:\"a\";i:24;s:1:\"b\";s:13:\"View:Customer\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:24;a:4:{s:1:\"a\";i:25;s:1:\"b\";s:15:\"Create:Customer\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:25;a:4:{s:1:\"a\";i:26;s:1:\"b\";s:15:\"Update:Customer\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:26;a:4:{s:1:\"a\";i:27;s:1:\"b\";s:15:\"Delete:Customer\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:27;a:4:{s:1:\"a\";i:28;s:1:\"b\";s:16:\"Restore:Customer\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:28;a:4:{s:1:\"a\";i:29;s:1:\"b\";s:20:\"ForceDelete:Customer\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:29;a:4:{s:1:\"a\";i:30;s:1:\"b\";s:23:\"ForceDeleteAny:Customer\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:30;a:4:{s:1:\"a\";i:31;s:1:\"b\";s:19:\"RestoreAny:Customer\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:31;a:4:{s:1:\"a\";i:32;s:1:\"b\";s:18:\"Replicate:Customer\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:32;a:4:{s:1:\"a\";i:33;s:1:\"b\";s:16:\"Reorder:Customer\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:33;a:4:{s:1:\"a\";i:34;s:1:\"b\";s:16:\"ViewAny:Delivery\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:34;a:4:{s:1:\"a\";i:35;s:1:\"b\";s:13:\"View:Delivery\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:35;a:4:{s:1:\"a\";i:36;s:1:\"b\";s:15:\"Create:Delivery\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:36;a:4:{s:1:\"a\";i:37;s:1:\"b\";s:15:\"Update:Delivery\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:37;a:4:{s:1:\"a\";i:38;s:1:\"b\";s:15:\"Delete:Delivery\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:38;a:4:{s:1:\"a\";i:39;s:1:\"b\";s:16:\"Restore:Delivery\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:39;a:4:{s:1:\"a\";i:40;s:1:\"b\";s:20:\"ForceDelete:Delivery\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:40;a:4:{s:1:\"a\";i:41;s:1:\"b\";s:23:\"ForceDeleteAny:Delivery\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:41;a:4:{s:1:\"a\";i:42;s:1:\"b\";s:19:\"RestoreAny:Delivery\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:42;a:4:{s:1:\"a\";i:43;s:1:\"b\";s:18:\"Replicate:Delivery\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:43;a:4:{s:1:\"a\";i:44;s:1:\"b\";s:16:\"Reorder:Delivery\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:44;a:4:{s:1:\"a\";i:45;s:1:\"b\";s:13:\"ViewAny:Order\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:45;a:4:{s:1:\"a\";i:46;s:1:\"b\";s:10:\"View:Order\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:46;a:4:{s:1:\"a\";i:47;s:1:\"b\";s:12:\"Create:Order\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:47;a:4:{s:1:\"a\";i:48;s:1:\"b\";s:12:\"Update:Order\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:48;a:4:{s:1:\"a\";i:49;s:1:\"b\";s:12:\"Delete:Order\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:49;a:4:{s:1:\"a\";i:50;s:1:\"b\";s:13:\"Restore:Order\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:50;a:4:{s:1:\"a\";i:51;s:1:\"b\";s:17:\"ForceDelete:Order\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:51;a:4:{s:1:\"a\";i:52;s:1:\"b\";s:20:\"ForceDeleteAny:Order\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:52;a:4:{s:1:\"a\";i:53;s:1:\"b\";s:16:\"RestoreAny:Order\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:53;a:4:{s:1:\"a\";i:54;s:1:\"b\";s:15:\"Replicate:Order\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:54;a:4:{s:1:\"a\";i:55;s:1:\"b\";s:13:\"Reorder:Order\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:55;a:4:{s:1:\"a\";i:56;s:1:\"b\";s:22:\"ViewAny:PreparationLog\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:56;a:4:{s:1:\"a\";i:57;s:1:\"b\";s:19:\"View:PreparationLog\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:57;a:4:{s:1:\"a\";i:58;s:1:\"b\";s:21:\"Create:PreparationLog\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:58;a:4:{s:1:\"a\";i:59;s:1:\"b\";s:21:\"Update:PreparationLog\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:59;a:4:{s:1:\"a\";i:60;s:1:\"b\";s:21:\"Delete:PreparationLog\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:60;a:4:{s:1:\"a\";i:61;s:1:\"b\";s:22:\"Restore:PreparationLog\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:61;a:4:{s:1:\"a\";i:62;s:1:\"b\";s:26:\"ForceDelete:PreparationLog\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:62;a:4:{s:1:\"a\";i:63;s:1:\"b\";s:29:\"ForceDeleteAny:PreparationLog\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:63;a:4:{s:1:\"a\";i:64;s:1:\"b\";s:25:\"RestoreAny:PreparationLog\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:64;a:4:{s:1:\"a\";i:65;s:1:\"b\";s:24:\"Replicate:PreparationLog\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:65;a:4:{s:1:\"a\";i:66;s:1:\"b\";s:22:\"Reorder:PreparationLog\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:66;a:4:{s:1:\"a\";i:67;s:1:\"b\";s:15:\"ViewAny:Product\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:67;a:4:{s:1:\"a\";i:68;s:1:\"b\";s:12:\"View:Product\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:68;a:4:{s:1:\"a\";i:69;s:1:\"b\";s:14:\"Create:Product\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:69;a:4:{s:1:\"a\";i:70;s:1:\"b\";s:14:\"Update:Product\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:70;a:4:{s:1:\"a\";i:71;s:1:\"b\";s:14:\"Delete:Product\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:71;a:4:{s:1:\"a\";i:72;s:1:\"b\";s:15:\"Restore:Product\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:72;a:4:{s:1:\"a\";i:73;s:1:\"b\";s:19:\"ForceDelete:Product\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:73;a:4:{s:1:\"a\";i:74;s:1:\"b\";s:22:\"ForceDeleteAny:Product\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:74;a:4:{s:1:\"a\";i:75;s:1:\"b\";s:18:\"RestoreAny:Product\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:75;a:4:{s:1:\"a\";i:76;s:1:\"b\";s:17:\"Replicate:Product\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:76;a:4:{s:1:\"a\";i:77;s:1:\"b\";s:15:\"Reorder:Product\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:77;a:4:{s:1:\"a\";i:78;s:1:\"b\";s:16:\"ViewAny:Purchase\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:78;a:4:{s:1:\"a\";i:79;s:1:\"b\";s:13:\"View:Purchase\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:79;a:4:{s:1:\"a\";i:80;s:1:\"b\";s:15:\"Create:Purchase\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:80;a:4:{s:1:\"a\";i:81;s:1:\"b\";s:15:\"Update:Purchase\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:81;a:4:{s:1:\"a\";i:82;s:1:\"b\";s:15:\"Delete:Purchase\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:82;a:4:{s:1:\"a\";i:83;s:1:\"b\";s:16:\"Restore:Purchase\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:83;a:4:{s:1:\"a\";i:84;s:1:\"b\";s:20:\"ForceDelete:Purchase\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:84;a:4:{s:1:\"a\";i:85;s:1:\"b\";s:23:\"ForceDeleteAny:Purchase\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:85;a:4:{s:1:\"a\";i:86;s:1:\"b\";s:19:\"RestoreAny:Purchase\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:86;a:4:{s:1:\"a\";i:87;s:1:\"b\";s:18:\"Replicate:Purchase\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:87;a:4:{s:1:\"a\";i:88;s:1:\"b\";s:16:\"Reorder:Purchase\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:88;a:4:{s:1:\"a\";i:89;s:1:\"b\";s:14:\"ViewAny:Recipe\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:89;a:4:{s:1:\"a\";i:90;s:1:\"b\";s:11:\"View:Recipe\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:90;a:4:{s:1:\"a\";i:91;s:1:\"b\";s:13:\"Create:Recipe\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:91;a:4:{s:1:\"a\";i:92;s:1:\"b\";s:13:\"Update:Recipe\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:92;a:4:{s:1:\"a\";i:93;s:1:\"b\";s:13:\"Delete:Recipe\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:93;a:4:{s:1:\"a\";i:94;s:1:\"b\";s:14:\"Restore:Recipe\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:94;a:4:{s:1:\"a\";i:95;s:1:\"b\";s:18:\"ForceDelete:Recipe\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:95;a:4:{s:1:\"a\";i:96;s:1:\"b\";s:21:\"ForceDeleteAny:Recipe\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:96;a:4:{s:1:\"a\";i:97;s:1:\"b\";s:17:\"RestoreAny:Recipe\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:97;a:4:{s:1:\"a\";i:98;s:1:\"b\";s:16:\"Replicate:Recipe\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:98;a:4:{s:1:\"a\";i:99;s:1:\"b\";s:14:\"Reorder:Recipe\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:99;a:4:{s:1:\"a\";i:100;s:1:\"b\";s:12:\"ViewAny:Role\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:100;a:4:{s:1:\"a\";i:101;s:1:\"b\";s:9:\"View:Role\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:101;a:4:{s:1:\"a\";i:102;s:1:\"b\";s:11:\"Create:Role\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:102;a:4:{s:1:\"a\";i:103;s:1:\"b\";s:11:\"Update:Role\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:103;a:4:{s:1:\"a\";i:104;s:1:\"b\";s:11:\"Delete:Role\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:104;a:4:{s:1:\"a\";i:105;s:1:\"b\";s:12:\"Restore:Role\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:105;a:4:{s:1:\"a\";i:106;s:1:\"b\";s:16:\"ForceDelete:Role\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:106;a:4:{s:1:\"a\";i:107;s:1:\"b\";s:19:\"ForceDeleteAny:Role\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:107;a:4:{s:1:\"a\";i:108;s:1:\"b\";s:15:\"RestoreAny:Role\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:108;a:4:{s:1:\"a\";i:109;s:1:\"b\";s:14:\"Replicate:Role\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:109;a:4:{s:1:\"a\";i:110;s:1:\"b\";s:12:\"Reorder:Role\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:110;a:4:{s:1:\"a\";i:111;s:1:\"b\";s:18:\"ViewAny:StockCount\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:111;a:4:{s:1:\"a\";i:112;s:1:\"b\";s:15:\"View:StockCount\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:112;a:4:{s:1:\"a\";i:113;s:1:\"b\";s:17:\"Create:StockCount\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:113;a:4:{s:1:\"a\";i:114;s:1:\"b\";s:17:\"Update:StockCount\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:114;a:4:{s:1:\"a\";i:115;s:1:\"b\";s:17:\"Delete:StockCount\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:115;a:4:{s:1:\"a\";i:116;s:1:\"b\";s:18:\"Restore:StockCount\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:116;a:4:{s:1:\"a\";i:117;s:1:\"b\";s:22:\"ForceDelete:StockCount\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:117;a:4:{s:1:\"a\";i:118;s:1:\"b\";s:25:\"ForceDeleteAny:StockCount\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:118;a:4:{s:1:\"a\";i:119;s:1:\"b\";s:21:\"RestoreAny:StockCount\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:119;a:4:{s:1:\"a\";i:120;s:1:\"b\";s:20:\"Replicate:StockCount\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:120;a:4:{s:1:\"a\";i:121;s:1:\"b\";s:18:\"Reorder:StockCount\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:121;a:4:{s:1:\"a\";i:122;s:1:\"b\";s:14:\"ViewAny:Supply\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:122;a:4:{s:1:\"a\";i:123;s:1:\"b\";s:11:\"View:Supply\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:123;a:4:{s:1:\"a\";i:124;s:1:\"b\";s:13:\"Create:Supply\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:124;a:4:{s:1:\"a\";i:125;s:1:\"b\";s:13:\"Update:Supply\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:125;a:4:{s:1:\"a\";i:126;s:1:\"b\";s:13:\"Delete:Supply\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:126;a:4:{s:1:\"a\";i:127;s:1:\"b\";s:14:\"Restore:Supply\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:127;a:4:{s:1:\"a\";i:128;s:1:\"b\";s:18:\"ForceDelete:Supply\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:128;a:4:{s:1:\"a\";i:129;s:1:\"b\";s:21:\"ForceDeleteAny:Supply\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:129;a:4:{s:1:\"a\";i:130;s:1:\"b\";s:17:\"RestoreAny:Supply\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:130;a:4:{s:1:\"a\";i:131;s:1:\"b\";s:16:\"Replicate:Supply\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:131;a:4:{s:1:\"a\";i:132;s:1:\"b\";s:14:\"Reorder:Supply\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:132;a:4:{s:1:\"a\";i:133;s:1:\"b\";s:12:\"ViewAny:User\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:133;a:4:{s:1:\"a\";i:134;s:1:\"b\";s:9:\"View:User\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:134;a:4:{s:1:\"a\";i:135;s:1:\"b\";s:11:\"Create:User\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:135;a:4:{s:1:\"a\";i:136;s:1:\"b\";s:11:\"Update:User\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:136;a:4:{s:1:\"a\";i:137;s:1:\"b\";s:11:\"Delete:User\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:137;a:4:{s:1:\"a\";i:138;s:1:\"b\";s:12:\"Restore:User\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:138;a:4:{s:1:\"a\";i:139;s:1:\"b\";s:16:\"ForceDelete:User\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:139;a:4:{s:1:\"a\";i:140;s:1:\"b\";s:19:\"ForceDeleteAny:User\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:140;a:4:{s:1:\"a\";i:141;s:1:\"b\";s:15:\"RestoreAny:User\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:141;a:4:{s:1:\"a\";i:142;s:1:\"b\";s:14:\"Replicate:User\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:142;a:4:{s:1:\"a\";i:143;s:1:\"b\";s:12:\"Reorder:User\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:143;a:4:{s:1:\"a\";i:144;s:1:\"b\";s:15:\"View:Deliveries\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:144;a:4:{s:1:\"a\";i:145;s:1:\"b\";s:18:\"View:StatsOverview\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:145;a:4:{s:1:\"a\";i:146;s:1:\"b\";s:19:\"View:ChartsOverview\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}}s:5:\"roles\";a:2:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:11:\"super_admin\";s:1:\"c\";s:3:\"web\";}i:1;a:3:{s:1:\"a\";i:2;s:1:\"b\";s:10:\"Repartidor\";s:1:\"c\";s:3:\"web\";}}}', 1774998984);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Nieves de leche', 'Nieves hechas a partir de leche y frutas.', '2026-03-23 01:41:34', '2026-03-23 01:41:34'),
(2, 'Aguas Frescas', 'Aguas de diferentes sabores hechas apartir de fruta.', '2026-03-23 01:42:22', '2026-03-23 01:42:22');

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(120) DEFAULT NULL,
  `discount` int(11) NOT NULL,
  `points_price` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`id`, `name`, `description`, `discount`, `points_price`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Dia de las madres', 'Cupon Disponible para el día de las madres', 15, 45, 1, '2026-03-23 01:43:36', '2026-03-23 01:43:36'),
(2, 'Halloween', 'Promoción aterradora del 31%.', 31, 68, 1, '2026-04-01 03:13:55', '2026-04-01 03:13:55');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(10) NOT NULL,
  `address` text NOT NULL,
  `points` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `email`, `password`, `phone`, `address`, `points`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Pancho', 'pancho@gmail.com', '$2y$12$sZ5qzaZPJ5MW2HCuPlYPMu2jCr2Wq.2qGlbHWhVR6xL2.G7G3Z79.', '1234567890', '#Avenida Siempre Viva', 167, '2026-03-23 01:41:01', '2026-03-25 21:02:23', NULL),
(2, 'Petra', 'petra@gmail.com', '$2y$12$tlQVknUWqLAxSGLewNLU1OpCx7Nb2umRfJEsAVLjJsrxMLNNssE4.', '1234556789', 'Tlajomulco, las piñas #1241', 103, '2026-03-25 23:16:45', '2026-03-25 23:18:11', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `customer_coupons`
--

CREATE TABLE `customer_coupons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `coupon_id` bigint(20) UNSIGNED NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `discount` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer_coupons`
--

INSERT INTO `customer_coupons` (`id`, `customer_id`, `coupon_id`, `status`, `discount`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 0, 32, NULL, '2026-03-25 21:02:23');

-- --------------------------------------------------------

--
-- Table structure for table `deliveries`
--

CREATE TABLE `deliveries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `address` varchar(255) NOT NULL,
  `status` enum('pending','ready','completed','canceled','in_transit') NOT NULL DEFAULT 'pending',
  `total` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `deliveries`
--

INSERT INTO `deliveries` (`id`, `user_id`, `order_id`, `address`, `status`, `total`, `created_at`, `updated_at`) VALUES
(1, NULL, 1, 'Avenida siempre viva #1829', 'ready', 119.00, NULL, '2026-03-31 12:46:04'),
(2, NULL, 2, '#Tlajomuerto. Calle Piña ', 'pending', 70.00, NULL, '2026-03-31 12:30:51');

-- --------------------------------------------------------

--
-- Stand-in structure for view `drivers`
-- (See below for the actual view)
--
CREATE TABLE `drivers` (
`id` bigint(20) unsigned
,`name` varchar(255)
,`email` varchar(255)
,`password` varchar(255)
);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(87, '2026_03_05_012029_create_drivers_view', 1),
(88, '2026_03_29_044351_add_cashier_id_to_orders_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 2),
(2, 'App\\Models\\User', 3);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `status` enum('Pending','Canceled','Ready','Completed') NOT NULL DEFAULT 'Completed',
  `type` enum('in_store','delivery') NOT NULL DEFAULT 'in_store',
  `coupon_id` bigint(20) UNSIGNED DEFAULT NULL,
  `discount` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_id`, `subtotal`, `total`, `status`, `type`, `coupon_id`, `discount`, `created_at`, `updated_at`, `user_id`) VALUES
(1, 1, 175.00, 119.00, 'Pending', 'delivery', 1, 32, '2026-03-25 21:02:23', '2026-03-25 21:02:23', NULL),
(2, 2, 70.00, 70.00, 'Pending', 'delivery', NULL, 0, '2026-03-25 23:18:11', '2026-03-25 23:18:11', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `unit_price` decimal(10,2) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `unit_price`, `quantity`, `subtotal`, `created_at`, `updated_at`) VALUES
(1, 1, 2, NULL, 5, 175.00, '2026-03-25 21:02:23', '2026-03-25 21:02:23'),
(2, 2, 2, NULL, 2, 70.00, '2026-03-25 23:18:11', '2026-03-25 23:18:11');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'ViewAny:Category', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(2, 'View:Category', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(3, 'Create:Category', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(4, 'Update:Category', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(5, 'Delete:Category', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(6, 'Restore:Category', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(7, 'ForceDelete:Category', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(8, 'ForceDeleteAny:Category', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(9, 'RestoreAny:Category', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(10, 'Replicate:Category', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(11, 'Reorder:Category', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(12, 'ViewAny:Coupon', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(13, 'View:Coupon', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(14, 'Create:Coupon', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(15, 'Update:Coupon', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(16, 'Delete:Coupon', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(17, 'Restore:Coupon', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(18, 'ForceDelete:Coupon', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(19, 'ForceDeleteAny:Coupon', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(20, 'RestoreAny:Coupon', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(21, 'Replicate:Coupon', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(22, 'Reorder:Coupon', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(23, 'ViewAny:Customer', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(24, 'View:Customer', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(25, 'Create:Customer', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(26, 'Update:Customer', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(27, 'Delete:Customer', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(28, 'Restore:Customer', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(29, 'ForceDelete:Customer', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(30, 'ForceDeleteAny:Customer', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(31, 'RestoreAny:Customer', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(32, 'Replicate:Customer', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(33, 'Reorder:Customer', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(34, 'ViewAny:Delivery', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(35, 'View:Delivery', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(36, 'Create:Delivery', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(37, 'Update:Delivery', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(38, 'Delete:Delivery', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(39, 'Restore:Delivery', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(40, 'ForceDelete:Delivery', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(41, 'ForceDeleteAny:Delivery', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(42, 'RestoreAny:Delivery', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(43, 'Replicate:Delivery', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(44, 'Reorder:Delivery', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(45, 'ViewAny:Order', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(46, 'View:Order', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(47, 'Create:Order', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(48, 'Update:Order', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(49, 'Delete:Order', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(50, 'Restore:Order', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(51, 'ForceDelete:Order', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(52, 'ForceDeleteAny:Order', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(53, 'RestoreAny:Order', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(54, 'Replicate:Order', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(55, 'Reorder:Order', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(56, 'ViewAny:PreparationLog', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(57, 'View:PreparationLog', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(58, 'Create:PreparationLog', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(59, 'Update:PreparationLog', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(60, 'Delete:PreparationLog', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(61, 'Restore:PreparationLog', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(62, 'ForceDelete:PreparationLog', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(63, 'ForceDeleteAny:PreparationLog', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(64, 'RestoreAny:PreparationLog', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(65, 'Replicate:PreparationLog', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(66, 'Reorder:PreparationLog', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(67, 'ViewAny:Product', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(68, 'View:Product', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(69, 'Create:Product', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(70, 'Update:Product', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(71, 'Delete:Product', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(72, 'Restore:Product', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(73, 'ForceDelete:Product', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(74, 'ForceDeleteAny:Product', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(75, 'RestoreAny:Product', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(76, 'Replicate:Product', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(77, 'Reorder:Product', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(78, 'ViewAny:Purchase', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(79, 'View:Purchase', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(80, 'Create:Purchase', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(81, 'Update:Purchase', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(82, 'Delete:Purchase', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(83, 'Restore:Purchase', 'web', '2026-03-22 16:12:46', '2026-03-22 16:12:46'),
(84, 'ForceDelete:Purchase', 'web', '2026-03-22 16:12:47', '2026-03-22 16:12:47'),
(85, 'ForceDeleteAny:Purchase', 'web', '2026-03-22 16:12:47', '2026-03-22 16:12:47'),
(86, 'RestoreAny:Purchase', 'web', '2026-03-22 16:12:47', '2026-03-22 16:12:47'),
(87, 'Replicate:Purchase', 'web', '2026-03-22 16:12:47', '2026-03-22 16:12:47'),
(88, 'Reorder:Purchase', 'web', '2026-03-22 16:12:47', '2026-03-22 16:12:47'),
(89, 'ViewAny:Recipe', 'web', '2026-03-22 16:12:47', '2026-03-22 16:12:47'),
(90, 'View:Recipe', 'web', '2026-03-22 16:12:47', '2026-03-22 16:12:47'),
(91, 'Create:Recipe', 'web', '2026-03-22 16:12:47', '2026-03-22 16:12:47'),
(92, 'Update:Recipe', 'web', '2026-03-22 16:12:47', '2026-03-22 16:12:47'),
(93, 'Delete:Recipe', 'web', '2026-03-22 16:12:47', '2026-03-22 16:12:47'),
(94, 'Restore:Recipe', 'web', '2026-03-22 16:12:47', '2026-03-22 16:12:47'),
(95, 'ForceDelete:Recipe', 'web', '2026-03-22 16:12:47', '2026-03-22 16:12:47'),
(96, 'ForceDeleteAny:Recipe', 'web', '2026-03-22 16:12:47', '2026-03-22 16:12:47'),
(97, 'RestoreAny:Recipe', 'web', '2026-03-22 16:12:47', '2026-03-22 16:12:47'),
(98, 'Replicate:Recipe', 'web', '2026-03-22 16:12:47', '2026-03-22 16:12:47'),
(99, 'Reorder:Recipe', 'web', '2026-03-22 16:12:47', '2026-03-22 16:12:47'),
(100, 'ViewAny:Role', 'web', '2026-03-22 16:12:47', '2026-03-22 16:12:47'),
(101, 'View:Role', 'web', '2026-03-22 16:12:47', '2026-03-22 16:12:47'),
(102, 'Create:Role', 'web', '2026-03-22 16:12:47', '2026-03-22 16:12:47'),
(103, 'Update:Role', 'web', '2026-03-22 16:12:47', '2026-03-22 16:12:47'),
(104, 'Delete:Role', 'web', '2026-03-22 16:12:47', '2026-03-22 16:12:47'),
(105, 'Restore:Role', 'web', '2026-03-22 16:12:47', '2026-03-22 16:12:47'),
(106, 'ForceDelete:Role', 'web', '2026-03-22 16:12:47', '2026-03-22 16:12:47'),
(107, 'ForceDeleteAny:Role', 'web', '2026-03-22 16:12:47', '2026-03-22 16:12:47'),
(108, 'RestoreAny:Role', 'web', '2026-03-22 16:12:47', '2026-03-22 16:12:47'),
(109, 'Replicate:Role', 'web', '2026-03-22 16:12:47', '2026-03-22 16:12:47'),
(110, 'Reorder:Role', 'web', '2026-03-22 16:12:47', '2026-03-22 16:12:47'),
(111, 'ViewAny:StockCount', 'web', '2026-03-22 16:12:47', '2026-03-22 16:12:47'),
(112, 'View:StockCount', 'web', '2026-03-22 16:12:47', '2026-03-22 16:12:47'),
(113, 'Create:StockCount', 'web', '2026-03-22 16:12:47', '2026-03-22 16:12:47'),
(114, 'Update:StockCount', 'web', '2026-03-22 16:12:47', '2026-03-22 16:12:47'),
(115, 'Delete:StockCount', 'web', '2026-03-22 16:12:47', '2026-03-22 16:12:47'),
(116, 'Restore:StockCount', 'web', '2026-03-22 16:12:47', '2026-03-22 16:12:47'),
(117, 'ForceDelete:StockCount', 'web', '2026-03-22 16:12:47', '2026-03-22 16:12:47'),
(118, 'ForceDeleteAny:StockCount', 'web', '2026-03-22 16:12:47', '2026-03-22 16:12:47'),
(119, 'RestoreAny:StockCount', 'web', '2026-03-22 16:12:47', '2026-03-22 16:12:47'),
(120, 'Replicate:StockCount', 'web', '2026-03-22 16:12:47', '2026-03-22 16:12:47'),
(121, 'Reorder:StockCount', 'web', '2026-03-22 16:12:47', '2026-03-22 16:12:47'),
(122, 'ViewAny:Supply', 'web', '2026-03-22 16:12:47', '2026-03-22 16:12:47'),
(123, 'View:Supply', 'web', '2026-03-22 16:12:47', '2026-03-22 16:12:47'),
(124, 'Create:Supply', 'web', '2026-03-22 16:12:47', '2026-03-22 16:12:47'),
(125, 'Update:Supply', 'web', '2026-03-22 16:12:47', '2026-03-22 16:12:47'),
(126, 'Delete:Supply', 'web', '2026-03-22 16:12:47', '2026-03-22 16:12:47'),
(127, 'Restore:Supply', 'web', '2026-03-22 16:12:47', '2026-03-22 16:12:47'),
(128, 'ForceDelete:Supply', 'web', '2026-03-22 16:12:47', '2026-03-22 16:12:47'),
(129, 'ForceDeleteAny:Supply', 'web', '2026-03-22 16:12:47', '2026-03-22 16:12:47'),
(130, 'RestoreAny:Supply', 'web', '2026-03-22 16:12:47', '2026-03-22 16:12:47'),
(131, 'Replicate:Supply', 'web', '2026-03-22 16:12:47', '2026-03-22 16:12:47'),
(132, 'Reorder:Supply', 'web', '2026-03-22 16:12:47', '2026-03-22 16:12:47'),
(133, 'ViewAny:User', 'web', '2026-03-22 16:12:47', '2026-03-22 16:12:47'),
(134, 'View:User', 'web', '2026-03-22 16:12:47', '2026-03-22 16:12:47'),
(135, 'Create:User', 'web', '2026-03-22 16:12:47', '2026-03-22 16:12:47'),
(136, 'Update:User', 'web', '2026-03-22 16:12:47', '2026-03-22 16:12:47'),
(137, 'Delete:User', 'web', '2026-03-22 16:12:47', '2026-03-22 16:12:47'),
(138, 'Restore:User', 'web', '2026-03-22 16:12:47', '2026-03-22 16:12:47'),
(139, 'ForceDelete:User', 'web', '2026-03-22 16:12:47', '2026-03-22 16:12:47'),
(140, 'ForceDeleteAny:User', 'web', '2026-03-22 16:12:47', '2026-03-22 16:12:47'),
(141, 'RestoreAny:User', 'web', '2026-03-22 16:12:47', '2026-03-22 16:12:47'),
(142, 'Replicate:User', 'web', '2026-03-22 16:12:47', '2026-03-22 16:12:47'),
(143, 'Reorder:User', 'web', '2026-03-22 16:12:47', '2026-03-22 16:12:47'),
(144, 'View:Deliveries', 'web', '2026-03-22 16:12:47', '2026-03-22 16:12:47'),
(145, 'View:StatsOverview', 'web', '2026-03-22 16:12:47', '2026-03-22 16:12:47'),
(146, 'View:ChartsOverview', 'web', '2026-03-22 16:12:47', '2026-03-22 16:12:47');

-- --------------------------------------------------------

--
-- Table structure for table `preparation_logs`
--

CREATE TABLE `preparation_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `recipe_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `produced_quantity` decimal(8,2) NOT NULL DEFAULT 1.00,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `preparation_logs`
--

INSERT INTO `preparation_logs` (`id`, `recipe_id`, `user_id`, `produced_quantity`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1.00, NULL, '2026-03-23 02:00:21', '2026-03-23 02:00:21');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` text DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` decimal(10,3) NOT NULL,
  `points` int(11) NOT NULL,
  `expire` tinyint(1) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `image`, `category_id`, `price`, `stock`, `points`, `expire`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Nieve de fresa', 'images/01KMBHPRJ9HESHW8TBGTKDP8SV.jpg', 1, 32.00, 1.000, 50, 0, 1, '2026-03-23 01:47:09', '2026-03-23 02:00:21', NULL),
(2, 'Nieve de limon', 'images/01KMBSETFVMRBXYFPKRBVKE66A.jpg', 1, 35.00, 5.000, 27, 0, 1, '2026-03-23 04:08:12', '2026-03-25 23:18:11', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `total` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_supplies`
--

CREATE TABLE `purchase_supplies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `supplies_id` bigint(20) UNSIGNED NOT NULL,
  `purchase_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` decimal(8,2) NOT NULL,
  `subtotal` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `recipes`
--

CREATE TABLE `recipes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `produced_quantity` decimal(8,3) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `recipes`
--

INSERT INTO `recipes` (`id`, `name`, `description`, `product_id`, `produced_quantity`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Nieve de fresa -', 'Usar exclusivamente de sello negro. ', 1, 1.000, '2026-03-23 01:58:43', '2026-03-23 01:58:43', NULL),
(2, 'Nieve de limon', 'NIeve de limon siempre usar leche de sello negro. Nunca refrigerar los limones, usarse una vez comprados.', 2, 1.000, '2026-03-23 04:12:07', '2026-03-23 04:12:07', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `recipe_supplies`
--

CREATE TABLE `recipe_supplies` (
  `id` bigint(20) NOT NULL,
  `recipe_id` bigint(20) UNSIGNED NOT NULL,
  `supply_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(8,3) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `recipe_supplies`
--

INSERT INTO `recipe_supplies` (`id`, `recipe_id`, `supply_id`, `amount`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1.000, '2026-03-23 01:58:43', '2026-03-23 01:58:43'),
(2, 1, 3, 1.000, '2026-03-23 01:58:43', '2026-03-23 01:58:43'),
(3, 1, 2, 2.000, '2026-03-23 01:58:43', '2026-03-23 01:58:43'),
(4, 2, 4, 1.000, '2026-03-23 04:12:07', '2026-03-23 04:12:07'),
(5, 2, 1, 1.000, '2026-03-23 04:12:07', '2026-03-23 04:12:07'),
(6, 2, 3, 1.500, '2026-03-23 04:12:07', '2026-03-23 04:12:07');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'super_admin', 'web', '2026-03-22 16:12:17', '2026-03-22 16:12:17'),
(2, 'Repartidor', 'web', '2026-03-22 16:13:15', '2026-03-22 16:13:15');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(27, 1),
(28, 1),
(29, 1),
(30, 1),
(31, 1),
(32, 1),
(33, 1),
(34, 1),
(35, 1),
(36, 1),
(37, 1),
(38, 1),
(39, 1),
(40, 1),
(41, 1),
(42, 1),
(43, 1),
(44, 1),
(45, 1),
(45, 2),
(46, 1),
(47, 1),
(47, 2),
(48, 1),
(49, 1),
(50, 1),
(51, 1),
(52, 1),
(53, 1),
(54, 1),
(55, 1),
(56, 1),
(57, 1),
(58, 1),
(59, 1),
(60, 1),
(61, 1),
(62, 1),
(63, 1),
(64, 1),
(65, 1),
(66, 1),
(67, 1),
(68, 1),
(69, 1),
(70, 1),
(71, 1),
(72, 1),
(73, 1),
(74, 1),
(75, 1),
(76, 1),
(77, 1),
(78, 1),
(79, 1),
(80, 1),
(81, 1),
(82, 1),
(83, 1),
(84, 1),
(85, 1),
(86, 1),
(87, 1),
(88, 1),
(89, 1),
(90, 1),
(91, 1),
(92, 1),
(93, 1),
(94, 1),
(95, 1),
(96, 1),
(97, 1),
(98, 1),
(99, 1),
(100, 1),
(101, 1),
(102, 1),
(103, 1),
(104, 1),
(105, 1),
(106, 1),
(107, 1),
(108, 1),
(109, 1),
(110, 1),
(111, 1),
(112, 1),
(113, 1),
(114, 1),
(115, 1),
(116, 1),
(117, 1),
(118, 1),
(119, 1),
(120, 1),
(121, 1),
(122, 1),
(123, 1),
(124, 1),
(125, 1),
(126, 1),
(127, 1),
(128, 1),
(129, 1),
(130, 1),
(131, 1),
(132, 1),
(133, 1),
(133, 2),
(134, 1),
(135, 1),
(136, 1),
(137, 1),
(138, 1),
(139, 1),
(140, 1),
(141, 1),
(142, 1),
(143, 1),
(144, 1),
(145, 1),
(146, 1);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('fURqssa9HQODjxfky9wGAFToSAix8eetGjjzGXLJ', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'YTo3OntzOjY6Il90b2tlbiI7czo0MDoicHNIOEZ5ZWVZa0oxZmEzMmVzUklLTmNyV25LZ0s3bmd4ZUY1WjFyYyI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjE3OiJwYXNzd29yZF9oYXNoX3dlYiI7czo2NDoiNmY3YWQ4MWZmY2YzODhmYTYxNzA2ZGFkMTkyOWQ0YzE5MzMzMDk5NzA0MTVhNDg5YmM4MWNkMjI4ZGQ3Y2RkOCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9zdXBwbGllcyI7czo1OiJyb3V0ZSI7czozOToiZmlsYW1lbnQuYWRtaW4ucmVzb3VyY2VzLnN1cHBsaWVzLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo2OiJ0YWJsZXMiO2E6Njp7czo0MDoiZTc5M2EyNzlkNTZlNDUwNjA5NzU0MDIwZDYyN2JlZWNfY29sdW1ucyI7YTo1OntpOjA7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTM6ImN1c3RvbWVyLm5hbWUiO3M6NToibGFiZWwiO3M6NzoiQ2xpZW50ZSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjE7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6NToidG90YWwiO3M6NToibGFiZWwiO3M6NToiVG90YWwiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToyO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjY6InN0YXR1cyI7czo1OiJsYWJlbCI7czo2OiJTdGF0dXMiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTozO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEwOiJjcmVhdGVkX2F0IjtzOjU6ImxhYmVsIjtzOjEwOiJDcmVhdGVkIGF0IjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MDtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MTtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO2I6MTt9aTo0O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEwOiJ1cGRhdGVkX2F0IjtzOjU6ImxhYmVsIjtzOjEwOiJVcGRhdGVkIGF0IjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MDtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MTtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO2I6MTt9fXM6NDA6IjkzZmMyNTllMDA0NTlkMTBiNjJhOWQ0MDUwZjA2NGYxX2NvbHVtbnMiO2E6Njp7aTowO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjExOiJkcml2ZXIubmFtZSI7czo1OiJsYWJlbCI7czoxMDoiUmVwYXJ0aWRvciI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjE7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6NzoiYWRkcmVzcyI7czo1OiJsYWJlbCI7czo3OiJBZGRyZXNzIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MjthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo2OiJzdGF0dXMiO3M6NToibGFiZWwiO3M6NjoiU3RhdHVzIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MzthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo1OiJ0b3RhbCI7czo1OiJsYWJlbCI7czo1OiJUb3RhbCI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjQ7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTA6ImNyZWF0ZWRfYXQiO3M6NToibGFiZWwiO3M6MTA6IkNyZWF0ZWQgYXQiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjowO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjoxO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7YjoxO31pOjU7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTA6InVwZGF0ZWRfYXQiO3M6NToibGFiZWwiO3M6MTA6IlVwZGF0ZWQgYXQiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjowO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjoxO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7YjoxO319czo0MDoiZGRjMWQwOGViZWZhNjUyMjkwM2FiMWYzN2MzY2I4YWNfY29sdW1ucyI7YTozOntpOjA7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6NDoibmFtZSI7czo1OiJsYWJlbCI7czo0OiJOYW1lIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MTthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMDoiY3JlYXRlZF9hdCI7czo1OiJsYWJlbCI7czoxMDoiQ3JlYXRlZCBhdCI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjA7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjE7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtiOjE7fWk6MjthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMDoidXBkYXRlZF9hdCI7czo1OiJsYWJlbCI7czoxMDoiVXBkYXRlZCBhdCI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjA7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjE7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtiOjE7fX1zOjQwOiJkMWVlNmRhMzJhZDg1YzFjOGYxOTdkODI4YzAwZWEwYl9jb2x1bW5zIjthOjY6e2k6MDthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo0OiJuYW1lIjtzOjU6ImxhYmVsIjtzOjQ6Ik5hbWUiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToxO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjg6ImRpc2NvdW50IjtzOjU6ImxhYmVsIjtzOjg6IkRpc2NvdW50IjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MjthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMjoicG9pbnRzX3ByaWNlIjtzOjU6ImxhYmVsIjtzOjEyOiJQb2ludHMgcHJpY2UiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTozO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjY6InN0YXR1cyI7czo1OiJsYWJlbCI7czo2OiJTdGF0dXMiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTo0O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEwOiJjcmVhdGVkX2F0IjtzOjU6ImxhYmVsIjtzOjEwOiJDcmVhdGVkIGF0IjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MDtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MTtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO2I6MTt9aTo1O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEwOiJ1cGRhdGVkX2F0IjtzOjU6ImxhYmVsIjtzOjEwOiJVcGRhdGVkIGF0IjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MDtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MTtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO2I6MTt9fXM6NDA6ImQ0MjJjOTUyNzE1MDFiZTEzNTJkOWNmZmI0YzczNzczX2NvbHVtbnMiO2E6Nzp7aTowO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjQ6Im5hbWUiO3M6NToibGFiZWwiO3M6NDoiTmFtZSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjE7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6NToiZW1haWwiO3M6NToibGFiZWwiO3M6MTM6IkVtYWlsIGFkZHJlc3MiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToyO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjU6InBob25lIjtzOjU6ImxhYmVsIjtzOjU6IlBob25lIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MzthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo2OiJwb2ludHMiO3M6NToibGFiZWwiO3M6NjoiUG9pbnRzIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6NDthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMDoiY3JlYXRlZF9hdCI7czo1OiJsYWJlbCI7czoxMDoiQ3JlYXRlZCBhdCI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjA7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjE7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtiOjE7fWk6NTthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMDoidXBkYXRlZF9hdCI7czo1OiJsYWJlbCI7czoxMDoiVXBkYXRlZCBhdCI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjA7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjE7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtiOjE7fWk6NjthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMDoiZGVsZXRlZF9hdCI7czo1OiJsYWJlbCI7czoxMDoiRGVsZXRlZCBhdCI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjA7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjE7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtiOjE7fX1zOjQwOiJmNjAwOTRlMzZlY2NjZDA3MjFkOWY4Y2ZkNzdjMjE4Ml9jb2x1bW5zIjthOjY6e2k6MDthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo0OiJuYW1lIjtzOjU6ImxhYmVsIjtzOjQ6Ik5hbWUiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToxO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjExOiJkZXNjcmlwdGlvbiI7czo1OiJsYWJlbCI7czoxMToiRGVzY3JpcHRpb24iO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToyO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjU6InN0b2NrIjtzOjU6ImxhYmVsIjtzOjU6IlN0b2NrIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MzthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMDoic3RvY2tfdHlwZSI7czo1OiJsYWJlbCI7czoxMDoiU3RvY2sgdHlwZSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjQ7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTA6ImNyZWF0ZWRfYXQiO3M6NToibGFiZWwiO3M6MTA6IkNyZWF0ZWQgYXQiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjowO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjoxO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7YjoxO31pOjU7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTA6InVwZGF0ZWRfYXQiO3M6NToibGFiZWwiO3M6MTA6IlVwZGF0ZWQgYXQiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjowO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjoxO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7YjoxO319fXM6ODoiZmlsYW1lbnQiO2E6MDp7fX0=', 1774992176);

-- --------------------------------------------------------

--
-- Table structure for table `stock_counts`
--

CREATE TABLE `stock_counts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `count` int(11) NOT NULL DEFAULT 0,
  `last_counted_at` timestamp NULL DEFAULT NULL,
  `status` enum('uncounted','matched','discrepancy','stale') NOT NULL DEFAULT 'uncounted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_counts`
--

INSERT INTO `stock_counts` (`id`, `product_id`, `count`, `last_counted_at`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 0, NULL, 'uncounted', '2026-03-23 01:47:09', '2026-03-23 01:47:09'),
(2, 2, 0, NULL, 'uncounted', '2026-03-23 04:08:12', '2026-03-23 04:08:12');

-- --------------------------------------------------------

--
-- Table structure for table `supplies`
--

CREATE TABLE `supplies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `stock` decimal(8,2) NOT NULL,
  `stock_type` enum('liters','kilograms','units') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `supplies`
--

INSERT INTO `supplies` (`id`, `name`, `description`, `stock`, `stock_type`, `created_at`, `updated_at`) VALUES
(1, 'Leche Etiqueta Negra', 'Usar leche de etiqueta negra, no usar descremada', 12.00, 'liters', '2026-03-23 01:55:04', '2026-03-23 02:00:21'),
(2, 'Fresas', 'Fresas Frescas, mercado. No comprar de supermercado.', 43.00, 'kilograms', '2026-03-23 01:56:00', '2026-03-23 02:00:21'),
(3, 'Azucar morena', 'Azucar morena. Preservar bien', 42.00, 'kilograms', '2026-03-23 01:57:08', '2026-03-23 02:00:21'),
(4, 'Limones', 'Limones frescos. Siempre usar', 4.50, 'kilograms', '2026-03-23 04:09:52', '2026-03-23 04:09:52');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'developer', 'developer@gmail.com', NULL, '$2y$12$VX4FqZb7D.IAAblE1TOwour4pAjZinyCDMSVmlcSX.JN88My8Whrq', '77iXVxkqPK4acTkGPcJva6R4WvoCMnqr3AiNMZxLjavMI2zbXHNKLRT3B74z', '2026-03-22 16:12:17', '2026-03-22 16:12:17'),
(2, 'jorge', 'jorge@gmail.com', NULL, '$2y$12$DU5L4fTSiOiHYKI7YofHPe6oILMa522Ys5PRgari4VmyuF5zlFr3.', NULL, '2026-03-22 16:13:54', '2026-03-22 16:13:54'),
(3, 'Maria', 'maria@gmail.com', NULL, '$2y$12$qcLbAUlY7LXmahgn/8Ocy.J5f57dFTO7JB0waQeH9v2eUuVZxPVyi', NULL, '2026-03-25 21:47:16', '2026-03-25 21:47:16');

-- --------------------------------------------------------

--
-- Structure for view `drivers`
--
DROP TABLE IF EXISTS `drivers`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `drivers`  AS SELECT `u`.`id` AS `id`, `u`.`name` AS `name`, `u`.`email` AS `email`, `u`.`password` AS `password` FROM ((`users` `u` join `model_has_roles` `mhr` on(`mhr`.`model_id` = `u`.`id`)) join `roles` `r` on(`r`.`id` = `mhr`.`role_id`)) WHERE `r`.`name` = 'Repartidor' ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customers_email_unique` (`email`);

--
-- Indexes for table `customer_coupons`
--
ALTER TABLE `customer_coupons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_coupons_id_customer_foreign` (`customer_id`),
  ADD KEY `user_coupons_id_coupon_foreign` (`coupon_id`);

--
-- Indexes for table `deliveries`
--
ALTER TABLE `deliveries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `deliveries_order_id_foreign` (`order_id`),
  ADD KEY `deliveries_user_id_foreign` (`user_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_customer_id_foreign` (`customer_id`),
  ADD KEY `fk_customer_coupons` (`coupon_id`),
  ADD KEY `orders_user_id_foreign` (`user_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_details_order_id_foreign` (`order_id`),
  ADD KEY `order_details_product_id_foreign` (`product_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `preparation_logs`
--
ALTER TABLE `preparation_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `preparation_logs_recipe_id_foreign` (`recipe_id`),
  ADD KEY `preparation_logs_user_id_foreign` (`user_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_category_id_foreign` (`category_id`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchases_user_id_foreign` (`user_id`);

--
-- Indexes for table `purchase_supplies`
--
ALTER TABLE `purchase_supplies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_supplies_supplies_id_foreign` (`supplies_id`),
  ADD KEY `purchase_supplies_id_foreign_key_purchase` (`purchase_id`);

--
-- Indexes for table `recipes`
--
ALTER TABLE `recipes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `recipes_id_products` (`product_id`);

--
-- Indexes for table `recipe_supplies`
--
ALTER TABLE `recipe_supplies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `recipe_id_foreign` (`recipe_id`),
  ADD KEY `supplies_id_foreign` (`supply_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `stock_counts`
--
ALTER TABLE `stock_counts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `stock_counts_product_id_unique` (`product_id`);

--
-- Indexes for table `supplies`
--
ALTER TABLE `supplies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `customer_coupons`
--
ALTER TABLE `customer_coupons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `deliveries`
--
ALTER TABLE `deliveries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=147;

--
-- AUTO_INCREMENT for table `preparation_logs`
--
ALTER TABLE `preparation_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_supplies`
--
ALTER TABLE `purchase_supplies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `recipes`
--
ALTER TABLE `recipes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `recipe_supplies`
--
ALTER TABLE `recipe_supplies`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `stock_counts`
--
ALTER TABLE `stock_counts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `supplies`
--
ALTER TABLE `supplies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `customer_coupons`
--
ALTER TABLE `customer_coupons`
  ADD CONSTRAINT `user_coupons_id_coupon_foreign` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`),
  ADD CONSTRAINT `user_coupons_id_customer_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`);

--
-- Constraints for table `deliveries`
--
ALTER TABLE `deliveries`
  ADD CONSTRAINT `deliveries_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `deliveries_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_customer_coupons` FOREIGN KEY (`coupon_id`) REFERENCES `customer_coupons` (`id`),
  ADD CONSTRAINT `orders_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `preparation_logs`
--
ALTER TABLE `preparation_logs`
  ADD CONSTRAINT `preparation_logs_recipe_id_foreign` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `preparation_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `purchases`
--
ALTER TABLE `purchases`
  ADD CONSTRAINT `purchases_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `purchase_supplies`
--
ALTER TABLE `purchase_supplies`
  ADD CONSTRAINT `purchase_supplies_id_foreign_key_purchase` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`),
  ADD CONSTRAINT `purchase_supplies_supplies_id_foreign` FOREIGN KEY (`supplies_id`) REFERENCES `supplies` (`id`);

--
-- Constraints for table `recipes`
--
ALTER TABLE `recipes`
  ADD CONSTRAINT `recipes_id_products` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `recipe_supplies`
--
ALTER TABLE `recipe_supplies`
  ADD CONSTRAINT `recipe_id_foreign` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`),
  ADD CONSTRAINT `supplies_id_foreign` FOREIGN KEY (`supply_id`) REFERENCES `supplies` (`id`);

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_counts`
--
ALTER TABLE `stock_counts`
  ADD CONSTRAINT `stock_counts_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
