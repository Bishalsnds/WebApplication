-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 26, 2026 at 09:39 PM
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
-- Database: `student_course_hub`
--

-- --------------------------------------------------------

--
-- Table structure for table `interestedstudents`
--

CREATE TABLE `interestedstudents` (
  `InterestID` int(11) NOT NULL,
  `ProgrammeID` int(11) NOT NULL,
  `StudentName` varchar(100) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `RegisteredAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `interestedstudents`
--

INSERT INTO `interestedstudents` (`InterestID`, `ProgrammeID`, `StudentName`, `Email`, `RegisteredAt`) VALUES
(1, 1, 'John Doe', 'john.doe@example.com', '2026-03-24 12:06:47'),
(2, 4, 'Jane Smith', 'jane.smith@example.com', '2026-03-24 12:06:47'),
(3, 6, 'Alex Brown', 'alex.brown@example.com', '2026-03-24 12:06:47'),
(4, 9, 'Priya Patel', 'priya.patel@example.com', '2026-03-24 12:06:47'),
(8, 4, 'Bishal Sundas', 'Bishal@gmail.com', '2026-03-26 13:03:28');

-- --------------------------------------------------------

--
-- Table structure for table `levels`
--

CREATE TABLE `levels` (
  `LevelID` int(11) NOT NULL,
  `LevelName` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `levels`
--

INSERT INTO `levels` (`LevelID`, `LevelName`) VALUES
(1, 'Undergraduate'),
(2, 'Postgraduate');

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE `modules` (
  `ModuleID` int(11) NOT NULL,
  `ModuleName` text NOT NULL,
  `ModuleLeaderID` int(11) DEFAULT NULL,
  `Description` text DEFAULT NULL,
  `Image` text DEFAULT NULL,
  `ImageAlt` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`ModuleID`, `ModuleName`, `ModuleLeaderID`, `Description`, `Image`, `ImageAlt`) VALUES
(1, 'Introduction to Programming', 1, 'Covers the fundamentals of programming using Python and Java.', 'https://picsum.photos/id/121/600/420', 'Illustrative image for module Introduction to Programming'),
(2, 'Mathematics for Computer Science', 2, 'Teaches discrete mathematics, linear algebra, and probability theory.', 'https://picsum.photos/id/122/600/420', 'Illustrative image for module Mathematics for Computer Science'),
(3, 'Computer Systems & Architecture', 3, 'Explores CPU design, memory management, and assembly language.', 'https://picsum.photos/id/123/600/420', 'Illustrative image for module Computer Systems & Architecture'),
(4, 'Databases', 4, 'Covers SQL, relational database design, and NoSQL systems.', 'https://picsum.photos/id/124/600/420', 'Illustrative image for module Databases'),
(5, 'Software Engineering', 5, 'Focuses on agile development, design patterns, and project management.', 'https://picsum.photos/id/125/600/420', 'Illustrative image for module Software Engineering'),
(6, 'Algorithms & Data Structures', 6, 'Examines sorting, searching, graphs, and complexity analysis.', 'https://picsum.photos/id/126/600/420', 'Illustrative image for module Algorithms & Data Structures'),
(7, 'Cyber Security Fundamentals', 7, 'Provides an introduction to network security, cryptography, and vulnerabilities.', 'https://picsum.photos/id/127/600/420', 'Illustrative image for module Cyber Security Fundamentals'),
(8, 'Artificial Intelligence', 8, 'Introduces AI concepts such as neural networks, expert systems, and robotics.', 'https://picsum.photos/id/128/600/420', 'Illustrative image for module Artificial Intelligence'),
(9, 'Machine Learning', 9, 'Explores supervised and unsupervised learning, including decision trees and clustering.', 'https://picsum.photos/id/129/600/420', 'Illustrative image for module Machine Learning'),
(10, 'Ethical Hacking', 10, 'Covers penetration testing, security assessments, and cybersecurity laws.', 'https://picsum.photos/id/130/600/420', 'Illustrative image for module Ethical Hacking'),
(11, 'Computer Networks', 1, 'Teaches TCP/IP, network layers, and wireless communication.', 'https://picsum.photos/id/131/600/420', 'Illustrative image for module Computer Networks'),
(12, 'Software Testing & Quality Assurance', 2, 'Focuses on automated testing, debugging, and code reliability.', 'https://picsum.photos/id/132/600/420', 'Illustrative image for module Software Testing & Quality Assurance'),
(13, 'Embedded Systems', 3, 'Examines microcontrollers, real-time OS, and IoT applications.', 'https://picsum.photos/id/133/600/420', 'Illustrative image for module Embedded Systems'),
(14, 'Human-Computer Interaction', 4, 'Studies UI/UX design, usability testing, and accessibility.', 'https://picsum.photos/id/134/600/420', 'Illustrative image for module Human-Computer Interaction'),
(15, 'Blockchain Technologies', 5, 'Covers distributed ledgers, consensus mechanisms, and smart contracts.', 'https://picsum.photos/id/135/600/420', 'Illustrative image for module Blockchain Technologies'),
(16, 'Cloud Computing', 6, 'Introduces cloud services, virtualization, and distributed systems.', 'https://picsum.photos/id/136/600/420', 'Illustrative image for module Cloud Computing'),
(17, 'Digital Forensics', 7, 'Teaches forensic investigation techniques for cybercrime.', 'https://picsum.photos/id/137/600/420', 'Illustrative image for module Digital Forensics'),
(18, 'Final Year Project', 8, 'A major independent project where students develop a software solution.', 'https://picsum.photos/id/138/600/420', 'Illustrative image for module Final Year Project'),
(19, 'Advanced Machine Learning', 11, 'Covers deep learning, reinforcement learning, and cutting-edge AI techniques.', 'https://picsum.photos/id/139/600/420', 'Illustrative image for module Advanced Machine Learning'),
(20, 'Cyber Threat Intelligence', 12, 'Focuses on cybersecurity risk analysis, malware detection, and threat mitigation.', 'https://picsum.photos/id/140/600/420', 'Illustrative image for module Cyber Threat Intelligence'),
(21, 'Big Data Analytics', 13, 'Explores data mining, distributed computing, and AI-driven insights.', 'https://picsum.photos/id/141/600/420', 'Illustrative image for module Big Data Analytics'),
(22, 'Cloud & Edge Computing', 14, 'Examines scalable cloud platforms, serverless computing, and edge networks.', 'https://picsum.photos/id/142/600/420', 'Illustrative image for module Cloud & Edge Computing'),
(23, 'Blockchain & Cryptography', 15, 'Covers decentralized applications, consensus algorithms, and security measures.', 'https://picsum.photos/id/143/600/420', 'Illustrative image for module Blockchain & Cryptography'),
(24, 'AI Ethics & Society', 16, 'Analyzes ethical dilemmas in AI, fairness, bias, and regulatory considerations.', 'https://picsum.photos/id/144/600/420', 'Illustrative image for module AI Ethics & Society'),
(25, 'Quantum Computing', 17, 'Introduces quantum algorithms, qubits, and cryptographic applications.', 'https://picsum.photos/id/145/600/420', 'Illustrative image for module Quantum Computing'),
(26, 'Cybersecurity Law & Policy', 18, 'Explores digital privacy, GDPR, and international cyber law.', 'https://picsum.photos/id/146/600/420', 'Illustrative image for module Cybersecurity Law & Policy'),
(27, 'Neural Networks & Deep Learning', 19, 'Delves into convolutional networks, GANs, and AI advancements.', 'https://picsum.photos/id/147/600/420', 'Illustrative image for module Neural Networks & Deep Learning'),
(28, 'Human-AI Interaction', 20, 'Studies AI usability, NLP systems, and social robotics.', 'https://picsum.photos/id/148/600/420', 'Illustrative image for module Human-AI Interaction'),
(29, 'Autonomous Systems', 11, 'Focuses on self-driving technology, robotics, and intelligent agents.', 'https://picsum.photos/id/149/600/420', 'Illustrative image for module Autonomous Systems'),
(30, 'Digital Forensics & Incident Response', 12, 'Teaches forensic analysis, evidence gathering, and threat mitigation.', 'https://picsum.photos/id/150/600/420', 'Illustrative image for module Digital Forensics & Incident Response'),
(31, 'Postgraduate Dissertation', 13, 'A major research project where students explore advanced topics in computing.', 'https://picsum.photos/id/151/600/420', 'Illustrative image for module Postgraduate Dissertation');

-- --------------------------------------------------------

--
-- Table structure for table `programmemodules`
--

CREATE TABLE `programmemodules` (
  `ProgrammeModuleID` int(11) NOT NULL,
  `ProgrammeID` int(11) DEFAULT NULL,
  `ModuleID` int(11) DEFAULT NULL,
  `Year` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `programmemodules`
--

INSERT INTO `programmemodules` (`ProgrammeModuleID`, `ProgrammeID`, `ModuleID`, `Year`) VALUES
(1, 1, 1, 1),
(2, 1, 2, 1),
(3, 1, 3, 1),
(4, 1, 4, 1),
(5, 2, 1, 1),
(6, 2, 2, 1),
(7, 2, 3, 1),
(8, 2, 4, 1),
(9, 3, 1, 1),
(10, 3, 2, 1),
(11, 3, 3, 1),
(12, 3, 4, 1),
(13, 4, 1, 1),
(14, 4, 2, 1),
(15, 4, 3, 1),
(16, 4, 4, 1),
(17, 5, 1, 1),
(18, 5, 2, 1),
(19, 5, 3, 1),
(20, 5, 4, 1),
(21, 1, 5, 2),
(22, 1, 6, 2),
(23, 1, 7, 2),
(24, 1, 8, 2),
(25, 2, 5, 2),
(26, 2, 6, 2),
(27, 2, 12, 2),
(28, 2, 14, 2),
(29, 3, 5, 2),
(30, 3, 9, 2),
(31, 3, 8, 2),
(32, 3, 10, 2),
(33, 4, 7, 2),
(34, 4, 10, 2),
(35, 4, 11, 2),
(36, 4, 17, 2),
(37, 5, 5, 2),
(38, 5, 6, 2),
(39, 5, 9, 2),
(40, 5, 16, 2),
(41, 1, 11, 3),
(42, 1, 13, 3),
(43, 1, 15, 3),
(44, 1, 18, 3),
(45, 2, 13, 3),
(46, 2, 15, 3),
(47, 2, 16, 3),
(48, 2, 18, 3),
(49, 3, 13, 3),
(50, 3, 15, 3),
(51, 3, 16, 3),
(52, 3, 18, 3),
(53, 4, 15, 3),
(54, 4, 16, 3),
(55, 4, 17, 3),
(56, 4, 18, 3),
(57, 5, 9, 3),
(58, 5, 14, 3),
(59, 5, 16, 3),
(60, 5, 18, 3),
(61, 6, 19, 1),
(62, 6, 24, 1),
(63, 6, 27, 1),
(64, 6, 29, 1),
(65, 6, 31, 1),
(66, 7, 20, 1),
(67, 7, 26, 1),
(68, 7, 30, 1),
(69, 7, 23, 1),
(70, 7, 31, 1),
(71, 8, 21, 1),
(72, 8, 22, 1),
(73, 8, 27, 1),
(74, 8, 28, 1),
(75, 8, 31, 1),
(76, 9, 19, 1),
(77, 9, 24, 1),
(78, 9, 28, 1),
(79, 9, 29, 1),
(80, 9, 31, 1),
(81, 10, 23, 1),
(82, 10, 22, 1),
(83, 10, 25, 1),
(84, 10, 26, 1),
(85, 10, 31, 1);

-- --------------------------------------------------------

--
-- Table structure for table `programmes`
--

CREATE TABLE `programmes` (
  `ProgrammeID` int(11) NOT NULL,
  `ProgrammeName` text NOT NULL,
  `LevelID` int(11) DEFAULT NULL,
  `ProgrammeLeaderID` int(11) DEFAULT NULL,
  `Description` text DEFAULT NULL,
  `Image` text DEFAULT NULL,
  `IsPublished` tinyint(1) NOT NULL DEFAULT 1,
  `ImageAlt` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `programmes`
--

INSERT INTO `programmes` (`ProgrammeID`, `ProgrammeName`, `LevelID`, `ProgrammeLeaderID`, `Description`, `Image`, `IsPublished`, `ImageAlt`) VALUES
(1, 'BSc Computer Science', 1, 1, 'A broad computer science degree covering programming, AI, cybersecurity, and software engineering.', 'https://picsum.photos/id/180/1200/700', 1, 'Promotional image for BSc Computer Science'),
(2, 'BSc Software Engineering', 1, 2, 'A specialized degree focusing on the development and lifecycle of software applications.', 'https://picsum.photos/id/3/1200/700', 1, 'Promotional image for BSc Software Engineering'),
(3, 'BSc Artificial Intelligence', 1, 3, 'Focuses on machine learning, deep learning, and AI applications.', 'https://picsum.photos/id/0/1200/700', 1, 'Promotional image for BSc Artificial Intelligence'),
(4, 'BSc Cyber Security', 1, 4, 'Explores network security, ethical hacking, and digital forensics.', 'https://picsum.photos/id/48/1200/700', 1, 'Promotional image for BSc Cyber Security'),
(5, 'BSc Data Science', 1, 5, 'Covers big data, machine learning, and statistical computing.', 'https://picsum.photos/id/96/1200/700', 1, 'Promotional image for BSc Data Science'),
(6, 'MSc Machine Learning', 2, 11, 'A postgraduate degree focusing on deep learning, AI ethics, and neural networks.', 'https://picsum.photos/id/20/1200/700', 1, 'Promotional image for MSc Machine Learning'),
(7, 'MSc Cyber Security', 2, 12, 'A specialized programme covering digital forensics, cyber threat intelligence, and security policy.', 'https://picsum.photos/id/1011/1200/700', 1, 'Promotional image for MSc Cyber Security'),
(8, 'MSc Data Science', 2, 13, 'Focuses on big data analytics, cloud computing, and AI-driven insights.', 'https://picsum.photos/id/1003/1200/700', 1, 'Promotional image for MSc Data Science'),
(9, 'MSc Artificial Intelligence', 2, 14, 'Explores autonomous systems, AI ethics, and deep learning technologies.', 'https://picsum.photos/id/1015/1200/700', 1, 'Promotional image for MSc Artificial Intelligence'),
(10, 'MSc Software Engineering', 2, 15, 'Emphasizes software design, blockchain applications, and cutting-edge methodologies.', 'https://picsum.photos/id/1044/1200/700', 1, 'Promotional image for MSc Software Engineering');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `StaffID` int(11) NOT NULL,
  `Name` text NOT NULL,
  `JobTitle` varchar(150) DEFAULT NULL,
  `Department` varchar(150) DEFAULT NULL,
  `Bio` text DEFAULT NULL,
  `PhotoUrl` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`StaffID`, `Name`, `JobTitle`, `Department`, `Bio`, `PhotoUrl`) VALUES
(1, 'Dr. Alice Johnson', 'Lecturer', 'School of Computing', 'Dr. Alice Johnson teaches on undergraduate and postgraduate programmes in computing and supports student success through applied, industry-focused learning.', 'https://randomuser.me/api/portraits/women/44.jpg'),
(2, 'Dr. Brian Lee', 'Lecturer', 'School of Computing', 'Dr. Brian Lee teaches on undergraduate and postgraduate programmes in computing and supports student success through applied, industry-focused learning.', 'https://randomuser.me/api/portraits/men/32.jpg'),
(3, 'Dr. Carol White', 'Lecturer', 'School of Computing', 'Dr. Carol White teaches on undergraduate and postgraduate programmes in computing and supports student success through applied, industry-focused learning.', 'https://randomuser.me/api/portraits/women/68.jpg'),
(4, 'Dr. David Green', 'Lecturer', 'School of Computing', 'Dr. David Green teaches on undergraduate and postgraduate programmes in computing and supports student success through applied, industry-focused learning.', 'https://randomuser.me/api/portraits/men/75.jpg'),
(5, 'Dr. Emma Scott', 'Lecturer', 'School of Computing', 'Dr. Emma Scott teaches on undergraduate and postgraduate programmes in computing and supports student success through applied, industry-focused learning.', 'https://randomuser.me/api/portraits/women/12.jpg'),
(6, 'Dr. Frank Moore', 'Lecturer', 'School of Computing', 'Dr. Frank Moore teaches on undergraduate and postgraduate programmes in computing and supports student success through applied, industry-focused learning.', 'https://randomuser.me/api/portraits/men/41.jpg'),
(7, 'Dr. Grace Adams', 'Lecturer', 'School of Computing', 'Dr. Grace Adams teaches on undergraduate and postgraduate programmes in computing and supports student success through applied, industry-focused learning.', 'https://randomuser.me/api/portraits/women/21.jpg'),
(8, 'Dr. Henry Clark', 'Lecturer', 'School of Computing', 'Dr. Henry Clark teaches on undergraduate and postgraduate programmes in computing and supports student success through applied, industry-focused learning.', 'https://randomuser.me/api/portraits/men/57.jpg'),
(9, 'Dr. Irene Hall', 'Lecturer', 'School of Computing', 'Dr. Irene Hall teaches on undergraduate and postgraduate programmes in computing and supports student success through applied, industry-focused learning.', 'https://randomuser.me/api/portraits/women/33.jpg'),
(10, 'Dr. James Wright', 'Lecturer', 'School of Computing', 'Dr. James Wright teaches on undergraduate and postgraduate programmes in computing and supports student success through applied, industry-focused learning.', 'https://randomuser.me/api/portraits/men/18.jpg'),
(11, 'Dr. Sophia Miller', 'Lecturer', 'School of Computing', 'Dr. Sophia Miller teaches on undergraduate and postgraduate programmes in computing and supports student success through applied, industry-focused learning.', 'https://randomuser.me/api/portraits/women/52.jpg'),
(12, 'Dr. Benjamin Carter', 'Lecturer', 'School of Computing', 'Dr. Benjamin Carter teaches on undergraduate and postgraduate programmes in computing and supports student success through applied, industry-focused learning.', 'https://randomuser.me/api/portraits/men/66.jpg'),
(13, 'Dr. Chloe Thompson', 'Lecturer', 'School of Computing', 'Dr. Chloe Thompson teaches on undergraduate and postgraduate programmes in computing and supports student success through applied, industry-focused learning.', 'https://randomuser.me/api/portraits/women/79.jpg'),
(14, 'Dr. Daniel Robinson', 'Lecturer', 'School of Computing', 'Dr. Daniel Robinson teaches on undergraduate and postgraduate programmes in computing and supports student success through applied, industry-focused learning.', 'https://randomuser.me/api/portraits/men/87.jpg'),
(15, 'Dr. Emily Davis', 'Lecturer', 'School of Computing', 'Dr. Emily Davis teaches on undergraduate and postgraduate programmes in computing and supports student success through applied, industry-focused learning.', 'https://randomuser.me/api/portraits/women/7.jpg'),
(16, 'Dr. Nathan Hughes', 'Lecturer', 'School of Computing', 'Dr. Nathan Hughes teaches on undergraduate and postgraduate programmes in computing and supports student success through applied, industry-focused learning.', 'https://randomuser.me/api/portraits/men/9.jpg'),
(17, 'Dr. Olivia Martin', 'Lecturer', 'School of Computing', 'Dr. Olivia Martin teaches on undergraduate and postgraduate programmes in computing and supports student success through applied, industry-focused learning.', 'https://randomuser.me/api/portraits/women/90.jpg'),
(18, 'Dr. Samuel Anderson', 'Lecturer', 'School of Computing', 'Dr. Samuel Anderson teaches on undergraduate and postgraduate programmes in computing and supports student success through applied, industry-focused learning.', 'https://randomuser.me/api/portraits/men/53.jpg'),
(19, 'Dr. Victoria Hall', 'Lecturer', 'School of Computing', 'Dr. Victoria Hall teaches on undergraduate and postgraduate programmes in computing and supports student success through applied, industry-focused learning.', 'https://randomuser.me/api/portraits/women/16.jpg'),
(20, 'Dr. William Scott', 'Lecturer', 'School of Computing', 'Dr. William Scott teaches on undergraduate and postgraduate programmes in computing and supports student success through applied, industry-focused learning.', 'https://randomuser.me/api/portraits/men/27.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','staff','student') DEFAULT 'student',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password_hash`, `role`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'admin@example.com', '$2y$10$NxartPUHPfPpf3/H.wzJUOPYFlnf/lfi.1JhjFwIPPaOTt.Rc75UO', 'admin', '2026-03-24 12:23:42', '2026-03-24 12:39:57');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `interestedstudents`
--
ALTER TABLE `interestedstudents`
  ADD PRIMARY KEY (`InterestID`),
  ADD KEY `idx_interest_programme_email` (`ProgrammeID`,`Email`),
  ADD KEY `idx_interest_email_registered` (`Email`,`RegisteredAt`),
  ADD KEY `idx_interest_programme_registered` (`ProgrammeID`,`RegisteredAt`);

