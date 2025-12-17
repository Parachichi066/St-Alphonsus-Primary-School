-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 16, 2025 at 06:30 PM
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
-- Database: `st_alphonsus`
--

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `class_id` int(2) NOT NULL,
  `class_name` varchar(30) NOT NULL,
  `class_capacity` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`class_id`, `class_name`, `class_capacity`) VALUES
(1, 'Reception Year', 30),
(2, 'Year One', 32),
(3, 'Year Two', 30),
(4, 'Year Three', 32),
(5, 'Year Four', 30),
(6, 'Year Five', 32),
(7, 'Year Six', 30);

-- --------------------------------------------------------

--
-- Table structure for table `parents`
--

CREATE TABLE `parents` (
  `parent_id` int(30) NOT NULL,
  `parent_name` varchar(30) NOT NULL,
  `parent_email` varchar(30) NOT NULL,
  `parent_telephone` varchar(13) NOT NULL,
  `parent_address` varchar(30) NOT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `parents`
--

INSERT INTO `parents` (`parent_id`, `parent_name`, `parent_email`, `parent_telephone`, `parent_address`, `user_id`) VALUES
(1, 'Emma Smith', 'emma.smith@email.com', '07123456789', '12 Maple St', 9),
(2, 'James Johnson', 'j.johnson@email.com', '07234567890', '45 Oak Ave', 10),
(3, 'Olivia Williams', 'olivia.w@email.com', '07345678901', '88 Pine Rd', 11),
(4, 'Ben Brown', 'ben.brown@email.com', '07456789012', '3 Elm Blvd', 12),
(5, 'Sophie Jones', 'sophie.jones@email.com', '07567890123', '77 Cedar Ln', 13),
(6, 'Daniel Garcia', 'd.garcia@email.com', '07678901234', '92 Birch Way', 14),
(7, 'Lucy Davis', 'lucy.davis@email.com', '07789012345', '11 Spruce Ct', 15),
(8, 'Ryan Miller', 'ryan.miller@email.com', '07890123456', '64 Aspen Dr', 16),
(9, 'Hannah Wilson', 'h.wilson@email.com', '07901234567', '33 Redwood Ter', 17),
(10, 'Callum Moore', 'callum.moore@email.com', '07112345678', '21 Willow St', 18),
(11, 'Ella Taylor', 'ella.taylor@email.com', '07223456789', '56 Magnolia Ave', 19),
(12, 'Thomas Anderson', 't.anderson@email.com', '07334567890', '98 Sycamore Rd', 20),
(13, 'Chloe Thomas', 'chloe.thomas@email.com', '07445678901', '14 Hickory Ln', 21),
(14, 'Jack Jackson', 'jack.jackson@email.com', '07556789012', '67 Ash St', 22),
(15, 'Grace White', 'grace.white@email.com', '07667890123', '42 Poplar Ct', 23),
(16, 'Sam Harris', 'sam.harris@email.com', '07778901234', '19 Chestnut Dr', 24),
(17, 'Lily Martin', 'lily.martin@email.com', '07889012345', '88 Laurel Way', 25),
(18, 'Owen Thompson', 'owen.thompson@email.com', '07990123456', '35 Juniper Ter', 26),
(19, 'Zoe Garcia', 'zoe.garcia@email.com', '07101234567', '76 Cypress Ave', 27),
(20, 'Charlie Martinez', 'c.martinez@email.com', '07212345678', '53 Dogwood Blvd', 28),
(21, 'Alice Robinson', 'alice.robinson@email.com', '07323456789', '27 Redwood St', 29),
(22, 'George Clark', 'george.clark@email.com', '07434567890', '61 Beech Ct', 30),
(23, 'Mia Rodriguez', 'mia.rodriguez@email.com', '07545678901', '89 Spruce Ave', 31),
(24, 'Leo Lewis', 'leo.lewis@email.com', '07656789012', '14 Alder Ln', 32),
(25, 'Ruby Lee', 'ruby.lee@email.com', '07767890123', '92 Willow Rd', 33),
(26, 'Jacob Walker', 'jacob.walker@email.com', '07878901234', '38 Hickory Way', 34),
(27, 'Isla Hall', 'isla.hall@email.com', '07989012345', '55 Aspen Blvd', 35),
(28, 'Noah Allen', 'noah.allen@email.com', '07190123456', '70 Pine Ct', 36),
(29, 'Freya Young', 'freya.young@email.com', '07201234567', '11 Cedar Dr', 37),
(30, 'Archie King', 'archie.king@email.com', '07312345678', '84 Maple Ave', 38);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` int(30) NOT NULL,
  `student_name` varchar(30) NOT NULL,
  `age` int(2) NOT NULL,
  `class_id` int(2) DEFAULT NULL,
  `student_address` varchar(30) NOT NULL,
  `medical_information` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `student_name`, `age`, `class_id`, `student_address`, `medical_information`) VALUES
(3, 'Noah Williams', 5, 2, '88 Pine Rd', 'Penicillin allergy'),
(4, 'Oliver Brown', 6, 2, '3 Elm Blvd', 'No known allergies'),
(5, 'Sophia Jones', 6, 3, '77 Cedar Ln', 'Wears glasses'),
(6, 'Isabella Garcia', 7, 3, '92 Birch Way', 'No known allergies'),
(7, 'Mia Davis', 7, 4, '11 Spruce Ct', 'Lactose intolerant'),
(8, 'Charlotte Miller', 8, 4, '64 Aspen Dr', 'No known allergies'),
(9, 'Amelia Wilson', 8, 5, '33 Redwood Ter', 'Bee-sting allergy'),
(10, 'Harper Moore', 8, 5, '21 Willow St', 'No known allergies'),
(11, 'Evelyn Taylor', 9, 6, '56 Magnolia Ave', 'Migraine history'),
(12, 'Abigail Anderson', 9, 6, '98 Sycamore Rd', 'No known allergies'),
(13, 'Emily Thomas', 10, 7, '14 Hickory Ln', 'Asthma – inhaler required'),
(14, 'Elizabeth Jackson', 10, 7, '67 Ash St', 'No known allergies'),
(15, 'Sofia White', 6, 2, '42 Poplar Ct', 'Wears braces'),
(16, 'Madison Harris', 5, 1, '19 Chestnut Dr', 'No known allergies'),
(17, 'Avery Martin', 6, 2, '88 Laurel Way', 'Peanut allergy'),
(18, 'Chloe Thompson', 7, 3, '35 Juniper Ter', 'No known allergies'),
(19, 'Ella Garcia', 8, 4, '76 Cypress Ave', 'ADHD medication'),
(20, 'Grace Martinez', 9, 5, '53 Dogwood Blvd', 'No known allergies'),
(21, 'Victoria Robinson', 10, 6, '27 Redwood St', 'Wears glasses'),
(22, 'Lily Clark', 11, 7, '61 Beech Ct', 'No known allergies'),
(23, 'Nora Rodriguez', 4, 1, '89 Spruce Ave', 'Asthma – inhaler required'),
(24, 'Hazel Lewis', 5, 1, '14 Alder Ln', 'No known allergies'),
(25, 'Zoey Lee', 6, 2, '92 Willow Rd', 'Shellfish allergy'),
(26, 'Penelope Walker', 7, 3, '38 Hickory Way', 'No known allergies'),
(27, 'Layla Hall', 8, 4, '55 Aspen Blvd', 'Lactose intolerant'),
(28, 'Riley Allen', 9, 5, '70 Pine Ct', 'No known allergies'),
(29, 'Leah Young', 10, 6, '11 Cedar Dr', 'Wears glasses'),
(30, 'Aubrey King', 11, 7, '84 Maple Ave', 'No known allergies');

--
-- Triggers `students`
--
DELIMITER $$
CREATE TRIGGER `check_class_capacity` BEFORE INSERT ON `students` FOR EACH ROW BEGIN
    DECLARE current_count INT;
    DECLARE max_cap INT;

    -- 1. Get current count of students in the target class
    SELECT COUNT(*) INTO current_count 
    FROM students 
    WHERE class_id = NEW.class_id;

    -- 2. Get the capacity of that class
    SELECT class_capacity INTO max_cap 
    FROM classes 
    WHERE class_id = NEW.class_id;

    -- 3. Check if full
    IF current_count >= max_cap THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Cannot add student: Class is fully booked.';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `student_parent`
--

CREATE TABLE `student_parent` (
  `parent_id` int(30) DEFAULT NULL,
  `student_id` int(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_parent`
--

INSERT INTO `student_parent` (`parent_id`, `student_id`) VALUES
(3, 3),
(4, 4),
(5, 5),
(6, 6),
(7, 7),
(8, 8),
(9, 9),
(10, 10),
(11, 11),
(12, 12),
(13, 13),
(14, 14),
(15, 15),
(16, 16),
(17, 17),
(18, 18),
(19, 19),
(20, 20),
(21, 21),
(22, 22),
(23, 23),
(24, 24),
(25, 25),
(26, 26),
(27, 27),
(28, 28),
(29, 29),
(30, 30),
(6, 3);

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `teacher_id` int(30) NOT NULL,
  `teacher_name` varchar(30) NOT NULL,
  `teacher_email` varchar(30) NOT NULL,
  `teacher_telephone` varchar(13) NOT NULL,
  `teacher_address` varchar(30) NOT NULL,
  `teacher_salary` float NOT NULL,
  `background_check` tinyint(1) NOT NULL,
  `class_id` int(2) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`teacher_id`, `teacher_name`, `teacher_email`, `teacher_telephone`, `teacher_address`, `teacher_salary`, `background_check`, `class_id`, `user_id`) VALUES
