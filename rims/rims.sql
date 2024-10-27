-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 08, 2024 at 09:03 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rims`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_login`
--

CREATE TABLE `admin_login` (
  `id` int(11) NOT NULL,
  `admin_username` varchar(255) NOT NULL,
  `admin_password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin_signup`
--

CREATE TABLE `admin_signup` (
  `id` int(11) NOT NULL,
  `admin_username` varchar(255) NOT NULL,
  `admin_email` varchar(255) NOT NULL,
  `admin_password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_signup`
--

INSERT INTO `admin_signup` (`id`, `admin_username`, `admin_email`, `admin_password`) VALUES
(1, 'Faiqa', 'fqshaikh202@gmail.com', '$2y$10$9LmuE.THG8d43lLNS.4ro.bwsm0P4yoE9vtQzNGilOUZJXi1pcuNS'),
(2, 'Faiqa', 'fqshaikh202@gmail.com', '$2y$10$p/U4LDRDlgk.xZQecNSvzuAE4nsQQILFg7OSX4CCBjM4f7u7Xrf7a'),
(3, 'umer', 'umer@gmail.com', '$2y$10$JO9xN2Ar87wG.ev1CwRn3uzlunDk333OziYq6X2/BCC3BP6U4ci62');

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `appointment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `mechanic_id` int(11) NOT NULL,
  `service_id` int(11) DEFAULT NULL,
  `start_time` varchar(255) DEFAULT NULL,
  `end_time` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service`
--

CREATE TABLE `service` (
  `service_id` int(11) NOT NULL,
  `service_name` varchar(255) NOT NULL,
  `mechanic_id` int(255) NOT NULL,
  `service_duration` varchar(255) NOT NULL,
  `service_price` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service`
--

INSERT INTO `service` (`service_id`, `service_name`, `mechanic_id`, `service_duration`, `service_price`) VALUES
(1, 'AC Tuning', 2, '1', 1000),
(2, 'Door Repair', 2, '2', 1500),
(3, 'Oil Change', 2, '45', 800),
(4, 'Break Repair', 2, '2', 2000),
(5, 'Oil Change', 3, '45', 500),
(6, 'Brake Service', 3, '2', 2000),
(7, 'Tire Rotation', 3, '1', 2500),
(8, 'Engine Tune-Up', 3, '2', 3000),
(9, 'Engine Tune-Up', 4, '2', 4500),
(10, 'Fluid Checks', 4, '1', 800),
(11, 'Transmission Service', 4, '2', 2500),
(12, 'Brake Service', 4, '3', 3000),
(13, 'Battery Chec', 5, '1', 800),
(14, 'Wheel Alignment', 5, '2', 3500),
(15, 'AC Service', 5, '45', 1000),
(16, 'Air Filter Replacement', 5, '2', 2000),
(18, 'Radiator Flush', 10, '1', 2000),
(19, 'Shock and Strut Replacement', 10, '2', 3000),
(20, 'Anti-Lock system diagnosis', 10, '1', 2500),
(21, 'Suspension system service', 10, '2', 4000);

-- --------------------------------------------------------

--
-- Table structure for table `submit_form`
--

CREATE TABLE `submit_form` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `package` varchar(255) NOT NULL,
  `car_make` varchar(100) NOT NULL,
  `car_model` varchar(100) NOT NULL,
  `date` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `submit_form`
--

INSERT INTO `submit_form` (`id`, `name`, `email`, `address`, `package`, `car_make`, `car_model`, `date`) VALUES
(1, '0', 'fqshaikh202@gmail.com', 'RB 202 Gatti Punjab,Faisalabad', 'gold', 'corola', '2010', '2024-09-03 16:49:08'),
(2, '0', 'fqshaikh202@gmail.com', 'RB 202 Gatti Punjab,Faisalabad', 'gold', 'corola', '2010', '2024-09-03 16:50:04'),
(3, '0', 'fqshaikh202@gmail.com', 'RB 202 Gatti Punjab,Faisalabad', 'gold', 'corola', '2010', '2024-09-03 16:50:12'),
(4, '0', 'fqshaikh202@gmail.com', 'RB 202 Gatti Punjab,Faisalabad', 'gold', 'corola', '2010', '2024-09-03 16:50:29'),
(5, '0', 'fqshaikh202@gmail.com', 'RB 202 Gatti Punjab,Faisalabad', 'gold', 'corola', '2010', '2024-09-03 16:50:37'),
(6, '0', 'faiza@gmail.com', 'RB 202 Gatti Punjab,Faisalabad', 'gold', 'mehran', '2009', '2024-09-03 16:51:39'),
(7, 'amna', 'amna@gmail.com', 'RB 202 Gatti Punjab,Faisalabad', 'platinum', 'honda', '2013', '2024-09-03 16:56:20'),
(8, 'amna', 'amna@gmail.com', 'RB 202 Gatti Punjab,Faisalabad', 'platinum', 'honda', '2013', '2024-09-03 16:56:47'),
(9, 'faiza', 'faiza@gmail.com', 'RB 202 Gatti Punjab,Faisalabad', 'gold', 'mehran', '2009', '2024-09-03 16:58:28'),
(10, 'Faiqa Rafiq', 'fqshaikh202@gmail.com', 'RB 202 Gatti Punjab,Faisalabad', 'silver', 'corola', '2014', '2024-10-07 21:50:19'),
(11, 'Ahmad', 'ahmad@gmail.com', 'Bhaiwala, Faisalabad', 'platinum', 'land cruser', '2015', '2024-10-07 22:20:55'),
(12, 'Faiqa Rafiq', 'fqshaikh202@gmail.com', 'RB 202 Gatti Punjab,Faisalabad', 'silver', 'corola', '2014', '2024-10-07 22:53:35'),
(13, 'Haider', 'haiderali@gmail.com', 'Mansoorabad, Faisalabad', 'Hybrid Premium Package', 'land cruser', '2015', '2024-10-07 22:57:11');

-- --------------------------------------------------------

--
-- Table structure for table `timeslots`
--

CREATE TABLE `timeslots` (
  `time_slot_id` int(11) NOT NULL,
  `mechanic_id` int(255) NOT NULL,
  `day_of_week` text NOT NULL,
  `start_time` varchar(255) NOT NULL,
  `end_time` varchar(255) NOT NULL,
  `slot_status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `timeslots`
--

INSERT INTO `timeslots` (`time_slot_id`, `mechanic_id`, `day_of_week`, `start_time`, `end_time`, `slot_status`) VALUES
(1, 2, 'Monday', '09:00', '15:00', 0),
(2, 2, 'Tuesday', '12:00', '18:00', 0),
(3, 2, 'Wednesday', '10:00', '17:00', 0),
(4, 2, 'Thursday', '13:00', '19:00', 0),
(5, 3, 'Monday', '08:00', '14:00', 0),
(6, 3, 'Tuesday', '11:00', '17:00', 0),
(7, 3, 'Wednesday', '09:00', '16:00', 0),
(8, 3, 'Thursday', '13:00', '19:00', 0),
(9, 4, 'Monday', '08:00', '14:00', 0),
(10, 4, 'Tuesday', '09:00', '14:00', 0),
(11, 4, 'Wednesday', '12:00', '19:00', 0),
(12, 4, 'Thursday', '10:00', '17:00', 0),
(13, 5, 'Thursday', '09:00', '14:00', 0),
(14, 5, 'Friday', '08:00', '23:00', 0),
(15, 5, 'Saturday', '11:00', '17:00', 0),
(16, 5, 'Sunday', '14:00', '19:00', 0),
(17, 10, 'Monday', '12:00', '16:00', 0),
(18, 10, 'Tuesday', '09:00', '14:00', 0),
(19, 10, 'Wednesday', '10:00', '17:30', 0),
(20, 10, 'Thursday', '10:30', '15:30', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` text NOT NULL,
  `address` varchar(100) NOT NULL,
  `latitude` float DEFAULT NULL,
  `longitude` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `firstname`, `lastname`, `email`, `password`, `role`, `address`, `latitude`, `longitude`) VALUES
(2, 'Bilal', 'Ashraf', 'bilal@gmail.com', '$2y$10$dYshRGpRJChY7IuEIdbLcOwqORLPo/Dyzepkb9PVpEVLBUu51Jh.q', 'Mechanic', 'Mansoorabad, Faisalabad, Pakistan', 31.4347, 73.115),
(3, 'Bashir', 'Ahmad', 'bashirahmad@gmail.com', '$2y$10$I54Am1q/KkSsDFZ1u3MJr.F4MRrSJuxAj3I4Dpd/J58gIL50UMTNu', 'Mechanic', 'Bhaiwala 202 R.B, Faisalabad, Pakistan', 31.4725, 73.1246),
(4, 'Tahir', 'Ali', 'tahirali@gmail.com', '$2y$10$VwCUVuGTXVKlrOlywC11wuvo8xA4uok6QFXxZL2RBavGjfGQd2uKq', 'Mechanic', 'Jhang Road, Faisalabad, Pakistan', 31.3893, 73.001),
(5, 'Imran', 'Abbas', 'imran202@gmail.com', '$2y$10$kQucvIkHU2LWOe8/243ZQevxGyYpBAocME8pq7fU.4sIhv5joyg3u', 'Mechanic', 'Samanabad, Faisalabad, Pakistan', NULL, NULL),
(6, 'aamir', 'Rafiq', 'aamirRafiq@gmail.com', '$2y$10$GefVn7avBwE4x7kX.zA9cO79pyaRXZVMvvCSOB1Rvm8Uny7X4zi8.', 'User', 'Gatti%2C+Faisalabad%2C+Pakistan', NULL, NULL),
(8, 'Mehmood', 'Taj', 'mahmood@gmail.com', '$2y$10$muMTw/5yBUpa9b3FBiItIeLeuAnCoSX8tLaDhn7H/jfEV8uLMjIQC', 'Mechanic', 'Millat+Town+Faisalabad%2C+Pakistan', NULL, NULL),
(9, 'Faiqa', 'Rafiq', 'fqshaikh202@gmail.com', '$2y$10$eZkubkxr24aPJ3Nbbn0xoucoIuifrQItIXeyt.pS9u9M7sMiCYHfC', 'User', 'RB+202+Gatti+Punjab%2CFaisalabad', NULL, NULL),
(10, 'Aamir', 'Rafiq', 'aamir@gmail.com', '$2y$10$2tUvSa8m2iFV/PCzuT6SGOmcGZBX0zh7pnEOtUC9bfWTQyNyK4ev6', 'Mechanic', 'Madina+town', NULL, NULL),
(11, 'Zain', 'Ahmad', 'zainahmad@gmail.com', '$2y$10$XtVB/f4NYAjNAmu0Hcy8bOfrKl99NQIrDbQ0zVOWnyNwS1W2hu.qu', 'User', 'Jhang+Bazar%2C+Douglaspura%2C+Faisalabad%2C+Pakistan', NULL, NULL),
(12, 'Inayat', 'Ali', 'inayatali@gmail.com', '$2y$10$wuYvnpjedWNdD6TlBEDFkeWIONKmXa45MF/8dQtuz.FKk6QxAod2q', 'Mechanic', 'Chak+Jhumra+Road%2C+Chak+Jhumra%2C+Pakistan', NULL, NULL),
(13, 'Babar', 'Ali', 'babarali202@gmail.com', '$2y$10$feMeXtIzGg8shsWZCA3p2.3ZC91Yc49LI9wwrwykhTKJ.R6kD9N1m', 'User', 'Peoples+Colony%2C+Gujranwala%2C+Pakistan', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `workshops`
--

CREATE TABLE `workshops` (
  `workshop_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `longitude` float NOT NULL,
  `latitude` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `workshops`
--

INSERT INTO `workshops` (`workshop_id`, `name`, `longitude`, `latitude`) VALUES
(1, 'Saif Auto Workshop', 73.1344, 31.4748),
(2, 'Al-Madina Workshop', 73.135, 31.4504),
(3, 'Khan Auto Repair', 73.0994, 31.4944),
(4, 'Expert Auto Care', 73.1258, 31.4832),
(5, 'Quick Fix Garage', 73.123, 31.4561),
(6, 'Reliable Mechanics', 73.1402, 31.4706),
(7, 'City Auto Service', 73.1294, 31.4615),
(8, 'Auto Master Workshop', 73.1104, 31.4758),
(9, 'Professional Auto Repairs', 73.1224, 31.4403),
(10, 'Auto Tech Solutions', 73.1372, 31.4486),
(11, 'Affordable Auto Care', 73.1168, 31.4707),
(12, 'Auto Excellence', 73.1456, 31.4847),
(13, 'Super Auto Workshop', 73.1078, 31.4607),
(14, 'Prime Auto Service', 73.1254, 31.4702),
(15, 'Trusted Auto Care', 73.1197, 31.4448),
(16, 'Mechanics Hub', 73.1394, 31.4527);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_login`
--
ALTER TABLE `admin_login`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_signup`
--
ALTER TABLE `admin_signup`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`appointment_id`),
  ADD KEY `mechanic_id` (`mechanic_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`service_id`);

--
-- Indexes for table `submit_form`
--
ALTER TABLE `submit_form`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `timeslots`
--
ALTER TABLE `timeslots`
  ADD PRIMARY KEY (`time_slot_id`),
  ADD KEY `mechanic_id` (`mechanic_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `workshops`
--
ALTER TABLE `workshops`
  ADD PRIMARY KEY (`workshop_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_login`
--
ALTER TABLE `admin_login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admin_signup`
--
ALTER TABLE `admin_signup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `appointment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service`
--
ALTER TABLE `service`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `submit_form`
--
ALTER TABLE `submit_form`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `timeslots`
--
ALTER TABLE `timeslots`
  MODIFY `time_slot_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `workshops`
--
ALTER TABLE `workshops`
  MODIFY `workshop_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`mechanic_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `appointments_ibfk_3` FOREIGN KEY (`service_id`) REFERENCES `service` (`service_id`);

--
-- Constraints for table `timeslots`
--
ALTER TABLE `timeslots`
  ADD CONSTRAINT `timeslots_ibfk_1` FOREIGN KEY (`mechanic_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
