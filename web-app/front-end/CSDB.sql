-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 26, 2020 at 06:45 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `CSDB`
--

-- --------------------------------------------------------

--
-- Table structure for table `Account`
--

CREATE TABLE `Account` (
  `account_id` varchar(9) NOT NULL,
  `account_type` varchar(10) NOT NULL,
  `email` varchar(45) NOT NULL,
  `first_name` varchar(30) NOT NULL,
  `last_name` varchar(30) NOT NULL,
  `DOB` date NOT NULL,
  `pwd` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `Account`
--

INSERT INTO `Account` (`account_id`, `account_type`, `email`, `first_name`, `last_name`, `DOB`, `pwd`) VALUES
('inst16699', 'instructor', 'instructor@cs.com', 'David', 'Kim', '2020-03-01', 'Chordscore2020'),
('instC1C79', 'instructor', 'instructor2@cs.com', 'Michael', 'Franklin', '2020-03-02', 'Chordscore2020'),
('stud304B5', 'student', 'ruth@ruth.com', 'Ruth', 'Bearden', '2020-04-14', '@Ruth1998'),
('studB3387', 'student', 'student@cs.com', 'Tadiwa', 'Mangadze', '2020-03-01', 'Password2020'),
('studBA605', 'student', 'LeviSutton@gmail.com', 'Levi', 'Sutton', '2020-04-04', 'Password0987'),
('studC92A1', 'student', 'student2@cs.com', 'Tanaka', 'Mangadze', '2020-03-02', 'Chordscore2020');

-- --------------------------------------------------------

--
-- Table structure for table `Answer_Key`
--

CREATE TABLE `Answer_Key` (
  `answer_key_id` varchar(10) NOT NULL,
  `assignment_def_id` varchar(15) NOT NULL,
  `answer_key_file_path` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `Answer_Key`
--

INSERT INTO `Answer_Key` (`answer_key_id`, `assignment_def_id`, `answer_key_file_path`) VALUES
('%#3E%C8I9!', '5C1FEJ202D', 'answerkey_images/homework_answer_key.jpeg'),
('30B5F70%#9', 'JFCE0%G4H7', 'answerkey_images/homework_answer_key.jpeg'),
('36912', '1234', 'a/b/d/Answer.1'),
('78278D1J9E', '858276@E77', 'answerkey_images/homework_answer_key.jpeg'),
('89@126F40E', '45I28D2G93', 'answerkey_images/'),
('8@F#1%9D%!', '*7754!%A**', 'answerkey_images/homework_answer_key.jpeg'),
('9G01F677%H', 'CG15.*A*!C', 'answerkey_images/'),
('C44E3*26A9', '#CJB1005I3', 'answerkey_images/homework_answer_key.jpeg'),
('E.5G#9967%', '1CA739462G', 'answerkey_images/homework_answer_key.jpeg'),
('HA77J#7B2C', '5@#65.90.5', 'answerkey_images/homework_answer_key.jpeg'),
('J9HJ0FABH%', '*2@##%.04A', 'answerkey_images/');

-- --------------------------------------------------------

--
-- Table structure for table `Assignment`
--

CREATE TABLE `Assignment` (
  `assignment_id` varchar(10) NOT NULL,
  `account_id` varchar(9) NOT NULL,
  `assignment_def_id` varchar(10) NOT NULL,
  `answer_key_id` varchar(10) NOT NULL,
  `is_graded` tinyint(1) NOT NULL,
  `grade` varchar(10) NOT NULL,
  `marked_up_file_path` varchar(100) NOT NULL,
  `comment` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `Assignment`
--

INSERT INTO `Assignment` (`assignment_id`, `account_id`, `assignment_def_id`, `answer_key_id`, `is_graded`, `grade`, `marked_up_file_path`, `comment`) VALUES
('00000777', 'instC1C79', '4321', '36912', 1, 'C', 'a/b/c', 'Good job'),
('0987qwerty', 'instC1C79', '4321', '36912', 1, 'B', 'a/a/a/b/c', 'comment here.'),
('12345', 'instC1C79', 'ABCD3', '36912', 1, 'A', 'a/a/b', 'comment here'),
('1234new', 'inst16699', '4321', '36912', 0, '–', 'a/b/c', 'comment here'),
('246810', 'studC92A1', '1234', '36912', 0, 'A', 'a/b/c/asssigment', 'It was easy');

-- --------------------------------------------------------

--
-- Table structure for table `Assignment_Definition`
--

CREATE TABLE `Assignment_Definition` (
  `assignment_def_id` varchar(10) NOT NULL,
  `account_id` varchar(9) NOT NULL,
  `title` varchar(30) NOT NULL,
  `instructions` varchar(1000) NOT NULL,
  `ak_file_name` text NOT NULL,
  `datetime_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `Assignment_Definition`
--

INSERT INTO `Assignment_Definition` (`assignment_def_id`, `account_id`, `title`, `instructions`, `ak_file_name`, `datetime_created`) VALUES
('!GD@74I65H', 'instC1C79', 'qwerty', 'instructions', 'Homework_sheet.jpeg', '2020-04-20 10:02:59'),
('#CJB1005I3', 'inst16699', 'Assignment 2', '1. Explain a, b, c...\r\n2. What is...?\r\n3. List a, ...', 'homework_answer_key.jpeg', '2020-04-20 12:44:34'),
('%7B34ED*B2', 'instC1C79', 'Testing 123', 'instructions', 'file_name.png', '2020-04-20 08:08:21'),
('*2@##%.04A', 'inst16699', 'Testing', '', '', '2020-04-20 11:07:08'),
('*7754!%A**', 'inst16699', 'Assignment 2', 'abc\r\n', 'homework_answer_key.jpeg', '2020-04-20 14:05:56'),
('..481*0.#J', 'instC1C79', 'qwerty', 'instructions', 'Homework_sheet.jpeg', '2020-04-20 10:04:35'),
('1234', 'inst16699', 'Assignment 1', 'Do this, Do that...Assignment 1', 'a1.jpeg', '2020-04-20 07:40:52'),
('1CA739462G', 'instC1C79', 'qewqeqeq', 'dafgdhdjfg 4534654746', 'homework_answer_key.jpeg', '2020-04-20 10:34:07'),
('4321', 'inst16699', 'Assignment 2', 'abc', 'homework_answer_key.jpeg', '2020-04-20 07:40:52'),
('45I28D2G93', 'inst16699', 'Testing', '', '', '2020-04-20 11:27:49'),
('5@#65.90.5', 'inst16699', 'Assignment 2', 'abc', 'homework_answer_key.jpeg', '2020-04-20 14:09:30'),
('5C1FEJ202D', 'inst16699', 'New Assignment 3', 'gscxhjavxkjaxkjabxkjabxaj xgaxjhax', 'homework_answer_key.jpeg', '2020-04-20 12:17:18'),
('858276@E77', 'inst16699', 'New Assignment 9', 'hsjdskbaskcbnaskjc cjndzckznlkcz', 'homework_answer_key.jpeg', '2020-04-20 12:37:06'),
('8HD14!1!IG', 'instC1C79', 'Testing 123', 'instructions', 'homework_answer_key.jpeg', '2020-04-20 08:13:34'),
('924IF2GHBI', 'instC1C79', 'New Assignment 1', 'instructions', 'Homework_sheet.jpeg', '2020-04-20 09:07:04'),
('ABCD3', 'inst16699', 'Test1', 'Do this, Do that', 't1.pdf', '2020-04-20 07:40:52'),
('CG15.*A*!C', 'inst16699', 'Testing', '', '', '2020-04-20 11:07:15'),
('JFCE0%G4H7', 'inst16699', 'New Assignment 5', 'hcxjhascxkjascbjcbsackasnlkc. ijchsakocaskcpsjkcjasc', 'homework_answer_key.jpeg', '2020-04-20 12:32:23');

-- --------------------------------------------------------

--
-- Table structure for table `Class`
--

CREATE TABLE `Class` (
  `class_id` varchar(9) NOT NULL,
  `account_id` varchar(9) NOT NULL,
  `class_key` varchar(15) NOT NULL,
  `class_name` varchar(30) NOT NULL,
  `semester` varchar(6) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `Class`
--

INSERT INTO `Class` (`class_id`, `account_id`, `class_key`, `class_name`, `semester`, `start_date`, `end_date`) VALUES
('123456789', 'inst16699', '123456789101112', 'Music Theory Section 1', 'Spring', '2020-01-01', '2020-05-06'),
('987654321', 'instC1C79', '121110987654321', 'Music Theory Section 2', 'Fall', '2020-08-24', '2020-12-07'),
('adbcdefgh', 'inst16699', 'abcdefghijklmo', 'Music Theory Section 3', 'Summer', '2020-06-01', '2020-08-06'),
('HTt~&fSd2', 'instC1C79', 'f6.XX@LuN6j.w8J', 'Music Theory Section 4', 'Summer', '2020-04-17', '2020-07-25'),
('x#MH%rlFr', 'inst16699', 'rzzAR%DP02]zWBm', 'Music Theory Section 5', 'Summer', '2020-04-02', '2020-04-25');

-- --------------------------------------------------------

--
-- Table structure for table `Class_Assignment`
--

CREATE TABLE `Class_Assignment` (
  `No.` int(10) NOT NULL,
  `class_id` varchar(9) NOT NULL,
  `assignment_id` varchar(10) NOT NULL,
  `open_datetime` datetime(6) NOT NULL,
  `due_datetime` datetime(6) NOT NULL,
  `posted_date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `Class_Assignment`
--

INSERT INTO `Class_Assignment` (`No.`, `class_id`, `assignment_id`, `open_datetime`, `due_datetime`, `posted_date`) VALUES
(3, '123456789', '1234new', '2020-04-17 09:41:46.000000', '2020-06-18 09:41:46.000000', '2020-04-09'),
(4, '123456789', '0987qwerty', '2020-06-13 09:41:46.000000', '2020-07-17 09:41:46.000000', '2020-04-09'),
(5, 'adbcdefgh', '00000777', '2020-04-02 09:43:00.000000', '2020-04-30 09:43:00.000000', '2020-04-09'),
(6, '987654321', '246810', '2020-06-13 09:41:46.000000', '2020-07-17 09:41:46.000000', '2020-04-09');

-- --------------------------------------------------------

--
-- Table structure for table `Class_List`
--

CREATE TABLE `Class_List` (
  `No.` int(10) NOT NULL,
  `account_id` varchar(9) NOT NULL,
  `class_id` varchar(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `Class_List`
--

INSERT INTO `Class_List` (`No.`, `account_id`, `class_id`) VALUES
(9, 'stud304B5', 'adbcdefgh'),
(18, 'studB3387', 'adbcdefgh'),
(19, 'studB3387', '987654321'),
(20, 'studC92A1', '987654321'),
(21, 'stud304B5', '123456789'),
(22, 'studC92A1', 'adbcdefgh'),
(23, 'studBA605', '987654321');

-- --------------------------------------------------------

--
-- Table structure for table `Field`
--

CREATE TABLE `Field` (
  `field_id` varchar(10) NOT NULL,
  `answer_key_id` varchar(10) NOT NULL,
  `x` varchar(10) NOT NULL,
  `y` varchar(10) NOT NULL,
  `xdim` varchar(20) NOT NULL,
  `ydim` varchar(20) NOT NULL,
  `field_type` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `Instructor_Key`
--

CREATE TABLE `Instructor_Key` (
  `instructor_key` int(9) NOT NULL,
  `account_id` varchar(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `Instructor_Key`
--

INSERT INTO `Instructor_Key` (`instructor_key`, `account_id`) VALUES
(246810, 'inst16699'),
(135791, 'instC1C79');

-- --------------------------------------------------------

--
-- Table structure for table `Pwd_Reset`
--

CREATE TABLE `Pwd_Reset` (
  `account_id` varchar(9) NOT NULL,
  `pwd_reset_key` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `Submissions`
--

CREATE TABLE `Submissions` (
  `submission_id` varchar(10) NOT NULL,
  `assignment_id` varchar(10) NOT NULL,
  `submission_file_path` varchar(100) NOT NULL,
  `submission_datetime` datetime(6) NOT NULL DEFAULT current_timestamp(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Account`
--
ALTER TABLE `Account`
  ADD PRIMARY KEY (`account_id`);

--
-- Indexes for table `Answer_Key`
--
ALTER TABLE `Answer_Key`
  ADD PRIMARY KEY (`answer_key_id`),
  ADD KEY `FOREIGN` (`assignment_def_id`);

--
-- Indexes for table `Assignment`
--
ALTER TABLE `Assignment`
  ADD PRIMARY KEY (`assignment_id`),
  ADD KEY `FOREIGN` (`account_id`),
  ADD KEY `FOREIGN2` (`assignment_def_id`) USING BTREE,
  ADD KEY `FOREIGN3` (`answer_key_id`) USING BTREE;

--
-- Indexes for table `Assignment_Definition`
--
ALTER TABLE `Assignment_Definition`
  ADD PRIMARY KEY (`assignment_def_id`),
  ADD KEY `FOREIGN` (`account_id`);

--
-- Indexes for table `Class`
--
ALTER TABLE `Class`
  ADD PRIMARY KEY (`class_id`),
  ADD KEY `FOREIGN KEY` (`account_id`);

--
-- Indexes for table `Class_Assignment`
--
ALTER TABLE `Class_Assignment`
  ADD PRIMARY KEY (`No.`),
  ADD KEY `FOREIGN` (`class_id`),
  ADD KEY `FOREIGN2` (`assignment_id`) USING BTREE;

--
-- Indexes for table `Class_List`
--
ALTER TABLE `Class_List`
  ADD PRIMARY KEY (`No.`),
  ADD KEY `FOREIGN` (`account_id`),
  ADD KEY `FOREIGN2` (`class_id`) USING BTREE;

--
-- Indexes for table `Field`
--
ALTER TABLE `Field`
  ADD PRIMARY KEY (`field_id`),
  ADD KEY `FOREIGN` (`answer_key_id`);

--
-- Indexes for table `Instructor_Key`
--
ALTER TABLE `Instructor_Key`
  ADD PRIMARY KEY (`instructor_key`),
  ADD KEY `FOREIGN` (`account_id`) USING BTREE;

--
-- Indexes for table `Pwd_Reset`
--
ALTER TABLE `Pwd_Reset`
  ADD KEY `account_id` (`account_id`);

--
-- Indexes for table `Submissions`
--
ALTER TABLE `Submissions`
  ADD PRIMARY KEY (`submission_id`),
  ADD KEY `FOREIGN` (`assignment_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Class_Assignment`
--
ALTER TABLE `Class_Assignment`
  MODIFY `No.` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `Class_List`
--
ALTER TABLE `Class_List`
  MODIFY `No.` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Answer_Key`
--
ALTER TABLE `Answer_Key`
  ADD CONSTRAINT `Answer_Key_ibfk_1` FOREIGN KEY (`assignment_def_id`) REFERENCES `Assignment_Definition` (`assignment_def_id`) ON UPDATE CASCADE;

--
-- Constraints for table `Assignment`
--
ALTER TABLE `Assignment`
  ADD CONSTRAINT `Assignment_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `Account` (`account_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `Assignment_ibfk_2` FOREIGN KEY (`answer_key_id`) REFERENCES `Answer_Key` (`answer_key_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `Assignment_ibfk_3` FOREIGN KEY (`assignment_def_id`) REFERENCES `Assignment_Definition` (`assignment_def_id`) ON UPDATE CASCADE;

--
-- Constraints for table `Assignment_Definition`
--
ALTER TABLE `Assignment_Definition`
  ADD CONSTRAINT `Assignment_Definition_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `Account` (`account_id`) ON UPDATE CASCADE;

--
-- Constraints for table `Class`
--
ALTER TABLE `Class`
  ADD CONSTRAINT `Class_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `Account` (`account_id`) ON DELETE CASCADE;

--
-- Constraints for table `Class_Assignment`
--
ALTER TABLE `Class_Assignment`
  ADD CONSTRAINT `Class_Assignment_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `Class` (`class_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `Class_Assignment_ibfk_3` FOREIGN KEY (`assignment_id`) REFERENCES `Assignment` (`assignment_id`) ON UPDATE CASCADE;

--
-- Constraints for table `Class_List`
--
ALTER TABLE `Class_List`
  ADD CONSTRAINT `Class_List_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `Account` (`account_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `Class_List_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `Class` (`class_id`);

--
-- Constraints for table `Field`
--
ALTER TABLE `Field`
  ADD CONSTRAINT `Field_ibfk_1` FOREIGN KEY (`answer_key_id`) REFERENCES `Answer_Key` (`answer_key_id`) ON UPDATE CASCADE;

--
-- Constraints for table `Instructor_Key`
--
ALTER TABLE `Instructor_Key`
  ADD CONSTRAINT `Instructor_Key_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `Account` (`account_id`) ON UPDATE CASCADE;

--
-- Constraints for table `Pwd_Reset`
--
ALTER TABLE `Pwd_Reset`
  ADD CONSTRAINT `Pwd_Reset_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `Account` (`account_id`) ON UPDATE CASCADE;

--
-- Constraints for table `Submissions`
--
ALTER TABLE `Submissions`
  ADD CONSTRAINT `Submissions_ibfk_1` FOREIGN KEY (`assignment_id`) REFERENCES `Assignment` (`assignment_id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