(1, 'Mrs Rachel Adams', 'r.adams@school.com', '07111111111', '10 Ivy Close', 28500, 1, 1, 2),
(2, 'Mr Thomas Bell', 't.bell@school.com', '07222222222', '21 Elm Way', 26950, 1, 2, 3),
(3, 'Miss Sophie Clark', 's.clark@school.com', '07333333333', '5 Oak Rd', 31000, 1, 3, 4),
(4, 'Mr David Dunn', 'd.dunn@school.com', '07444444444', '8 Beech Ave', 29500, 1, 4, 5),
(5, 'Mrs Emma Evans', 'e.evans@school.com', '07555555555', '14 Pine Ct', 33000, 1, 5, 6),
(6, 'Mr James Ford', 'j.ford@school.com', '07666666666', '2 Maple Dr', 27000, 1, 6, 7),
(7, 'Miss Laura Green', 'l.green@school.com', '07777777777', '7 Cedar Ln', 36500, 1, 7, 8);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `role`) VALUES
(1, 'admin', '$2y$10$ma9..L03N5caijmE9aIHTuO1keG3u491IPWMpauvm3Z5.nJrOtPhC', 'admin'),
(2, 'r.adams', '$2y$10$DUUseLMnzsDqyAR8kqMoeOMfyy6dIC0WhZJlU62LG95fexDpnp7w2', 'teacher'),
(3, 't.bell', 'pass123', 'teacher'),
(4, 's.clark', 'pass123', 'teacher'),
(5, 'd.dunn', 'pass123', 'teacher'),
(6, 'e.evans', 'pass123', 'teacher'),
(7, 'j.ford', 'pass123', 'teacher'),
(8, 'l.green', 'pass123', 'teacher'),
(9, 'emma.smith', 'pass123', 'parent'),
(10, 'j.johnson', 'pass123', 'parent'),
(11, 'olivia.w', 'pass123', 'parent'),
(12, 'ben.brown', 'pass123', 'parent'),
(13, 'sophie.jones', 'pass123', 'parent'),
(14, 'd.garcia', '$2y$10$0kluwV20nyDnPPbRB5chaeACqGIb9kHI5btld4oeD674uKJ9gFjju', 'parent'),
(15, 'lucy.davis', 'pass123', 'parent'),
(16, 'ryan.miller', 'pass123', 'parent'),
(17, 'h.wilson', 'pass123', 'parent'),
(18, 'callum.moore', 'pass123', 'parent'),
(19, 'ella.taylor', 'pass123', 'parent'),
(20, 't.anderson', 'pass123', 'parent'),
(21, 'chloe.thomas', 'pass123', 'parent'),
(22, 'jack.jackson', 'pass123', 'parent'),
(23, 'grace.white', 'pass123', 'parent'),
(24, 'sam.harris', 'pass123', 'parent'),
(25, 'lily.martin', 'pass123', 'parent'),
(26, 'owen.thompson', 'pass123', 'parent'),
(27, 'zoe.garcia', 'pass123', 'parent'),
(28, 'c.martinez', 'pass123', 'parent'),
(29, 'alice.robinson', 'pass123', 'parent'),
(30, 'george.clark', 'pass123', 'parent'),
(31, 'mia.rodriguez', 'pass123', 'parent'),
(32, 'leo.lewis', 'pass123', 'parent'),
(33, 'ruby.lee', 'pass123', 'parent'),
(34, 'jacob.walker', 'pass123', 'parent'),
(35, 'isla.hall', 'pass123', 'parent'),
(36, 'noah.allen', 'pass123', 'parent'),
(37, 'freya.young', 'pass123', 'parent'),
(38, 'archie.king', 'pass123', 'parent');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`class_id`);

--
-- Indexes for table `parents`
--
ALTER TABLE `parents`
  ADD PRIMARY KEY (`parent_id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`),
  ADD KEY `classes_ibfk_1` (`class_id`);

--
-- Indexes for table `student_parent`
--
ALTER TABLE `student_parent`
  ADD KEY `student_parent_ibfk_1` (`student_id`),
  ADD KEY `student_parent_ibfk_2` (`parent_id`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`teacher_id`),
  ADD UNIQUE KEY `class_id` (`class_id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `class_id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `parents`
--
ALTER TABLE `parents`
  MODIFY `parent_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `teacher_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `parents`
--
ALTER TABLE `parents`
  ADD CONSTRAINT `fk_parent_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `classes_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Constraints for table `student_parent`
--
ALTER TABLE `student_parent`
  ADD CONSTRAINT `student_parent_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `student_parent_ibfk_2` FOREIGN KEY (`parent_id`) REFERENCES `parents` (`parent_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `teachers`
--
ALTER TABLE `teachers`
  ADD CONSTRAINT `fk_teacher_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `teachers_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
