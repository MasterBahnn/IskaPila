-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 24, 2025 at 06:33 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `admission_records`
--

-- --------------------------------------------------------

--
-- Table structure for table `queuelogs`
--

CREATE TABLE `queuelogs` (
  `POS` int(11) NOT NULL,
  `Name` text NOT NULL,
  `Concern` text NOT NULL,
  `Notes` text NOT NULL,
  `Email` text NOT NULL,
  `Submission_date` text NOT NULL,
  `Type` text NOT NULL,
  `ID` int(11) NOT NULL,
  `REF` int(11) NOT NULL,
  `called_by` text NOT NULL,
  `REC_NO` int(11) NOT NULL,
  `datetime` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `queuelogs`
--

INSERT INTO `queuelogs` (`POS`, `Name`, `Concern`, `Notes`, `Email`, `Submission_date`, `Type`, `ID`, `REF`, `called_by`, `REC_NO`, `datetime`) VALUES
(1, 'asdasdad', 'Enrollment', 'asdasdadsad', 'ASDADASD@BJASJLDFBA', '2025/10/24', 'A', 2, 68622, 'D1vergen7', 94417, '24/10/25 04:53:00pm'),
(2, 'asdads', 'Non-issuance ID', 'asdadadadad', 'ASDADASD@BJASJLDFBA', '2025/10/24', 'B', 2, 31768, 'D1vergen7', 90177, '24/10/25 11:59:01pm');

-- --------------------------------------------------------

--
-- Table structure for table `queue_defaults`
--

CREATE TABLE `queue_defaults` (
  `IDX` int(11) NOT NULL,
  `madeby` text NOT NULL,
  `Type` char(5) NOT NULL,
  `max_queue` int(11) NOT NULL,
  `DESK1` text NOT NULL,
  `DESK2` text NOT NULL,
  `DESK3` text NOT NULL,
  `REF` int(11) NOT NULL,
  `DATETIME` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `queue_defaults`
--

INSERT INTO `queue_defaults` (`IDX`, `madeby`, `Type`, `max_queue`, `DESK1`, `DESK2`, `DESK3`, `REF`, `DATETIME`) VALUES
(1, 'DEFAULT', 'A', 50, '* 0', '* 0', '* 0', 0, ''),
(2, 'ADMIN', 'C', 25, '* 0', '* 0', '* 0', 87136, '24/10/25 10:01:52am'),
(3, 'Queue_manager', 'B', 25, 'B 2', '* 0', '* 0', 53923, '24/10/25 12:36:33pm'),
(4, 'Queue_manager', 'B', 25, '* 0', '* 0', '* 0', 76457, '24/10/25 01:30:35pm'),
(5, 'Queue_manager', 'B', 25, '* 0', 'A 2', '* 0', 14783, '24/10/25 01:39:15pm'),
(6, 'Queue_manager', 'B', 25, '* 0', 'A 2', 'D 2', 56386, '24/10/25 01:39:17pm'),
(7, 'ADMIN', 'B', 25, 'A 3', 'A 2', 'D 2', 41151, '24/10/25 03:50:07pm'),
(8, 'ADMIN', 'B', 25, '* 0', 'A 2', 'D 2', 81027, '24/10/25 04:09:52pm'),
(9, 'ADMIN', 'B', 25, '* 0', '* 0', 'D 2', 13045, '24/10/25 04:10:03pm'),
(10, 'ADMIN', 'B', 25, '* 0', '* 0', '* 0', 70216, '24/10/25 04:10:05pm'),
(11, 'ADMIN', 'B', 25, '* 0', 'C 2', '* 0', 94740, '24/10/25 04:10:06pm'),
(12, 'ADMIN', 'B', 25, '* 0', '* 0', '* 0', 18178, '24/10/25 04:10:13pm'),
(13, 'Roanne', 'C', 10, '* 0', '* 0', '* 0', 76030, '24/10/25 04:27:01pm'),
(14, 'Roanne', 'B', 10, '* 0', 'B 2', '* 0', 81913, '24/10/25 04:32:05pm'),
(15, 'Roanne', 'B', 10, '* 0', '* 0', '* 0', 97490, '24/10/25 04:32:08pm'),
(16, 'Roanne', 'B', 10, '* 0', 'B 3', '* 0', 97416, '24/10/25 04:33:48pm'),
(17, 'ADMIN', 'C', 50, '* 0', 'B 3', '* 0', 61554, '24/10/25 04:45:40pm'),
(18, 'D1vergen7', 'B', 50, '* 0', '* 0', '* 0', 70250, '24/10/25 04:52:53pm'),
(19, 'D1vergen7', 'B', 50, 'A 2', '* 0', '* 0', 92590, '24/10/25 04:53:00pm'),
(20, 'D1vergen7', 'D', 50, 'A 2', '* 0', '* 0', 28070, '24/10/25 05:31:16pm'),
(21, 'D1vergen7', 'B', 50, 'A 2', 'B 2', '* 0', 61963, '24/10/25 11:59:01pm'),
(22, 'D1vergen7', 'D', 50, 'A 2', 'B 2', '* 0', 81731, '24/10/25 11:59:19pm');

-- --------------------------------------------------------

--
-- Table structure for table `queue_orders`
--

CREATE TABLE `queue_orders` (
  `POS` int(10) NOT NULL,
  `Name` text NOT NULL,
  `Concern` text NOT NULL,
  `Notes` text NOT NULL,
  `Email` text NOT NULL,
  `Submission_date` text NOT NULL,
  `Type` text NOT NULL,
  `ID` int(11) NOT NULL,
  `REF` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `queue_orders`
--

INSERT INTO `queue_orders` (`POS`, `Name`, `Concern`, `Notes`, `Email`, `Submission_date`, `Type`, `ID`, `REF`) VALUES
(1, 'Candy', 'Enrollment', 'asdasdadadadadadada', 'ASDADASD@BJASJLDFBA', '25/10/25 12:00:08am', 'A', 2, 92255);

-- --------------------------------------------------------

--
-- Table structure for table `staff_handler`
--

CREATE TABLE `staff_handler` (
  `User_ID` int(11) NOT NULL,
  `Username` text NOT NULL,
  `Email` text NOT NULL,
  `Password` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff_handler`
--

INSERT INTO `staff_handler` (`User_ID`, `Username`, `Email`, `Password`) VALUES
(1001, 'ADMIN', 'administrative@gmail.com', '123456789'),
(1002, 'Queue_manager', 'queuemanager@outlook.com', 'qwerty123'),
(3072, 'marisracal', 'mariaracal@outlook.com', 'abcdefg@4'),
(4401, 'Roanne', 'roannie@test.com', 'testaccount'),
(1003, 'D1vergen7', 'pasterfile@outlook.com', 'mackintosk12345');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
