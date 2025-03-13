<?php
// Include session management
include '../Includes/session.php'; 

// Include database connection
include '../Includes/dbcon.php'; 

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $senderName = $_POST['senderName'];
    $admissionNumber = $_POST['admissionNumber']; // Get the student ID
    $message = $_POST['message'];

    // Prepare the SQL statement to update the report
    $updateQuery = "UPDATE tblreports SET adminName = ?, reply = ?, status = 'replied', sent_at = NOW() WHERE admissionNumber = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("sss", $senderName, $message, $admissionNumber); // Assuming admissionNumber is a string

    // Execute the statement and check for success
    if ($stmt->execute()) {
        $successMessage = "Message updated successfully!";
    } else {
        $errorMessage = "Error updating message: " . $stmt->error;
    }

    $stmt->close();
}

// Get the admission number from the session
$admissionNumberFromSession = isset($_SESSION['admissionNumber']) ? $_SESSION['admissionNumber'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="img/logo/attnlg.jpg" rel="icon">
    <title>Update Message</title>
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="css/ruang-admin.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fc;
        }
        .container {
            margin-top: 20px;
        }
    </style>
</head>
<body id="page-top">

<div id="wrapper">
    <!-- Sidebar -->
    <?php include "Includes/sidebar.php"; ?>
    <!-- Sidebar -->
    
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <!-- TopBar -->
            <?php include "Includes/topbar.php"; ?>
            <!-- Topbar -->
            
            <!-- Container Fluid-->
            <div class="container-fluid" id="container-wrapper">
                <h1 class="h3 mb-4 text-gray-800">Update Message</h1>
                <div class="container mt-5">
                    <?php if (isset($successMessage)): ?>
                        <div class="alert alert-success"><?php echo $successMessage; ?></div>
                    <?php elseif (isset($errorMessage)): ?>
                        <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
                    <?php endif; ?>
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="senderName">Your Name:</label>
                            <input type="text" class="form-control" id="senderName" name="senderName" required>
                        </div>
                        <div class="form-group">
                            <label for="admissionNumber">Student ID:</label>
                            <input type="text" class="form-control" id="admissionNumber" name="admissionNumber" value="<?php echo htmlspecialchars($admissionNumberFromSession); ?>" readonly required>
                        </div>
                        <div class="form-group">
                            <label for="message">Message:</label>
                            <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Message</button>
                    </form>
                </div>
            </div>
            <!---Container Fluid-->
        </div>
        <!-- Footer -->
        <?php include "Includes/footer.php"; ?>
        <!-- Footer -->
    </div>
</div>

<!-- Scroll to top -->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<script src="../vendor/jquery/jquery.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="js/ruang-admin.min.js"></script>
</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>