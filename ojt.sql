-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 19, 2025 at 04:09 AM
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
-- Database: `ojt_monitoring`
--

-- --------------------------------------------------------

--
-- Table structure for table `submissions`
--

CREATE TABLE `submissions` (
  `id` int(11) NOT NULL,
  `student_name` varchar(100) NOT NULL,
  `submitted_hours` int(11) NOT NULL,
  `submission_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `remaining_time` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbladmin`
--

CREATE TABLE `tbladmin` (
  `Id` int(10) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `emailAddress` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbladmin`
--

INSERT INTO `tbladmin` (`Id`, `firstName`, `lastName`, `emailAddress`, `password`) VALUES
(1, 'Admin', '', 'admin@mail.com', '123');

-- --------------------------------------------------------

--
-- Table structure for table `tblattendance`
--

CREATE TABLE `tblattendance` (
  `Id` int(10) NOT NULL,
  `admissionNo` varchar(255) NOT NULL,
  `classId` varchar(10) NOT NULL,
  `classArmId` varchar(10) NOT NULL,
  `sessionTermId` varchar(10) NOT NULL,
  `status` varchar(10) NOT NULL,
  `dateTimeTaken` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblattendance`
--

INSERT INTO `tblattendance` (`Id`, `admissionNo`, `classId`, `classArmId`, `sessionTermId`, `status`, `dateTimeTaken`) VALUES
(214, '2018-0239', '7', '9', '4', '1', '2024-09-12');

-- --------------------------------------------------------

--
-- Table structure for table `tblclass`
--

CREATE TABLE `tblclass` (
  `Id` int(10) NOT NULL,
  `className` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblclass`
--

INSERT INTO `tblclass` (`Id`, `className`) VALUES
(9, 'BSIS 4A');

-- --------------------------------------------------------

--
-- Table structure for table `tblclassarms`
--

CREATE TABLE `tblclassarms` (
  `Id` int(10) NOT NULL,
  `classId` varchar(10) NOT NULL,
  `classArmName` varchar(255) NOT NULL,
  `isAssigned` varchar(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblclassarms`
--

INSERT INTO `tblclassarms` (`Id`, `classId`, `classArmName`, `isAssigned`) VALUES
(8, '7', 'Section A', '1'),
(9, '7', 'Section B', '1');

-- --------------------------------------------------------

--
-- Table structure for table `tblclassteacher`
--

CREATE TABLE `tblclassteacher` (
  `Id` int(10) NOT NULL,
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `emailAddress` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phoneNo` varchar(50) NOT NULL,
  `classId` varchar(10) NOT NULL,
  `classArmId` varchar(50) NOT NULL,
  `dateCreated` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblclassteacher`
--

INSERT INTO `tblclassteacher` (`Id`, `firstName`, `lastName`, `emailAddress`, `password`, `phoneNo`, `classId`, `classArmId`, `dateCreated`) VALUES
(18, 'joshua', 'tiongco', 'teacher@gmail.com', '123', '123456', '7', '8', '2025-02-03');

-- --------------------------------------------------------

--
-- Table structure for table `tblcompany`
--

CREATE TABLE `tblcompany` (
  `Id` int(11) NOT NULL,
  `comp_name` varchar(255) NOT NULL,
  `contact_person` varchar(255) NOT NULL,
  `contact_num` varchar(255) NOT NULL,
  `comp_address` varchar(255) NOT NULL,
  `comp_link` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblcompany`
--

INSERT INTO `tblcompany` (`Id`, `comp_name`, `contact_person`, `contact_num`, `comp_address`, `comp_link`) VALUES
(2, 'Hiraya Software Solution', 'Katchie Lama', '09126788908', 'Sa May Doorn', NULL),
(3, 'src', 'John Rey', '094954', 'src2gmail', NULL),
(4, 'hello', 'Dcc', '1234', 'Santa Rita', 'https://src.edu.ph/');

-- --------------------------------------------------------

--
-- Table structure for table `tblenroll`
--

CREATE TABLE `tblenroll` (
  `Id` int(11) NOT NULL,
  `student_name` varchar(255) NOT NULL,
  `coor_name` varchar(255) NOT NULL,
  `company_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblsessionterm`
--

CREATE TABLE `tblsessionterm` (
  `Id` int(10) NOT NULL,
  `sessionName` varchar(50) NOT NULL,
  `termId` varchar(50) NOT NULL,
  `isActive` varchar(10) NOT NULL,
  `dateCreated` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblsessionterm`
--

INSERT INTO `tblsessionterm` (`Id`, `sessionName`, `termId`, `isActive`, `dateCreated`) VALUES
(1, '2021/2022', '1', '0', '2022-10-31'),
(3, '2021/2022', '2', '0', '2022-10-31'),
(4, '2024/2025', '3', '1', '2024-09-08');

-- --------------------------------------------------------

--
-- Table structure for table `tblstudent`
--

CREATE TABLE `tblstudent` (
  `id` int(11) NOT NULL,
  `school_id` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `contact` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblstudents`
--

CREATE TABLE `tblstudents` (
  `Id` int(10) NOT NULL,
  `admissionNumber` varchar(255) DEFAULT NULL,
  `firstName` varchar(255) DEFAULT NULL,
  `lastName` varchar(255) DEFAULT NULL,
  `classId` varchar(10) DEFAULT NULL,
  `contact` varchar(50) DEFAULT NULL,
  `comp_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `dateCreated` varchar(50) DEFAULT NULL,
  `remaining_time` int(255) NOT NULL,
  `comp_link` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblstudents`
--

INSERT INTO `tblstudents` (`Id`, `admissionNumber`, `firstName`, `lastName`, `classId`, `contact`, `comp_name`, `email`, `address`, `password`, `dateCreated`, `remaining_time`, `comp_link`) VALUES
(46, '1001', 'John Dexter ', 'zapanta', '9', '0949525', 'Hiraya Software Solution', 'student@gmail.com', 'malapit lang', '123', '2025-02-18', 300, NULL),
(47, '1002', 'jordan', 'zapanta', '9', '09594', 'Hiraya Software Solution', 'student1@gmail.com', 'samalayo', '123', '2025-02-19', 460, NULL),
(48, '1003', 'jordan', 'zapanta', '9', '8', 'Hiraya Software Solution', 'student2@gmail.com', '8', '123', '2025-02-19', 460, NULL),
(49, '1004', 'Zapanta', 'Zapanta', '9', '09594', 'Hiraya Software Solution', 'student3@gmail.com', 'samalayo', '123', '2025-02-19', 412, NULL),
(50, '1005', 'darvin', 'galang', '9', '09594', 'Hiraya Software Solution', 'student4@gmail.com', 'samalayo', '123', '2025-02-19', 460, NULL),
(51, '1006', 'jordan', 'Tiongco', '9', '09594', 'Hiraya Software Solution', 'student5@gmail.com', 'samalyo', '123', '2025-02-19', 460, NULL),
(52, '1007', 'jordan', 'zapanta', '9', '09594', 'Hiraya Software Solution', 'student6@gmail.com', 'samalyo', '123', '2025-02-19', 460, NULL),
(53, '1008', 'jordan', 'Rivera', '9', '09594', 'Hiraya Software Solution', 'student7@gmail.com', 'samalyo', '123', '2025-02-19', 460, NULL),
(54, '1009', 'jordan', 'zapanta', '9', '09594', 'Hiraya Software Solution', 'student8@gmail.com', 'samalyo', '123', '2025-02-19', 460, NULL),
(55, '10010', 'jordan', 'galang', '9', '09594', 'Hiraya Software Solution', 'student9@gmail.com', 'samalyo', '123', '2025-02-19', 460, NULL),
(56, '10011', 'jordan', 'zapanta', '9', '09594', 'Hiraya Software Solution', 'student10@gmail.com', '123', '123', '2025-02-19', 460, NULL),
(57, '', '', '', '', '', '', '', '', '', '', 0, ''),
(58, '', '', '', '', '', '', '', '', '', '', 0, ''),
(59, '', '', '', '', '', '', '', '', '', '', 0, ''),
(60, '', '', '', '', '', '', '', '', '', '', 0, ''),
(61, '', '', '', '', '', '', '', '', '', '', 0, ''),
(62, '', '', '', '', '', '', '', '', '', '', 0, ''),
(63, '', '', '', '', '', '', '', '', '', '', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `tblsubmit`
--

CREATE TABLE `tblsubmit` (
  `Id` int(11) NOT NULL,
  `Task_Code` varchar(200) NOT NULL,
  `Student_id` varchar(255) NOT NULL,
  `Uploaded_File` varchar(255) NOT NULL,
  `Date_of_Submission` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbltask`
--

CREATE TABLE `tbltask` (
  `Id` int(11) NOT NULL,
  `Task_Code` varchar(255) NOT NULL,
  `Task_Name` varchar(255) NOT NULL,
  `Description` varchar(255) NOT NULL,
  `Deadline` varchar(255) NOT NULL,
  `Student_ID` varchar(200) NOT NULL,
  `Files` varchar(255) NOT NULL,
  `Date_Submit` varchar(50) NOT NULL,
  `Stat` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbltask`
--

INSERT INTO `tbltask` (`Id`, `Task_Code`, `Task_Name`, `Description`, `Deadline`, `Student_ID`, `Files`, `Date_Submit`, `Stat`) VALUES
(4, 'TSK-001', '1st Week', '1st week of weekly report', '2024-09-30', '2018-0239', 'Capstone_Resibo_Final.pdf', '2024-09-19', 'Submitted');

-- --------------------------------------------------------

--
-- Table structure for table `tblterm`
--

CREATE TABLE `tblterm` (
  `Id` int(10) NOT NULL,
  `termName` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblterm`
--

INSERT INTO `tblterm` (`Id`, `termName`) VALUES
(1, 'First'),
(2, 'Second'),
(3, 'Third');

-- --------------------------------------------------------

--
-- Table structure for table `tbluser`
--

CREATE TABLE `tbluser` (
  `Id` int(11) NOT NULL,
  `emailAddress` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbluser`
--

INSERT INTO `tbluser` (`Id`, `emailAddress`, `password`, `user_type`) VALUES
(3, 'admin@mail.com', '123', 'Admin'),
(29, 'student@gmail.com', '123', 'Student'),
(30, 'student1@gmail.com', '123', 'Student'),
(31, 'student2@gmail.com', '123', 'Student'),
(32, 'student3@gmail.com', '123', 'Student'),
(33, 'student4@gmail.com', '123', 'Student'),
(34, 'student5@gmail.com', '123', 'Student'),
(35, 'student6@gmail.com', '123', 'Student'),
(36, 'student7@gmail.com', '123', 'Student'),
(37, 'student8@gmail.com', '123', 'Student'),
(38, 'student9@gmail.com', '123', 'Student'),
(39, 'student10@gmail.com', '123', 'Student');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_weekly_time_entries`
--

CREATE TABLE `tbl_weekly_time_entries` (
  `id` int(11) NOT NULL,
  `week_start_date` date NOT NULL,
  `monday_time` float DEFAULT 0,
  `tuesday_time` float DEFAULT 0,
  `wednesday_time` float DEFAULT 0,
  `thursday_time` float DEFAULT 0,
  `friday_time` float DEFAULT 0,
  `saturday_time` int(8) DEFAULT NULL,
  `student_fullname` varchar(100) NOT NULL,
  `course` varchar(100) NOT NULL,
  `comp_name` varchar(255) DEFAULT NULL,
  `comp_link` varchar(255) DEFAULT NULL,
  `date_created` datetime DEFAULT current_timestamp(),
  `remaining_time` int(255) DEFAULT NULL,
  `admissionNumber` varchar(255) NOT NULL,
  `photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_weekly_time_entries`
--

INSERT INTO `tbl_weekly_time_entries` (`id`, `week_start_date`, `monday_time`, `tuesday_time`, `wednesday_time`, `thursday_time`, `friday_time`, `saturday_time`, `student_fullname`, `course`, `comp_name`, `comp_link`, `date_created`, `remaining_time`, `admissionNumber`, `photo`) VALUES
(29, '2025-02-17', 8, 8, 8, 8, 8, 7, 'John Dexter  zapanta', 'BSIS 4A', 'Hiraya Software Solution', 'https://src.edu.ph/', '2025-02-18 15:47:07', 300, '1001', '../uploads/IMG_0361.jpg'),
(30, '2025-02-17', 8, 8, 8, 8, 8, 8, 'jordan zapanta', 'BSIS 4A', 'Hiraya Software Solution', 'https://src.edu.ph/', '2025-02-19 09:09:48', 460, '1002', ''),
(31, '2025-02-17', 8, 8, 8, 8, 8, 8, 'jordan zapanta', 'BSIS 4A', 'Hiraya Software Solution', 'https://src.edu.ph/', '2025-02-19 09:10:09', 460, '1003', '../uploads/IMG_0361.jpg'),
(32, '2025-02-17', 8, 8, 8, 8, 8, 8, 'Zapanta Zapanta', 'BSIS 4A', 'Hiraya Software Solution', 'https://src.edu.ph/', '2025-02-19 09:10:30', 412, '1004', '../uploads/IMG_0361.jpg'),
(33, '2025-02-17', 8, 8, 8, 8, 8, NULL, 'darvin galang', 'BSIS 4A', 'Hiraya Software Solution', 'https://src.edu.ph/', '2025-02-19 09:11:34', 460, '1005', '../uploads/IMG_0361.jpg'),
(34, '2025-02-17', 8, 8, 8, 8, 8, 8, 'jordan Tiongco', 'BSIS 4A', 'Hiraya Software Solution', 'https://src.edu.ph/', '2025-02-19 09:12:08', 460, '1006', '../uploads/IMG_0361.jpg'),
(35, '2025-02-17', 8, 8, 8, 8, 8, 8, 'jordan zapanta', 'BSIS 4A', 'Hiraya Software Solution', 'https://src.edu.ph/', '2025-02-19 09:12:26', 460, '1007', '../uploads/IMG_0361.jpg'),
(36, '2025-02-17', 8, 8, 8, 8, 8, NULL, 'jordan zapanta', 'BSIS 4A', 'Hiraya Software Solution', 'https://src.edu.ph/', '2025-02-19 09:13:08', 460, '1009', '../uploads/IMG_1253 (1).jpeg'),
(37, '2025-02-17', 8, 8, 8, 8, 8, NULL, 'jordan galang', 'BSIS 4A', 'Hiraya Software Solution', 'https://src.edu.ph/', '2025-02-19 09:13:27', 460, '10010', '../uploads/BANNER SRC PAGE 24-25.png'),
(38, '2025-02-17', 8, 8, 8, 8, 8, 8, 'jordan zapanta', 'BSIS 4A', 'Hiraya Software Solution', 'https://src.edu.ph/', '2025-02-19 09:13:52', 460, '10011', ''),
(39, '2025-02-17', 8, 8, 8, 8, 8, 5, 'jordan Rivera', 'BSIS 4A', 'Hiraya Software Solution', 'https://src.edu.ph/', '2025-02-19 09:15:48', 460, '1008', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `submissions`
--
ALTER TABLE `submissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbladmin`
--
ALTER TABLE `tbladmin`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tblattendance`
--
ALTER TABLE `tblattendance`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tblclass`
--
ALTER TABLE `tblclass`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tblclassarms`
--
ALTER TABLE `tblclassarms`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tblclassteacher`
--
ALTER TABLE `tblclassteacher`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tblcompany`
--
ALTER TABLE `tblcompany`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tblenroll`
--
ALTER TABLE `tblenroll`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tblsessionterm`
--
ALTER TABLE `tblsessionterm`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tblstudent`
--
ALTER TABLE `tblstudent`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblstudents`
--
ALTER TABLE `tblstudents`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tblsubmit`
--
ALTER TABLE `tblsubmit`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tbltask`
--
ALTER TABLE `tbltask`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tblterm`
--
ALTER TABLE `tblterm`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tbluser`
--
ALTER TABLE `tbluser`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `tbl_weekly_time_entries`
--
ALTER TABLE `tbl_weekly_time_entries`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `submissions`
--
ALTER TABLE `submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbladmin`
--
ALTER TABLE `tbladmin`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tblattendance`
--
ALTER TABLE `tblattendance`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=215;

--
-- AUTO_INCREMENT for table `tblclass`
--
ALTER TABLE `tblclass`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tblclassarms`
--
ALTER TABLE `tblclassarms`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tblclassteacher`
--
ALTER TABLE `tblclassteacher`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `tblcompany`
--
ALTER TABLE `tblcompany`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tblenroll`
--
ALTER TABLE `tblenroll`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tblsessionterm`
--
ALTER TABLE `tblsessionterm`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tblstudent`
--
ALTER TABLE `tblstudent`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblstudents`
--
ALTER TABLE `tblstudents`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `tblsubmit`
--
ALTER TABLE `tblsubmit`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbltask`
--
ALTER TABLE `tbltask`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tblterm`
--
ALTER TABLE `tblterm`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbluser`
--
ALTER TABLE `tbluser`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `tbl_weekly_time_entries`
--
ALTER TABLE `tbl_weekly_time_entries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
