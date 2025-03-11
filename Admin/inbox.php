<?php
// Include session management
include '../Includes/session.php'; 

// Include database connection
include '../Includes/dbcon.php'; 

// Check if the form has been submitted to update the report status
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['admissionNumber'])) {
    $admissionNumber = $_POST['admissionNumber'];
    
    // Update the status to 'resolved'
    $updateQuery = "UPDATE tblreports SET status = 'resolved' WHERE admissionNumber = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("s", $admissionNumber);
    
    if ($stmt->execute()) {
        // Optionally, you can set a success message
        $successMessage = "Report status updated to resolved.";
    } else {
        $errorMessage = "Error updating report status: " . $stmt->error;
    }
    
    $stmt->close();
}

// Fetch data from tblreports
$query = "SELECT admissionNumber, fullname, course, report, status, created_at FROM tblreports"; // Adjust the query as needed
$result = mysqli_query($conn, $query);

if (!$result) {
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
    <title>Reports Page</title>
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="css/ruang-admin.min.css" rel="stylesheet">
    <style>
        .inbox-item {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .inbox-item:hover {
            background-color: #f1f1f1;
        }
        .report-content {
            margin-top: 10px;
            font-size: 14px;
            color: #555;
        }
        .timestamp {
            font-size: 12px;
            color: #888;
        }
        .resolved {
            color: green;
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
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Reports</h1>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="./">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Reports</li>
                        </ol>
                    </div>

                    <div class="row mb-3">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Below is the list of reports:</h5>
                                    <div id="inbox">
                                        <?php
                                        // Loop through the results and display them in the inbox style
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo "<div class='inbox-item' data-toggle='modal' data-target='#reportModal' data-admission='" . htmlspecialchars($row['admissionNumber']) . "' data-fullname='" . htmlspecialchars($row['fullname']) . "' data-course='" . htmlspecialchars($row['course']) . "' data-report='" . htmlspecialchars($row['report']) . "' data-createdat='" . htmlspecialchars($row['created_at']) . "'>";
                                            echo "<strong>Name: " . htmlspecialchars($row['fullname']) . "</strong> - Course: " . htmlspecialchars($row['course']);
                                            echo "<div class='report-content'> Message: " . nl2br(htmlspecialchars($row['report'])) . "</div>"; // Display report content
                                            echo "<div class='timestamp'>Date: " . htmlspecialchars($row['created_at']) . "</div>"; // Display created_at timestamp
                                            
                                            // Check if the status is resolved and display a check icon
                                            if ($row['status'] === 'resolved') {
                                                echo "<i class='fas fa-check-circle resolved' title='Resolved'></i>";
                                            }
                                            
                                            echo "</div>";
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Row-->

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

    <!-- Modal for report details -->
    <div class="modal fade" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="reportModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reportModalLabel">Report Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><strong>Admission Number:</strong> <span id="modalAdmissionNumber"></span></p>
                    <p><strong>Full Name:</strong> <span id="modalFullName"></span></p>
                    <p><strong>Course:</strong> <span id="modalCourse"></span></p>
                    <p><strong>Message:</strong></p>
                    <p id="modalReport"></p>
                    <p><strong>Date:</strong> <span id="modalCreatedAt"></span></p> <!-- Changed from Created At to Date -->
                </div>
                <div class="modal-footer">
                    <form method="POST" action="">
                        <input type="hidden" id="admissionNumberInput" name="admissionNumber" value="">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">OK</button> <!-- Changed from Update Report to OK -->
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/ruang-admin.min.js"></script>
    <script>
        // jQuery to handle the modal data population
        $('#reportModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var admissionNumber = button.data('admission');
            var fullname = button.data('fullname');
            var course = button.data('course');
            var report = button.data('report');
            var createdAt = button.data('createdat'); // Get the created_at data

            // Update the modal's content
            var modal = $(this);
            modal.find('#modalAdmissionNumber').text(admissionNumber);
            modal.find('#modalFullName').text(fullname);
            modal.find('#modalCourse').text(course);
            modal.find('#modalReport').text(report);
            modal.find('#modalCreatedAt').text(createdAt); // Set the created_at in the modal
            modal.find('#admissionNumberInput').val(admissionNumber); // Set the admission number in the hidden input
        });
    </script>
</body>

</html>

<?php
// Close the database connection
mysqli_close($conn);
?>