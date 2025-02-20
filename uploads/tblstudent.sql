/*
SQLyog Ultimate - MySQL GUI v8.2 
MySQL - 5.0.24a-community-nt 
*********************************************************************
*/
/*!40101 SET NAMES utf8 */;

create table `tblstudent` (
	`student_id` varchar (765),
	`firstname` varchar (765),
	`middlename` varchar (765),
	`lastname` varchar (765),
	`course` varchar (765),
	`contactnumber` varchar (765),
	`email` varchar (765),
	`password` varchar (765)
); 
insert into `tblstudent` (`student_id`, `firstname`, `middlename`, `lastname`, `course`, `contactnumber`, `email`, `password`) values('2001','ian','Castro','ga','BSIS','09612961794','garciaiancarlo1@gmail.com','123456');