--
-- Indexes for table `levels`
--
ALTER TABLE `levels`
  ADD PRIMARY KEY (`LevelID`);

--
-- Indexes for table `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`ModuleID`),
  ADD KEY `idx_modules_leader` (`ModuleLeaderID`),
  ADD KEY `idx_modules_name` (`ModuleName`(768));
ALTER TABLE `modules` ADD FULLTEXT KEY `ft_modules_name_desc` (`ModuleName`,`Description`);

--
-- Indexes for table `programmemodules`
--
ALTER TABLE `programmemodules`
  ADD PRIMARY KEY (`ProgrammeModuleID`),
  ADD KEY `idx_pm_programme_year` (`ProgrammeID`,`Year`),
  ADD KEY `idx_pm_module` (`ModuleID`);

--
-- Indexes for table `programmes`
--
ALTER TABLE `programmes`
  ADD PRIMARY KEY (`ProgrammeID`),
  ADD KEY `idx_programmes_level_publish` (`LevelID`,`IsPublished`),
  ADD KEY `idx_programmes_leader` (`ProgrammeLeaderID`),
  ADD KEY `idx_programmes_name` (`ProgrammeName`(768));
ALTER TABLE `programmes` ADD FULLTEXT KEY `ft_programmes_name_desc` (`ProgrammeName`,`Description`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`StaffID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `interestedstudents`
--
ALTER TABLE `interestedstudents`
  MODIFY `InterestID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `programmemodules`
--
ALTER TABLE `programmemodules`
  MODIFY `ProgrammeModuleID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `programmes`
