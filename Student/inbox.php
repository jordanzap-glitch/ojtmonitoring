<?php
// Include session management
include '../Includes/session.php'; 

// Include database connection
include '../Includes/dbcon.php'; 

// Get the admission number from the session
$admissionNumber = $_SESSION['admissionNumber']; // Assuming admissionNumber is stored in session

// Fetch data for the inbox for the specific admission number
$query = "SELECT id, adminName, reply, report, created_at, sent_at, status FROM tblreports WHERE admissionNumber = ? ORDER BY created_at DESC"; // Adjust the query as needed
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $admissionNumber); // Bind the admission number
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// Check if the AJAX request to update status is made
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id']) && isset($_POST['status'])) {
    $reportId = $_POST['id'];
    $status = $_POST['status'];

    // Update the status to "seen"
    $updateQuery = "UPDATE tblreports SET status = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("si", $status, $reportId);

    if ($updateStmt->execute()) {
        echo "Status updated successfully.";
    } else {
        echo "Error updating status: " . $updateStmt->error;
    }

    $updateStmt->close();
    exit; // Exit after handling the AJAX request
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="img/logo/attnlg.jpg" rel="icon">
    <title>Inbox</title>
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
            position: relative; /* Added for positioning the check icon */
        }
        .inbox-item:hover {
            background-color: #f1f1f1;
        }
        .timestamp {
            font-size: 12px;
            color: #888;
        }
        .message-content {
            margin-top: 5px;
            font-size: 14px;
            color: #333;
            font-weight: bold;
        }
        .reply-content {
            margin-top: 5px;
            font-size: 14px;
            color: #555;
        }
        .seen-icon {
            position: absolute;
            top: 15px;
            right: 15px;
            color: green;
            display: none; /* Initially hidden */
        }
    </style>
</head>

<body id="page-top">
    <div id="wrapper">
        <!-- Sidebar -->
       
        <!-- Sidebar -->
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <!-- TopBar -->
                <?php include "Includes/topbar.php"; ?>
                <!-- Topbar -->
                <!-- Container Fluid-->
                <div class="container-fluid" id="container-wrapper">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Inbox</h1>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="./">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Inbox</li>
                        </ol>
                    </div>

                    <div class="row mb-3">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Messages:</h5>
                                    <div id="inbox">
                                        <?php
                                        // Loop through the results and display them in the inbox style
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo "<div class='inbox-item' data-toggle='modal' data-target='#messageModal' data-adminname='" . htmlspecialchars($row['adminName']) . "' data-report='" . htmlspecialchars($row['report']) . "' data-reply='" . htmlspecialchars($row['reply']) . "' data-createdat='" . htmlspecialchars($row['created_at']) . "' data-sentat='" . htmlspecialchars($row['sent_at']) . "' data-id='" . htmlspecialchars($row['id']) . "'>";
                                            echo "<strong>From: " . htmlspecialchars($row['adminName']) . "</strong>";
                                            echo "<div class='reply-content'>From the Admin: " . htmlspecialchars($row['reply']) . "</div>";
                                            echo "<div class='timestamp'>Receive Date: " . htmlspecialchars($row['sent_at']) . "</div>"; // Display sent_at timestamp
                                            echo "<div class='message-content'>Your Message: " . htmlspecialchars($row['report']) . "</div>";
                                            echo "<div class='timestamp'>Sent Date: " . htmlspecialchars($row['created_at']) . "</div>";  // Display created_at timestamp
                                            
                                            // Check if the status is seen and display the check icon
                                            if ($row['status'] === 'seen') {
                                                echo "<i class='fas fa-check-circle seen-icon' title='Seen'></i>";
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
             <br><br><br><br><br><br><br><br><br><br><br>
            <?php include "Includes/footer.php"; ?>
            <?php include "Includes/speeddial.php"; ?>
            <!-- Footer -->
        </div>
    </div>

    <!-- Scroll to top -->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Modal for message details -->
    <div class="modal fade" id="messageModal" tabindex="-1" role="dialog" aria-labelledby="messageModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="messageModalLabel">Message Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><strong>From:</strong> <span id="modalAdminName"></span></p>
                    <hr> <!-- Line under From: -->
                    <p><strong>Message from the Admin:</strong></p>
                    <p id="modalReply"></p>
                    <p><strong>Receive Date:</strong> <span id="modalSentAt"></span></p>
                    <hr> <!-- Line under Receive Date: -->
                    <p><strong>Your Message:</strong></p>
                    <p id="modalReport"></p>
                    <p><strong>Sent Date:</strong> <span id="modalCreatedAt"></span></p> <!-- Added Sent Date -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
        $('#messageModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var adminName = button.data('adminname'); // Extract info from data-* attributes
            var report = button.data('report');
            var reply = button.data('reply');
            var createdAt = button.data('createdat'); // Get created_at for modal
            var sentAt = button.data('sentat'); // Get sent_at for modal
            var reportId = button.data('id'); // Get report ID

            // Update the modal's content
            var modal = $(this);
            modal.find('#modalAdminName').text(adminName);
            modal.find('#modalReport').text(report);
            modal.find('#modalReply').text(reply);
            modal.find('#modalCreatedAt').text(createdAt); // Set created_at in modal
            modal.find('#modalSentAt').text(sentAt); // Set sent_at in modal

            // AJAX call to update the status to "seen"
            $.ajax({
                url: '', // This will be the same file
                type: 'POST',
                data: { id: reportId, status: 'seen' },
                success: function(response) {
                    console.log(response); // Optional: log the response for debugging
                },
                error: function(xhr, status, error) {
                    console.error("Error updating status: " + error);
                }
            });
        });
    </script>
</body>

</html>

<?php
// Close the database connection
mysqli_close($conn);
?>