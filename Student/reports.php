<?php 
include '../Includes/session.php'; // Include session management
include '../Includes/dbcon.php'; // Include database connection

$error_message = '';
$success_message = '';

// Check if the user is logged in
// Retrieve admission number and full name from session
$admissionNumber = isset($_SESSION['admissionNumber']) ? $_SESSION['admissionNumber'] : '';
$firstName = isset($_SESSION['firstName']) ? $_SESSION['firstName'] : '';
$lastName = isset($_SESSION['lastName']) ? $_SESSION['lastName'] : '';
$fullname = trim($firstName . ' ' . $lastName); // Combine first and last name

// Handle report submission
if (isset($_POST['submit_report'])) {
    $course = $_POST['course'];
    $report = $_POST['report'];

    // Validate input
    if (empty($course) || empty($report)) {
        $error_message = "All fields are required.";
    } else {
        // Prepare and bind
        $query = "INSERT INTO tblreports (admissionNumber, fullname, course, report, created_at, status) VALUES (?, ?, ?, ?, NOW(), 'pending')";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssss", $admissionNumber, $fullname, $course, $report);

        // Execute the statement
        if ($stmt->execute()) {
            $success_message = "Your message sent successfully.";
        } else {
            $error_message = "Error. Please try again.";
        }

        // Close the statement
        $stmt->close();
    }
}

// Fetch class names from tblclass
$classQuery = "SELECT className FROM tblclass"; // Adjust the query as needed
$classResult = mysqli_query($conn, $classQuery);

if (!$classResult) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="img/logo/attnlg.jpg" rel="icon">
    <title>Report to Admin</title>
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="css/ruang-admin.min.css" rel="stylesheet">
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
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Send a Message to Admin</h1>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="./">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Report to Admin</li>
                        </ol>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card mb-4">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Message</h6>
                                </div>
                                <div class="card-body">
                                    <?php if (!empty($error_message)): ?>
                                        <div class="alert alert-danger" role="alert">
                                            <?php echo htmlspecialchars($error_message); ?>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!empty($success_message)): ?>
                                        <div class="alert alert-success" role="alert">
                                            <?php echo htmlspecialchars($success_message); ?>
                                        </div>
                                    <?php endif; ?>

                                    <form method="POST" action="">
                                        <div class="form-group">
                                            <label for="fullname">Full Name:</label>
                                            <input type="text" class="form-control" id="fullname" name="fullname" value="<?php echo htmlspecialchars($fullname); ?>" readonly>
                                            <input type="text" class="form-control" id="admissionNumber" name="admissionNumber" value="<?php echo htmlspecialchars($admissionNumber); ?>" hidden>
                                        </div>
                                        <div class="form-group">
                                            <label for="course">Course:</label>
                                            <select class="form-control" id="course" name="course" required>
                                                <option value="">Select a course</option>
                                                <?php while ($classRow = mysqli_fetch_assoc($classResult)): ?>
                                                    <option value="<?php echo htmlspecialchars($classRow['className']); ?>"><?php echo htmlspecialchars($classRow['className']); ?></option>
                                                <?php endwhile; ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="report">Message:</label>
                                            <textarea class="form-control" id="report" name="report" rows="4" required></textarea>
                                        </div>
                                        <button type="submit" name="submit_report" class="btn btn-primary">Send</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Row -->
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