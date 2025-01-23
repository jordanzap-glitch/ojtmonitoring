<?php
include '../Includes/dbcon.php';  // Ensure this file contains the mysqli connection setup
include '../Includes/session.php';

$targetDir = "../uploads/"; 
$fileName = basename($_FILES['file']['name']); 
$targetFilePath = $targetDir . $fileName; 
$TaskCode=$_POST['Task_Code'];
$dateCreated = date("Y-m-d");
$StudID=$_POST['Student_ID'];


$uploadOk = true; 
$uploaded = false;

// Attempt to upload file
if ($uploadOk) { 
    if (move_uploaded_file($_FILES['file']['tmp_name'], $targetFilePath)) { 
        $uploaded = true;
        $_SESSION['picpath1'] = $targetFilePath; // Store file path in session variable
    } else {
        echo "<script>alert('Photo upload error! Please try again!'); window.close(); window.history.go(-1);</script>";
        exit;
    }
}

// If file uploaded, update database
if ($uploaded) {
    $query = "UPDATE tbltask SET Files = '$fileName', Date_Submit = '$dateCreated' , Stat = 'Submitted', Student_ID = '$StudID' WHERE Task_Code ='$TaskCode'"; // Ensure you have a valid condition to update the right record

    // Execute the query
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "<script>window.close(); window.history.go(-2);</script>";
    } else {
        echo "<script>alert('Database update failed! Please try again!'); window.close(); window.history.go(-1);</script>";
    }
}
?>
