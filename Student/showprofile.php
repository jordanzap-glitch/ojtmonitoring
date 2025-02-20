<?php
// Database configuration
session_start();
include '../db.php';

// Get student_id from request (e.g., from a GET request)
$student_id = isset($_GET['student_id']) ? intval($_GET['student_id']) : 0;

// Prepare and execute the SQL statement
$sql = "SELECT firstname, middlename, lastname, course, contactnumber, email FROM tblstudent WHERE student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

// Start HTML output
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1>Student Profile</h1>
    <?php
    // Check if a student was found
    if ($result->num_rows > 0) {
        // Fetch the student data
        $student = $result->fetch_assoc();
        ?>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($student['firstname'] . ' ' . $student['middlename'] . ' ' . $student['lastname']); ?></h5>
                <p class="card-text"><strong>Course:</strong> <?php echo htmlspecialchars($student['course']); ?></p>
                <p class="card-text"><strong>Contact Number:</strong> <?php echo htmlspecialchars($student['contactnumber']); ?></p>
                <p class="card-text"><strong>Email:</strong> <?php echo htmlspecialchars($student['email']); ?></p>
            </div>
        </div>
        <?php
    } else {
        echo "<p class='alert alert-warning'>No student found with ID: " . htmlspecialchars($student_id) . "</p>";
    }
    ?>
    <a href="index.php" class="btn btn-primary mt-3">Back to Student List</a>
</div>

<!-- Optional JavaScript; choose one of the two! -->
<!-- Option 1: Bootstrap Bundle with Popper -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
// Close the statement and connection
$stmt->close();
$conn->close();
?>