-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 17, 2024 at 07:17 PM
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
(1, 'Admin', '', 'admin@mail.com', '2457dffa9d96aa0e810c980ea8423267');

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
(7, 'BSIS');

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

-- --------------------------------------------------------

--
-- Table structure for table `tblcompany`
--

CREATE TABLE `tblcompany` (
  `Id` int(11) NOT NULL,
  `comp_name` varchar(255) NOT NULL,
  `contact_person` varchar(255) NOT NULL,
  `contact_num` varchar(255) NOT NULL,
  `comp_address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblcompany`
--

INSERT INTO `tblcompany` (`Id`, `comp_name`, `contact_person`, `contact_num`, `comp_address`) VALUES
(2, 'Hiraya Software Solution', 'Katchie Lama', '09126788908', 'Sa May Doorn');

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
  `admissionNumber` varchar(255) NOT NULL,
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `classId` varchar(10) NOT NULL,
  `classArmId` varchar(10) NOT NULL,
  `contact` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `password` varchar(50) NOT NULL,
  `dateCreated` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

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
(3, 'admin@mail.com', '2457dffa9d96aa0e810c980ea8423267', 'Admin');

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for dumped tables
--

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
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tblclassarms`
--
ALTER TABLE `tblclassarms`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tblclassteacher`
--
ALTER TABLE `tblclassteacher`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `tblcompany`
--
ALTER TABLE `tblcompany`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

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
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
