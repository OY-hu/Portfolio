-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 31, 2025 at 03:53 AM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecoquest`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

DROP TABLE IF EXISTS `account`;
CREATE TABLE IF NOT EXISTS `account` (
  `accountID` int NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `role` varchar(20) NOT NULL,
  PRIMARY KEY (`accountID`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`accountID`, `username`, `password`, `email`, `phone`, `picture`, `role`) VALUES
(1, 'admin', 'admin', 'admin1@example.com', '+60 12-345 6789', 'user.png', 'Admin'),
(2, 'admin2', 'admin2', 'admin2@example.com', '+60 13-987 6543', 'user.png', 'Admin'),
(3, 'vendor', 'vendor', 'vendor@example.com', '+60 16-234 5678', 'user.png', 'Vendor'),
(4, 'student', 'student', 'student@example.com', '+60 12-111 2223', 'user.png', 'Student'),
(5, 'ecobuild', 'ecobuild', 'ecobuild@example.com', '+60 17-876 5432', 'user.png', 'Vendor'),
(6, 'solarbright', 'solarbright', 'solarbright@example.com', '+60 18-456 7890', 'user.png', 'Vendor'),
(7, 'ravi', 'ravi', 'ravi@example.com', '+60 13-444 5556', 'user.png', 'Student'),
(8, 'aisyah', 'aisyah', 'aisyah@example.com', '+60 14-777 8889', 'user.png', 'Student'),
(9, 'apuaccommodation', 'apuaccommodation', 'apuaccommodation@example.com', '+60 19-765 4321', 'user.png', 'Vendor'),
(10, 'urbanforest', 'urbanforest', 'urbanforest@example.com', '+60 11-223 3445', 'user.png', 'Vendor'),
(11, 'jason', 'jason', 'jason@example.com', '+60 15-999 0001', 'user.png', 'Student'),
(12, 'meiling', 'meiling', 'meiling@example.com', '+60 16-321 4321', 'user.png', 'Student'),
(13, 'ahmad', 'ahmad', 'faiz@example.com', '+60 17-654 7654', 'user.png', 'Student'),
(14, 'samantha', 'samantha', 'samantha@example.com', '+60 18-987 8987', 'user.png', 'Student'),
(15, 'kelvin', 'kelvin', 'kelvin@example.com', '+60 19-246 1357', 'user.png', 'Student'),
(16, 'farah', 'farah', 'farah@example.com', '+60 11-369 2580', 'user.png', 'Student'),
(17, 'daniel', 'daniel', 'daniel@example.com', '+60 12-852 7410', 'user.png', 'Student');

-- --------------------------------------------------------

--
-- Table structure for table `content`
--

DROP TABLE IF EXISTS `content`;
CREATE TABLE IF NOT EXISTS `content` (
  `contentID` int NOT NULL AUTO_INCREMENT,
  `accountID` int NOT NULL,
  `media` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `caption` varchar(500) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`contentID`),
  KEY `accountID` (`accountID`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `content`
--

INSERT INTO `content` (`contentID`, `accountID`, `media`, `title`, `caption`, `timestamp`) VALUES
(1, 4, '1-4.jpg', 'Made this cute robot', 'Look at this tin can robot I made. It is so cute.', '2025-12-23 02:42:22'),
(2, 3, '2-3.jpg', 'Reduce, Reuse, Recycle', '♻️ Small actions, big impact! The 3R mantra — Reduce, Reuse, Recycle — is more than just a slogan, it’s a lifestyle shift that helps us cut down waste, give items a second life, and turn trash into resources. Every time you carry a reusable bag, repurpose old jars, or sort your recyclables, you’re part of a movement that protects our planet. 🌍✨ Let’s make sustainability stylish and keep the cycle going!', '2025-12-23 02:47:08'),
(3, 3, '3-3.jpg', '', '🌍✨ Every choice we make leaves a mark on our planet — let’s make it a positive one! From carrying reusable bags to planting trees and conserving energy, small steps add up to big change. Together, we can protect the earth’s beauty for future generations. 💚♻️', '2025-12-23 02:49:17'),
(4, 6, '4-6.jpg', 'Solar is the future', '', '2025-12-23 02:55:19'),
(5, 10, '5-10.jpg', '', '', '2025-12-23 03:10:39'),
(6, 10, '6-10.jpg', 'Landscaping is an art', '', '2025-12-23 03:10:52');

-- --------------------------------------------------------

--
-- Table structure for table `history`
--

DROP TABLE IF EXISTS `history`;
CREATE TABLE IF NOT EXISTS `history` (
  `historyID` int NOT NULL AUTO_INCREMENT,
  `studentID` varchar(15) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `description` varchar(500) DEFAULT NULL,
  `points` int NOT NULL,
  `flagStatus` varchar(20) NOT NULL,
  PRIMARY KEY (`historyID`),
  KEY `studentID` (`studentID`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `history`
--

INSERT INTO `history` (`historyID`, `studentID`, `timestamp`, `description`, `points`, `flagStatus`) VALUES
(1, 'TP080001', '2025-12-29 12:45:12', 'Participated in Collect Plastic Bottles by GreenLeaf Recycling.', 150, 'notFlag'),
(2, 'TP080002', '2025-12-29 12:45:22', 'Participated in Collect Plastic Bottles by GreenLeaf Recycling.', 50, 'notFlag'),
(3, 'TP080003', '2025-12-29 12:45:31', 'Participated in Collect Plastic Bottles by GreenLeaf Recycling.', 100, 'notFlag'),
(4, 'TP080010', '2025-12-29 12:46:11', 'Participated in Collect Plastic Bottles by GreenLeaf Recycling.', 90, 'notFlag'),
(5, 'TP080001', '2025-12-29 12:46:26', 'Participated in Metal Recycler by GreenLeaf Recycling.', 100, 'notFlag'),
(6, 'TP080008', '2025-12-29 12:46:39', 'Participated in Metal Recycler by GreenLeaf Recycling.', 40, 'notFlag'),
(7, 'TP080009', '2025-12-29 12:47:03', 'Participated in Metal Recycler by GreenLeaf Recycling.', 170, 'notFlag'),
(8, 'TP080001', '2025-12-29 12:47:24', 'Participated in Community Awareness Session by GreenLeaf Recycling.', 50, 'notFlag'),
(9, 'TP080005', '2025-12-30 06:58:13', 'Participated in Electric Saver by APU Accommodation.', 300, 'notFlag'),
(10, 'TP080007', '2025-12-30 07:01:34', 'Participated in Electric Saver by APU Accommodation.', 168, 'notFlag'),
(11, 'TP080009', '2025-12-30 07:02:17', 'Participated in Electric Saver by APU Accommodation.', 137, 'notFlag'),
(12, 'TP080005', '2025-12-30 07:02:43', 'Participated in Water Saver by APU Accommodation.', 150, 'notFlag'),
(13, 'TP080007', '2025-12-30 07:02:58', 'Participated in Water Saver by APU Accommodation.', 81, 'notFlag'),
(14, 'TP080009', '2025-12-30 07:03:11', 'Participated in Water Saver by APU Accommodation.', 180, 'notFlag'),
(15, 'TP080001', '2025-12-30 07:04:11', 'Redeem reward Eco-Friendly Tote Bag', -150, 'notFlag'),
(16, 'TP080009', '2025-12-30 07:04:58', 'Redeem reward Stainless Steel Water Bottle', -300, 'notFlag'),
(17, 'TP080009', '2025-12-30 07:05:03', 'Redeem reward Eco-Friendly Tote Bag', -150, 'notFlag'),
(18, 'TP080007', '2025-12-30 07:05:47', 'Redeem reward Eco-Friendly Tote Bag', -150, 'notFlag'),
(19, 'TP080009', '2025-12-30 07:12:36', 'Points returned.', 150, 'notFlag'),
(20, 'TP080009', '2025-12-30 07:17:19', 'Participated in Paper Recycler by GreenLeaf Recycling.', 150, 'notFlag'),
(21, 'TP080009', '2025-12-30 07:18:55', 'Participated in Join Solar Awareness Campaign by SolarBright Energy.', 100, 'notFlag'),
(22, 'TP080009', '2025-12-30 07:19:15', 'Redeem reward Solar-Powered LED Garden Light', -350, 'notFlag'),
(23, 'TP080005', '2025-12-30 13:34:01', 'Participated in Paper Recycler by GreenLeaf Recycling.', 3000, 'flag'),
(24, 'TP080003', '2025-12-31 01:46:23', 'Admin - Additional Points Rewarded', 100, 'notFlag');

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

DROP TABLE IF EXISTS `item`;
CREATE TABLE IF NOT EXISTS `item` (
  `itemID` int NOT NULL AUTO_INCREMENT,
  `accountID` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `media` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` varchar(1000) DEFAULT NULL,
  `itemCondition` varchar(100) NOT NULL,
  `status` varchar(20) NOT NULL,
  PRIMARY KEY (`itemID`),
  KEY `accountID` (`accountID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`itemID`, `accountID`, `name`, `media`, `price`, `description`, `itemCondition`, `status`) VALUES
(1, 7, '1000 Pieces Jigsaw Puzzle', '1-7.jpg', 20.00, '1000 pieces jigsaw puzzle. Brand new and not opened. Looking to sell this. Prize can be negotiated. ', 'New', 'Available'),
(2, 15, 'Snake Board Game', '2-15.jpg', 10.50, 'Snake board game. Still in good condition. Price can be negotiated.', 'Used', 'Available'),
(3, 14, 'Full Harry Potter Book Series', '3-14.jpg', 200.00, 'All 7 Harry Potter books all in good condition. ', 'Used', 'Available');

-- --------------------------------------------------------

--
-- Table structure for table `quest`
--

DROP TABLE IF EXISTS `quest`;
CREATE TABLE IF NOT EXISTS `quest` (
  `questID` int NOT NULL AUTO_INCREMENT,
  `vendorID` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `banner` varchar(255) DEFAULT NULL,
  `description` varchar(1000) DEFAULT NULL,
  `unit` decimal(10,2) NOT NULL,
  `points` int NOT NULL,
  PRIMARY KEY (`questID`),
  KEY `vendorID` (`vendorID`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `quest`
--

INSERT INTO `quest` (`questID`, `vendorID`, `title`, `banner`, `description`, `unit`, `points`) VALUES
(1, 1, 'Collect Plastic Bottles', NULL, '', 1.00, 50),
(2, 1, 'Community Awareness Session', NULL, '', 1.00, 50),
(3, 1, 'Paper Recycler', NULL, '', 1.00, 30),
(4, 1, 'Metal Recycler', NULL, '', 1.00, 100),
(5, 2, 'Eco-Workshop', NULL, '', 1.00, 100),
(6, 3, 'Join Solar Awareness Campaign', NULL, '', 1.00, 100),
(7, 4, 'Electric Saver', NULL, '', 1.00, 15),
(8, 4, 'Water Saver', NULL, '', 1.00, 15);

-- --------------------------------------------------------

--
-- Table structure for table `redeem`
--

DROP TABLE IF EXISTS `redeem`;
CREATE TABLE IF NOT EXISTS `redeem` (
  `redeemID` int NOT NULL AUTO_INCREMENT,
  `studentID` varchar(15) NOT NULL,
  `rewardID` int NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(20) NOT NULL,
  PRIMARY KEY (`redeemID`),
  KEY `studentID` (`studentID`),
  KEY `rewardID` (`rewardID`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `redeem`
--

INSERT INTO `redeem` (`redeemID`, `studentID`, `rewardID`, `timestamp`, `status`) VALUES
(1, 'TP080001', 1, '2025-12-30 07:04:11', 'Completed'),
(2, 'TP080009', 2, '2025-12-30 07:04:58', 'Pending'),
(3, 'TP080009', 1, '2025-12-30 07:05:03', 'Rejected'),
(4, 'TP080007', 1, '2025-12-30 07:05:47', 'Pending'),
(5, 'TP080009', 4, '2025-12-30 07:19:15', 'Completed');

-- --------------------------------------------------------

--
-- Table structure for table `reward`
--

DROP TABLE IF EXISTS `reward`;
CREATE TABLE IF NOT EXISTS `reward` (
  `rewardID` int NOT NULL AUTO_INCREMENT,
  `vendorID` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `description` varchar(1000) DEFAULT NULL,
  `stock` int NOT NULL,
  `points` int NOT NULL,
  PRIMARY KEY (`rewardID`),
  KEY `vendorID` (`vendorID`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `reward`
--

INSERT INTO `reward` (`rewardID`, `vendorID`, `title`, `image`, `description`, `stock`, `points`) VALUES
(1, 1, 'Eco-Friendly Tote Bag', '1-1.png', 'A reusable cotton tote bag with GreenLeaf branding, perfect for groceries or daily use.', 40, 150),
(2, 1, 'Stainless Steel Water Bottle', '2-1.png', 'Durable, BPA-free stainless steel bottle that keeps drinks cold or hot for hours.', 25, 300),
(3, 1, 'Seed Planting Kit', '3-1.png', 'A starter kit with organic seeds, soil pellets, and biodegradable pots to grow your own herbs.', 15, 500),
(4, 3, 'Solar-Powered LED Garden Light', '4-3.png', 'A sleek, weatherproof LED garden light powered entirely by solar energy. Automatically charges during the day and lights up at night.', 30, 350),
(5, 3, 'Portable Solar Charger', '5-3.png', 'Compact and efficient solar charger for phones and small devices. Includes dual USB ports and a built-in flashlight.', 20, 600);

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

DROP TABLE IF EXISTS `student`;
CREATE TABLE IF NOT EXISTS `student` (
  `studentID` varchar(15) NOT NULL,
  `accountID` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `course` varchar(255) NOT NULL,
  PRIMARY KEY (`studentID`),
  UNIQUE KEY `accountID` (`accountID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`studentID`, `accountID`, `name`, `course`) VALUES
