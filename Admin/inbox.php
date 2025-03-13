<?php
// Include session management
include '../Includes/session.php'; 

// Include database connection
include '../Includes/dbcon.php'; 

// Check if the form has been submitted to update the report status
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reportId'])) {
    $reportId = $_POST['reportId'];
    
    // Update the status to 'resolved'
    $updateQuery = "UPDATE tblreports SET status = 'resolved' WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("i", $reportId);
    
    if ($stmt->execute()) {
        // Optionally, you can set a success message
        $successMessage = "Report status updated to resolved.";
    } else {
        $errorMessage = "Error updating report status: " . $stmt->error;
    }
    
    $stmt->close();
}

// Check if the delete request has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['deleteReportId'])) {
    $deleteReportId = $_POST['deleteReportId'];
    
    // Delete the report
    $deleteQuery = "DELETE FROM tblreports WHERE id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $deleteReportId);
    
    if ($stmt->execute()) {
        $successMessage = "Report deleted successfully.";
    } else {
        $errorMessage = "Error deleting report: " . $stmt->error;
    }
    
    $stmt->close();
}

// Fetch data from tblreports, prioritizing pending reports over resolved ones
$query = "SELECT id, admissionNumber, fullname, course, report, status, created_at 
          FROM tblreports 
          ORDER BY CASE 
              WHEN status = 'pending' THEN 1 
              WHEN status = 'resolved' THEN 2 
              ELSE 3 
          END, created_at DESC"; // Adjust the query as needed
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
            position: relative; /* Added for positioning the delete icon */
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
        .delete-icon {
            position: absolute;
            top: 10px;
            right: 10px;
            color: red;
            cursor: pointer;
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
                                            echo "<div class='inbox-item' data-toggle='modal' data-target='#reportModal' data-admission='" . htmlspecialchars($row['admissionNumber']) . "' data-fullname='" . htmlspecialchars($row['fullname']) . "' data-course='" . htmlspecialchars($row['course']) . "' data-report='" . htmlspecialchars($row['report']) . "' data-createdat='" . htmlspecialchars($row['created_at']) . "' data-reportid='" . htmlspecialchars($row['id']) . "'>";
                                            echo "<strong>Name: " . htmlspecialchars($row['fullname']) . "</strong> - Course: " . htmlspecialchars($row['course']);
                                            echo "<div class='report-content'> Message: " . nl2br(htmlspecialchars($row['report'])) . "</div>"; // Display report content
                                            echo "<div class='timestamp'>Date: " . htmlspecialchars($row['created_at']) . "</div>"; // Display created_at timestamp
                                            
                                            // Check if the status is resolved and display a check icon
                                            if ($row['status'] === 'resolved') {
                                                echo "<i class='fas fa-check-circle resolved' title='Resolved'></i>";
                                            }
                                            
                                            // Add delete icon
                                            echo "<i class='fas fa-trash delete-icon' title='Delete' data-reportid='" . htmlspecialchars($row['id']) . "'></i>";
                                            
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
                    <p><strong>Student ID:</strong> <span id="modalAdmissionNumber"></span></p> <!-- Changed from Admission Number to Student ID -->
                    <p><strong>Full Name:</strong> <span id="modalFullName"></span></p>
                    <p><strong>Course:</strong> <span id="modalCourse"></span></p>
                    <p><strong>Message:</strong></p>
                    <p id="modalReport"></p>
                    <p><strong>Date:</strong> <span id="modalCreatedAt"></span></p> <!-- Changed from Created At to Date -->
                </div>
                <div class="modal-footer">
                    <form method="POST" action="">
                        <input type="hidden" id="reportIdInput" name="reportId" value="">
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
            var reportId = button.data('reportid'); // Get the report ID

            // Update the modal's content
            var modal = $(this);
            modal.find('#modalAdmissionNumber').text(admissionNumber); // Display Student ID
            modal.find('#modalFullName').text(fullname);
            modal.find('#modalCourse').text(course);
            modal.find('#modalReport').text(report);
            modal.find('#modalCreatedAt').text(createdAt); // Set the created_at in the modal
            modal.find('#reportIdInput').val(reportId); // Set the report ID in the hidden input
        });

        // Handle delete icon click using event delegation
        $(document).on('click', '.delete-icon', function(event) {
            event.stopPropagation(); // Prevent the modal from opening
            var reportId = $(this).data('reportid');
            if (confirm("Are you sure you want to delete this report?")) {
                // Create a form to submit the delete request
                var form = $('<form method="POST" action="">');
                form.append($('<input type="hidden" name="deleteReportId" />').val(reportId));
                $('body').append(form);
                form.submit();
            }
        });
    </script>
</body>

</html>

<?php
// Close the database connection
mysqli_close($conn);
?>