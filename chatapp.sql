DROP schema IF EXISTs  chatapp;

CREATE schema chatapp;
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
-- Table structure for TABLE `chatapp`.`messages`
--

CREATE TABLE `chatapp`.`messages` (
  `msg_id` int(11) NOT NULL,
  `incoming_msg_id` int(255) NOT NULL,
  `outgoing_msg_id` int(255) NOT NULL,
  `msg` varchar(1000) NOT NULL,
  `read` int(11) NOT NULL DEFAULT 0 COMMENT '0 = havent read\n1 = read',
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for TABLE `chatapp`.`messages`
--

INSERT INTO `chatapp`.`messages` (`msg_id`, `incoming_msg_id`, `outgoing_msg_id`, `msg`, `read`, `date`) VALUES
(18, 500163789, 873798935, 'hi this is me trestionmg', 1, '2022-01-17 22:03:16'),
(19, 873798935, 500163789, 'heloo', 0, '2022-01-17 22:03:48'),
(20, 916171935, 500163789, 'Hi i just want to test the notification', 1, '2022-01-22 23:54:35'),
(21, 916171935, 500163789, 'Is it working?', 1, '2022-01-22 23:54:46'),
(22, 916171935, 975353273, 'Hi edward', 0, '2022-01-23 00:07:30');

-- --------------------------------------------------------

--
-- Table structure for TABLE `chatapp`.`users`
--

CREATE TABLE `chatapp`.`users` (
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
-- Indexes for TABLE `chatapp`.`messages`
--
ALTER TABLE `chatapp`.`messages`
  ADD PRIMARY KEY (`msg_id`);

--
-- Indexes for TABLE `chatapp`.`users`
--
ALTER TABLE `chatapp`.`users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for TABLE `chatapp`.`messages`
--
ALTER TABLE `chatapp`.`messages`
  MODIFY `msg_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for TABLE `chatapp`.`users`
--
ALTER TABLE `chatapp`.`users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
ALTER TABLE `chatapp`.`users` 
CHANGE COLUMN `img` `img` MEDIUMTEXT NOT NULL ;
ALTER TABLE `chatapp`.`users` 
ADD COLUMN `img_type` VARCHAR(45) NOT NULL AFTER `lock`;


CREATE TABLE `chatapp`.`face_unlock` (
  `user_id` INT NOT NULL,
  `img` LONGTEXT NOT NULL);
ALTER TABLE `chatapp`.`face_unlock` 
ADD COLUMN `img_id` VARCHAR(45) NOT NULL AFTER `img`;
ALTER TABLE `chatapp`.`face_unlock` 
ADD COLUMN `img_type` VARCHAR(45) NOT NULL AFTER `img_id`;
ALTER TABLE `chatapp`.`face_unlock` 
ADD INDEX `user_id_idx` (`user_id` ASC);
;
ALTER TABLE `chatapp`.`face_unlock` 
ADD CONSTRAINT `user_id`
  FOREIGN KEY (`user_id`)
  REFERENCES `chatapp`.`users` (`user_id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `chatapp`.`users` ADD `token` VARCHAR(255) NULL AFTER `img_type`;
ALTER TABLE `chatapp`.`users` ADD `verified` INT NOT NULL DEFAULT '0' AFTER `token`;

CREATE TABLE `chatapp`.`friendship` ( `to_` INT NOT NULL , `from` INT NOT NULL ) ENGINE = InnoDB;

CREATE TABLE `chatapp`.`notification` (
  `to_` int(11) NOT NULL,
  `from_` int(11) NOT NULL,
  `notification_type` int(11) NOT NULL,
  `message` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