('TP080001', 4, 'Alicia Tan', 'Diploma in Information & Communication Technology'),
('TP080002', 7, 'Ravi Prakash', 'Foundation in Business'),
('TP080003', 8, 'Nurul Aisyah', 'Bachelor of Software Engineering (Hons)'),
('TP080004', 11, 'Jason Lim', 'Bachelor of Computer Science (Hons) in Cyber Security'),
('TP080005', 12, 'Mei Ling', 'Diploma in Accounting'),
('TP080006', 13, 'Ahmad Faiz', 'Bachelor of International Business Management (Hons)'),
('TP080007', 14, 'Samantha Lee', 'Master of Data Science'),
('TP080008', 15, 'Kelvin Chong', 'Bachelor of Design in Multimedia Technology (Hons)'),
('TP080009', 16, 'Farah Nabila', 'PhD in Information Technology'),
('TP080010', 17, 'Daniel Wong', 'Diploma in Mechatronic Engineering');

-- --------------------------------------------------------

--
-- Table structure for table `ticket`
--

DROP TABLE IF EXISTS `ticket`;
CREATE TABLE IF NOT EXISTS `ticket` (
  `ticketID` int NOT NULL AUTO_INCREMENT,
  `accountID` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `issue` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` varchar(20) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ticketID`),
  KEY `accountID` (`accountID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `ticket`
--

INSERT INTO `ticket` (`ticketID`, `accountID`, `title`, `issue`, `image`, `status`, `timestamp`) VALUES
(1, 4, 'Profile Issue', 'To whoever it may concern, I am having some issues with profile page. I can\'t seem to upload any profile picture. I have uploaded the picture and yet it can\'t be saved. I hope this issue can be resolved as soon as possible. \r\n\r\nMy name is Alicia Tan with TP Number TP080001. My email is student@example.com. Please solve this issue immediately.', NULL, 'pending', '2025-12-25 08:59:42'),
(2, 4, 'No Access to Quest', 'Hello,\r\nI seem to be having another problem. I can\'t seem to access the quest page. In fact, I can\'t see anything. All I see is white with some dots. I doubt that is the page I am supposed to arrive at. Please solve this immediately. Also, my complaint about the profile has yet to be resolved.', '2-4.jpg', 'pending', '2025-12-25 09:01:56'),
(3, 3, 'No Power Outlet', 'I am writing this report in regards about the lack of power outlet at the booth we are assigned to. We have to plug in our weighing scale for it to function as well as our laptops so that we may access the website to give points to the student. For the past 2 days, we can only accommodate the request from students for only 3 hours before not having any charge in any of our devices. ', NULL, 'Resolved', '2025-12-25 09:03:58');

-- --------------------------------------------------------

--
-- Table structure for table `vendor`
--

DROP TABLE IF EXISTS `vendor`;
CREATE TABLE IF NOT EXISTS `vendor` (
  `vendorID` int NOT NULL AUTO_INCREMENT,
  `accountID` int NOT NULL,
  `vendorName` varchar(255) NOT NULL,
  `managerName` varchar(255) NOT NULL,
  PRIMARY KEY (`vendorID`),
  UNIQUE KEY `accountID` (`accountID`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `vendor`
--

INSERT INTO `vendor` (`vendorID`, `accountID`, `vendorName`, `managerName`) VALUES
(1, 3, 'GreenLeaf Recycling', 'Amira Tan'),
(2, 5, 'EcoBuild Materials', 'Ravi Kumar'),
(3, 6, 'SolarBright Energy', 'Lina Wong'),
(4, 9, 'APU Accommodation', 'Daniel Lee'),
(5, 10, 'UrbanForest Landscaping', 'Siti Rahman');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `content`
--
ALTER TABLE `content`
  ADD CONSTRAINT `content_ibfk_1` FOREIGN KEY (`accountID`) REFERENCES `account` (`accountID`) ON DELETE CASCADE;

--
-- Constraints for table `history`
--
ALTER TABLE `history`
  ADD CONSTRAINT `history_ibfk_1` FOREIGN KEY (`studentID`) REFERENCES `student` (`studentID`) ON DELETE CASCADE;

--
-- Constraints for table `item`
--
ALTER TABLE `item`
  ADD CONSTRAINT `item_ibfk_1` FOREIGN KEY (`accountID`) REFERENCES `account` (`accountID`) ON DELETE CASCADE;

--
-- Constraints for table `quest`
--
ALTER TABLE `quest`
  ADD CONSTRAINT `quest_ibfk_1` FOREIGN KEY (`vendorID`) REFERENCES `vendor` (`vendorID`) ON DELETE CASCADE;

--
-- Constraints for table `redeem`
--
ALTER TABLE `redeem`
  ADD CONSTRAINT `redeem_ibfk_1` FOREIGN KEY (`studentID`) REFERENCES `student` (`studentID`) ON DELETE CASCADE,
  ADD CONSTRAINT `redeem_ibfk_2` FOREIGN KEY (`rewardID`) REFERENCES `reward` (`rewardID`) ON DELETE CASCADE;

--
-- Constraints for table `reward`
--
ALTER TABLE `reward`
  ADD CONSTRAINT `reward_ibfk_1` FOREIGN KEY (`vendorID`) REFERENCES `vendor` (`vendorID`) ON DELETE CASCADE;

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `student_ibfk_1` FOREIGN KEY (`accountID`) REFERENCES `account` (`accountID`) ON DELETE CASCADE;

--
-- Constraints for table `ticket`
--
ALTER TABLE `ticket`
  ADD CONSTRAINT `ticket_ibfk_1` FOREIGN KEY (`accountID`) REFERENCES `account` (`accountID`) ON DELETE CASCADE;

--
-- Constraints for table `vendor`
--
ALTER TABLE `vendor`
  ADD CONSTRAINT `vendor_ibfk_1` FOREIGN KEY (`accountID`) REFERENCES `account` (`accountID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
