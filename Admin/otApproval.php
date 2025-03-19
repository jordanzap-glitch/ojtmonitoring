<?php
// Start output buffering
ob_start();
include '../Includes/session.php';
// Include database connection
include '../Includes/dbcon.php';

// Initialize status message variable
$statusMsg = '';

// Fetch students data from the database, including class name
$query = "
    SELECT s.id, s.admissionNumber, s.firstName, s.lastName, s.classId, s.contact, s.comp_name, s.email, s.address, s.ot_isactive, c.className 
    FROM tblstudents s 
    LEFT JOIN tblclass c ON s.classId = c.Id"; // Join with tblclass to get className
$result = mysqli_query($conn, $query);

// Handle activation/deactivation
if (isset($_GET['action']) && isset($_GET['id'])) {
    $studentId = mysqli_real_escape_string($conn, $_GET['id']);
    $currentStatus = mysqli_real_escape_string($conn, $_GET['action']);

    // Update the ot_active status
    if ($currentStatus == 'activate') {
        $updateQuery = "UPDATE tblstudents SET ot_isactive = 1 WHERE id = '$studentId'";
        mysqli_query($conn, $updateQuery);
        $statusMsg = "<div class='alert alert-success'>Student activated successfully!</div>";
    } else if ($currentStatus == 'deactivate') {
        $updateQuery = "UPDATE tblstudents SET ot_isactive = 0 WHERE id = '$studentId'";
        mysqli_query($conn, $updateQuery);
        $statusMsg = "<div class='alert alert-danger'>Student deactivated successfully!</div>";
    }

    // Redirect to the same page to avoid resubmission
    header("Location: " . $_SERVER['PHP_SELF'] . "?statusMsg=" . urlencode($statusMsg));
    exit;
}

// Check if a status message is set in the URL
if (isset($_GET['statusMsg'])) {
    $statusMsg = $_GET['statusMsg'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Students List</title>
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="css/ruang-admin.min.css" rel="stylesheet">
    <link href="../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"> <!-- DataTables CSS -->
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
                        <h1 class="h3 mb-0 text-gray-800">Students List</h1>
                    </div>

                    <!-- Display Status Message -->
                    

                    <!-- Main Content -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="studentsTable">
                                        <?php if ($statusMsg): ?>
                                            <div class="alert alert-dismissible fade show" role="alert">
                                                <?php echo $statusMsg; ?>
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                        <?php endif; ?>
                                            <thead>
                                                <tr>
                                                    <th>Student ID</th>
                                                    <th>Full Name</th>
                                                    <th>Class Section</th> <!-- New column for Class Name -->
                                                    <th>Contact</th>
                                                    <th>Company Name</th>
                                                    <th>Email</th>
                                                    <th>Address</th>
                                                    <th>OT Active</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (mysqli_num_rows($result) > 0): ?>
                                                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                                        <tr>
                                                            <td><?php echo htmlspecialchars($row['admissionNumber']); ?></td>
                                                            <td><?php echo htmlspecialchars($row['firstName'] . ' ' . $row['lastName']); ?></td>
                                                            <td><?php echo htmlspecialchars($row['className']); ?></td> <!-- Display Class Name -->
                                                            <td><?php echo htmlspecialchars($row['contact']); ?></td>
                                                            <td><?php echo htmlspecialchars($row['comp_name']); ?></td>
                                                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                                                            <td><?php echo htmlspecialchars($row['address']); ?></td>
                                                            <td><?php echo htmlspecialchars($row['ot_isactive']) ? 'Active' : 'Inactive'; ?></td>
                                                            <td>
                                                                <?php if ($row['ot_isactive']): ?>
                                                                    <a href="?action=deactivate&id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">Deactivate</a>
                                                                <?php else: ?>
                                                                    <a href="?action=activate&id=<?php echo $row['id']; ?>" class="btn btn-success btn-sm">Activate</a>
                                                                <?php endif; ?>
                                                            </td>
                                                        </tr>
                                                    <?php endwhile; ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="9" class="text-center">No students found.</td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Main Content -->
                </div>
                <!-- Container Fluid -->
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
        <!-- Page level plugins -->
        <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
        <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>

        <!-- Page level custom scripts -->
        <script>
            $(document).ready(function () {
                $('#studentsTable').DataTable({
                    "paging": true,
                    "lengthChange": true,
                    "searching": true,
                    "ordering": false,
                    "info": true,
                    "autoWidth": false
                });
            });
        </script>
    </body>
</html>

<?php
// End output buffering and flush output
ob_end_flush();
?>