--
ALTER TABLE `programmes`
  MODIFY `ProgrammeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `interestedstudents`
--
ALTER TABLE `interestedstudents`
  ADD CONSTRAINT `interestedstudents_ibfk_1` FOREIGN KEY (`ProgrammeID`) REFERENCES `programmes` (`ProgrammeID`) ON DELETE CASCADE;

--
-- Constraints for table `modules`
--
ALTER TABLE `modules`
  ADD CONSTRAINT `modules_ibfk_1` FOREIGN KEY (`ModuleLeaderID`) REFERENCES `staff` (`StaffID`);

--
-- Constraints for table `programmemodules`
--
ALTER TABLE `programmemodules`
  ADD CONSTRAINT `programmemodules_ibfk_1` FOREIGN KEY (`ProgrammeID`) REFERENCES `programmes` (`ProgrammeID`),
  ADD CONSTRAINT `programmemodules_ibfk_2` FOREIGN KEY (`ModuleID`) REFERENCES `modules` (`ModuleID`);

--
-- Constraints for table `programmes`
--
ALTER TABLE `programmes`
  ADD CONSTRAINT `programmes_ibfk_1` FOREIGN KEY (`LevelID`) REFERENCES `levels` (`LevelID`),
  ADD CONSTRAINT `programmes_ibfk_2` FOREIGN KEY (`ProgrammeLeaderID`) REFERENCES `staff` (`StaffID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
