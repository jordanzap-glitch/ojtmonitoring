<?php
// Include session management
include '../Includes/session.php'; 

// Include database connection
include '../Includes/dbcon.php'; 

// Function to handle the reply to a report
function handleReply($conn) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['replyReportId'])) {
        $replyReportId = $_POST['replyReportId'];
        $replyMessage = $_POST['replyMessage'];
        
        // Construct admin name from session variables
        $adminName = $_SESSION['firstName'] . ' ' . $_SESSION['lastName'];

        // Update the report with the reply
        $replyQuery = "UPDATE tblreports SET reply = ?, adminName = ?, sent_at = NOW(), status = 'replied' WHERE id = ?";
        $stmt = $conn->prepare($replyQuery);
        $stmt->bind_param("ssi", $replyMessage, $adminName, $replyReportId);

        if ($stmt->execute()) {
            return "Reply sent successfully!";
        } else {
            return "Error sending reply: " . $stmt->error;
        }

        $stmt->close();
    }
}

// Check if the form has been submitted to update the report status
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reportId'])) {
    $reportId = $_POST['reportId'];
    
    // Update the status to 'resolved'
    $updateQuery = "UPDATE tblreports SET status = 'resolved' WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("i", $reportId);
    
    if ($stmt->execute()) {
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

// Handle reply submission
if (isset($_POST['replyReportId'])) {
    $replyMessage = handleReply($conn);
}

// Fetch data from tblreports, prioritizing pending reports over resolved ones
$query = "SELECT id, admissionNumber, fullname, course, report, reply, status, created_at, sent_at 
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
        .reply-content {
            margin-top: 10px;
            font-size: 14px;
            color: #007bff; /* Color for reply text */
        }
        .timestamp {
            font-size: 12px;
            color: #888;
        }
        .resolved {
            color: green;
        }
        .replied {
            color: blue; /* Color for replied status */
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
                                            echo "<div class='inbox-item' data-toggle='modal' data-target='#reportModal' data-admission='" . htmlspecialchars($row['admissionNumber']) . "' data-fullname='" . htmlspecialchars($row['fullname']) . "' data-course ='" . htmlspecialchars($row['course']) . "' data-report='" . htmlspecialchars($row['report']) . "' data-reply='" . htmlspecialchars($row['reply']) . "' data-createdat='" . htmlspecialchars($row['created_at']) . "' data-sentat='" . htmlspecialchars($row['sent_at']) . "' data-reportid='" . htmlspecialchars($row['id']) . "'>";
                                            echo "<strong>Name: " . htmlspecialchars($row['fullname']) . "</strong> - Course: " . htmlspecialchars($row['course']);
                                            echo "<div class='report-content'> Message: " . nl2br(htmlspecialchars($row['report'])) . "</div>"; // Display report content
                                            
                                            // Display reply if it exists
                                            if (!empty($row['reply'])) {
                                                echo "<div class='reply-content'>Reply: " . nl2br(htmlspecialchars($row['reply'])) . "</div>"; // Display reply content
                                            }
                                            
                                            // Display created_at timestamp
                                            echo "<div class='timestamp'>Receive Date: " . htmlspecialchars($row['created_at']) . "</div>"; // Change label to "Receive Date"
                                            
                                            // Check if the status is resolved and display a check icon
                                            if ($row['status'] === 'resolved') {
                                                echo "<i class='fas fa-check-circle resolved' title='Resolved'></i>";
                                            }
                                            // Check if the status is replied and display a check icon
                                            if ($row['status'] === 'replied') {
                                                echo "<i class='fas fa-check-circle replied' title='Replied'></i>";
                                            }
                                            // Add delete icon
                                            echo "<i class='fas fa-trash delete-icon' title ='Delete' data-reportid='" . htmlspecialchars($row['id']) . "'></i>";
                                            
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
                    <p><strong>Student ID:</strong> <span id="modalAdmissionNumber"></span></p>
                    <p><strong>Full Name:</strong> <span id="modalFullName"></span></p>
                    <p><strong>Course:</strong> <span id="modalCourse"></span></p>
                    <hr> <!-- Line under the course -->
                    <p><strong>Message:</strong></p>
                    <p id="modalReport"></p>
                    <p><strong>Receive Date:</strong> <span id="modalCreatedAt"></span></p>
                    <hr> <!-- Line under the receive date -->
                    <p><strong>Reply:</strong></p>
                    <p id="modalReply"></p>
                    <p><strong>Reply Date:</strong> <span id="modalSentAt"></span></p>
                </div>
                <div class="modal-footer">
                    <form method="POST" action="">
                        <input type="hidden" id="reportIdInput" name="reportId" value="">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">OK</button>
                        <button type="button" class="btn btn-info" id="replyButton">Reply</button> <!-- Updated Reply button -->
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for replying to reports -->
    <div class="modal fade" id="replyModal" tabindex="-1" role="dialog" aria-labelledby="replyModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="replyModalLabel">Reply to Report</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="">
                        <input type="hidden" id="replyReportId" name="replyReportId" value="">
                        <div class="form-group">
                            <label for="replyMessage">Your Reply:</label>
                            <textarea class="form-control" id="replyMessage" name="replyMessage" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Send Reply</button>
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
            var button = $(event.relatedTarget);
            var admissionNumber = button.data('admission');
            var fullname = button.data('fullname');
            var course = button.data('course');
            var report = button.data('report');
            var reply = button.data('reply');
            var createdAt = button.data('createdat');
            var sentAt = button.data('sentat');
            var reportId = button.data('reportid');

            var modal = $(this);
            modal.find('#modalAdmissionNumber').text(admissionNumber);
            modal.find('#modalFullName').text(fullname);
            modal.find('#modalCourse').text(course);
            modal.find('#modalReport').text(report);
            modal.find('#modalReply').text(reply); // Display reply in modal
            modal.find('#modalCreatedAt').text(createdAt); // Display receive date
            modal.find('#modalSentAt').text(sentAt); // Display reply date
            modal.find('#reportIdInput').val(reportId);
        });

        // Handle reply button click to show the reply modal
        $('#replyButton').on('click', function() {
            var reportId = $('#reportIdInput').val();
            $('#replyReportId').val(reportId); // Set the report ID in the reply modal
            $('#replyModal').modal('show'); // Show the reply modal
        });

        // Handle delete icon click using event delegation
        $(document).on('click', '.delete-icon', function(event) {
            event.stopPropagation();
            var reportId = $(this).data('reportid');
            if (confirm("Are you sure you want to delete this report?")) {
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