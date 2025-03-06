<?php
// Include the database connection
include '../Includes/dbcon.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the student ID and the additional time from the POST request
    $id = $_POST['id'];
    $add_time = $_POST['add_time'];

    // Fetch current render_time and remaining_time for the student
    $result = mysqli_query($conn, "SELECT render_time, remaining_time FROM tblstudents WHERE Id='$id'");
    $student = mysqli_fetch_assoc($result);

    if ($student) {
        // Calculate new render_time and remaining_time
        $new_render_time = $student['render_time'] + $add_time;
        $new_remaining_time = $student['remaining_time'] + $add_time;

        // Update the database with the new values
        $update_query = mysqli_query($conn, "UPDATE tblstudents SET render_time='$new_render_time', remaining_time='$new_remaining_time' WHERE Id='$id'");

        if ($update_query) {
            echo "Time added successfully!";
        } else {
            echo "Error updating time!";
        }
    } else {
        echo "Student not found!";
    }
} else {
    echo "Invalid request method!";
}
?>