<?php
include 'Includes/dbcon.php'; // Include your database connection

/**
 * Log user actions in the user_logs table.
 *
 * @param int $userId The ID of the user.
 * @param string $userType The type of user (e.g., 'Administrator', 'ClassTeacher', 'Student').
 * @param string $action The action performed by the user (e.g., 'login', 'failed_login').
 * @return void
 */
function logUser Action($userId, $userType, $action) {
    global $conn; // Use the global database connection

    // Prepare the SQL statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO user_logs (user_id, user_type, action) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $userId, $userType, $action); // Bind parameters (integer and string)

    // Execute the statement
    if ($stmt->execute()) {
        // Optionally, you can log success or handle it as needed
    } else {
        // Handle error if needed
        error_log("Error logging user action: " . $stmt->error);
    }

    // Close the statement
    $stmt->close();
}
?>