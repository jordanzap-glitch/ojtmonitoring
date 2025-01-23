<?php

	//Make it cloud database, use SMARTERASP.NET
	//Make sure that it is being uploaded not in the localhost
	//$host = "mysql8003.site4now.net";
	//$user = "root";
	//$pass = "";
	//$db = "ojt_monitoring";

	$host = "localhost";
	$user = "root";
	$pass = "";
	$db = "ojt_monitoring";
	
	$conn = new mysqli($host, $user, $pass, $db);
	if($conn->connect_error){
		echo "Seems like you have not configured the database. Failed To Connect to database:" . $conn->connect_error;
	}
?>

<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "ojt_monitoring";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>

