-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 30, 2022 at 07:44 AM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 7.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `chatapp`
--

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `msg_id` int(11) NOT NULL,
  `incoming_msg_id` int(255) NOT NULL,
  `outgoing_msg_id` int(255) NOT NULL,
  `msg` varchar(1000) NOT NULL,
  `read` int(11) NOT NULL DEFAULT 0 COMMENT '0 = havent read\n1 = read',
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`msg_id`, `incoming_msg_id`, `outgoing_msg_id`, `msg`, `read`, `date`) VALUES
(18, 500163789, 873798935, 'hi this is me trestionmg', 1, '2022-01-17 22:03:16'),
(19, 873798935, 500163789, 'heloo', 0, '2022-01-17 22:03:48'),
(20, 916171935, 500163789, 'Hi i just want to test the notification', 1, '2022-01-22 23:54:35'),
(21, 916171935, 500163789, 'Is it working?', 1, '2022-01-22 23:54:46'),
(22, 916171935, 975353273, 'Hi edward', 0, '2022-01-23 00:07:30');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `unique_id` int(255) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `img` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `lock` longtext NOT NULL DEFAULT '' COMMENT '{value}|{value}'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `unique_id`, `fname`, `lname`, `email`, `password`, `img`, `status`, `lock`) VALUES
(1, 500163789, 'ELWIN', 'VON', 'wif180013@siswa.um.edu.my', '0192023a7bbd73250516f069df18b500', '1640752681Happy_icon.png', 'Active now', '|1447032531'),
(2, 1447032531, 'ELVINA', 'VON', 'elwinvon.ev@gmail.com', '0192023a7bbd73250516f069df18b500', '1640752730realize.png', 'Offline now', ''),
(3, 916171935, 'EDWARD', 'VON', 'elwinvon@rocketmail.com', '0192023a7bbd73250516f069df18b500', '1640752765cute excited boy.png', 'Offline now', ''),
(4, 975353273, 'Edwin', 'Von', 'elwinvon.ev0407@gmail.com', '0192023a7bbd73250516f069df18b500', '1641114210customer support.jpg', 'Offline now', ''),
(8, 242572868, 'alex', 'trt', 'elwinvonrtr@rocketmail.com', '0192023a7bbd73250516f069df18b500', '1642243045Happy_icon.png', 'Offline now', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`msg_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `msg_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
