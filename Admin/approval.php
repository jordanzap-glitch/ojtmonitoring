<?php
// Start output buffering
ob_start();
include '../Includes/session.php';
// Include database connection
include '../Includes/dbcon.php';

// Initialize search variables
$searchTerm = '';
$selectedCourse = '';
$selectedCompany = '';
$searchApprovedTerm = '';
$searchDeniedTerm = '';

// Function to update remarks
function updateRemarks($conn, $entryId, $remarks) {
    $entryId = mysqli_real_escape_string($conn, $entryId);
    $remarks = mysqli_real_escape_string($conn, $remarks);
    $updateRemarksQuery = "UPDATE tbl_weekly_time_entries SET remarks = '$remarks' WHERE id = '$entryId'";
    return mysqli_query($conn, $updateRemarksQuery);
}

// Check if a search term has been submitted for pending submissions
if (isset($_POST['search'])) {
    $searchTerm = mysqli_real_escape_string($conn, $_POST['search_term']);
}

// Check if a course or company has been selected for sorting
if (isset($_POST['sort'])) {
    $selectedCourse = mysqli_real_escape_string($conn, $_POST['course']);
    $selectedCompany = mysqli_real_escape_string($conn, $_POST['company']);
}

// Approve submission
if (isset($_GET['action']) && $_GET['action'] == 'approve' && isset($_GET['Id'])) {
    $entryId = $_GET['Id'];

    // Fetch the entry details
    $entryQuery = "SELECT * FROM tbl_weekly_time_entries WHERE id = '$entryId'";
    $entryResult = mysqli_query($conn, $entryQuery);
    $entry = mysqli_fetch_assoc($entryResult);

    if ($entry) {
        // Calculate total hours submitted
        $totalHours = $entry['monday_time'] + $entry['tuesday_time'] + $entry['wednesday_time'] + $entry['thursday_time'] + $entry['friday_time'] + $entry['saturday_time'];
        
        // Include bonus time and overtime in total hours
        $totalHours += $entry['bon_time'] + $entry['over_time'];

        // Update the remaining_time in tblstudents
        $admissionNumber = $entry['admissionNumber'];
        $updateQuery = "UPDATE tblstudents SET remaining_time = remaining_time - $totalHours WHERE admissionNumber = '$admissionNumber'";
        mysqli_query($conn, $updateQuery);

        // Update the remaining_time in tbl_weekly_time_entries
        $remainingTime = $entry['remaining_time'] - $totalHours; // Calculate new remaining time
        $updateEntryQuery = "UPDATE tbl_weekly_time_entries SET remaining_time = $remainingTime, status = 'submitted' WHERE id = '$entryId'";
        mysqli_query($conn, $updateEntryQuery);

        // Redirect or show success message
        header("Location: approval.php?status=success");
        exit;
    }
}

// Deny submission
if (isset($_POST['deny_submission'])) {
    $entryId = mysqli_real_escape_string($conn, $_POST['entry_id']);
    $remarks = mysqli_real_escape_string($conn, $_POST['remarks']);

    // Update the status to 'denied' and add remarks
    $updateEntryQuery = "UPDATE tbl_weekly_time_entries SET status = 'denied', remarks = '$remarks' WHERE id = '$entryId'";
    mysqli_query($conn, $updateEntryQuery);

    // Redirect or show success message
    header("Location: approval.php?status=denied");
    exit;
}

// Update bonus time
if (isset($_POST['update_bonus'])) {
    $entryId = mysqli_real_escape_string($conn, $_POST['entry_id']);
    $bonusTime = mysqli_real_escape_string($conn, $_POST['bonus_time']);

    $updateBonusQuery = "UPDATE tbl_weekly_time_entries SET bon_time = '$bonusTime' WHERE id = '$entryId'";
    mysqli_query($conn, $updateBonusQuery);
}

// Update remarks
if (isset($_POST['update_remarks'])) {
    $entryId = $_POST['entry_id'];
    $remarks = $_POST['remarks'];

    if (updateRemarks($conn, $entryId, $remarks)) {
        header("Location: approval.php?status=remarks_updated");
        exit;
    }
}

// Fetch all pending submissions for admin to approve, with optional search and sorting
$query = "
    SELECT w.*, s.sessionName, st.render_time 
    FROM tbl_weekly_time_entries w 
    LEFT JOIN tblsessionterm s ON w.sessionId = s.id 
    LEFT JOIN tblstudents st ON w.admissionNumber = st.admissionNumber
    WHERE w.status = 'pending'";

if (!empty($searchTerm)) {
    $query .= " AND w.student_fullname LIKE '%$searchTerm%'";
}
if (!empty($selectedCourse)) {
    $query .= " AND w.course = '$selectedCourse'";
}
if (!empty($selectedCompany)) {
    $query .= " AND w.comp_name = '$selectedCompany'";
}
$query .= " ORDER BY w.course, w.comp_name"; // Sort by course and company name
$result = mysqli_query($conn, $query);

// Fetch approved submissions with search functionality
$approvedQuery = "SELECT * FROM tbl_weekly_time_entries WHERE status = 'submitted'";
if (isset($_POST['search_approved_btn']) && !empty($_POST['search_approved'])) {
    $searchApprovedTerm = mysqli_real_escape_string($conn, $_POST['search_approved']);
    $approvedQuery .= " AND student_fullname LIKE '%$searchApprovedTerm%'";
}
$approvedResult = mysqli_query($conn, $approvedQuery);

// Fetch denied submissions with search functionality
$deniedQuery = "SELECT * FROM tbl_weekly_time_entries WHERE status = 'denied'";
if (isset($_POST['search_denied_btn']) && !empty($_POST['search_denied'])) {
    $searchDeniedTerm = mysqli_real_escape_string($conn, $_POST['search_denied']);
    $deniedQuery .= " AND student_fullname LIKE '%$searchDeniedTerm%'";
}
$deniedResult = mysqli_query($conn, $deniedQuery);

// Fetch distinct courses for the dropdown
$coursesQuery = "SELECT DISTINCT course FROM tbl_weekly_time_entries";
$coursesResult = mysqli_query($conn, $coursesQuery);
$courses = [];
while ($row = mysqli_fetch_assoc($coursesResult)) {
    $courses[] = $row['course'];
}

// Fetch distinct companies for the dropdown
$companiesQuery = "SELECT DISTINCT comp_name FROM tbl_weekly_time_entries";
$companiesResult = mysqli_query($conn, $companiesQuery);
$companies = [];
while ($row = mysqli_fetch_assoc($companiesResult)) {
    $companies[] = $row['comp_name'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Approval</title>
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
                        <h1 class="h3 mb-0 text-gray-800">Pending Submissions</h1>
                        <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
                            <div class="alert alert-success">Submission approved successfully!</div>
                        <?php elseif (isset($_GET['status']) && $_GET['status'] == 'denied'): ?>
                            <div class="alert alert-danger">Submission denied successfully!</div>
                        <?php elseif (isset($_GET['status']) && $_GET['status'] == 'remarks_updated'): ?>
                            <div class="alert alert-success">Remarks updated successfully!</div>
                        <?php endif; ?>
                    </div>

                    <!-- Search and Sort Form -->
                    <form method="post" class="mb-3">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <input type="text" name="search_term" class="form-control" placeholder="Search by Student Full Name" value="<?php echo htmlspecialchars($searchTerm); ?>">
                            </div>
                            <div class="col-md-4">
                                <select name="course" class="form-control">
                                    <option value="">Select Course</option>
                                    <?php foreach ($courses as $course): ?>
                                        <option value="<?php echo htmlspecialchars($course); ?>" <?php echo ($selectedCourse == $course) ? 'selected' : ''; ?>><?php echo htmlspecialchars($course); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select name="company" class="form-control">
                                    <option value="">Select Company</option>
                                    <?php foreach ($companies as $company): ?>
                                        <option value="<?php echo htmlspecialchars($company); ?>" <?php echo ($selectedCompany == $company) ? 'selected' : ''; ?>><?php echo htmlspecialchars($company); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <button class="btn btn-primary" type="submit" name="search">Search</button>
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-secondary" type="submit" name="sort">Sort</button>
                            </div>
                        </div>
                    </form>
                    <br>

                    <!-- Table for Pending Submissions -->
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table" id="dataTableHover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Week Start Date</th>
                                    <th>Student ID</th>
                                    <th>Student Full Name</th>
                                    <th>Course</th>
                                    <th>Company</th>
                                    <th>Session ID</th>
                                    <th>Session Name</th>
                                    <th>Monday</th>
                                    <th>Tuesday</th>
                                    <th>Wednesday</th>
                                    <th>Thursday</th>
                                    <th>Friday</th>
                                    <th>Saturday</th>
                                    <th>Total Hours</th>
                                    <th>Bonus Time</th>
                                    <th>Overtime</th> <!-- New column for Overtime -->
                                    <th>Remaining Time</th>
                                    <th>Render Time</th>
                                    <th>Date Submitted</th> <!-- New column for Date Created -->
                                    <th>DTR Image Link</th>
                                    <th>Approve</th>
                                    <th>Deny</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (mysqli_num_rows($result) > 0): ?>
                                    <?php $sn = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                                        <tr>
                                            <td><?php echo $sn++; ?></td>
                                            <td><?php echo $row['week_start_date']; ?></td>
                                            <td><?php echo $row['admissionNumber']; ?></td> 
                                            <td><?php echo $row['student_fullname']; ?></td>
                                            <td><?php echo $row['course']; ?></td>
                                            <td><?php echo $row['comp_name']; ?></td>
                                            <td><?php echo $row['sessionId']; ?></td>
                                            <td><?php echo $row['sessionName']; ?></td>
                                            <td><?php echo $row['monday_time']; ?></td>
                                            <td><?php echo $row['tuesday_time']; ?></td>
                                            <td><?php echo $row['wednesday_time']; ?></td>
                                            <td><?php echo $row['thursday_time']; ?></td>
                                            <td><?php echo $row['friday_time']; ?></td>
                                            <td><?php echo $row['saturday_time']; ?></td>
                                            <td>
                                                <?php 
                                                // Calculate total hours for display
                                                $totalHours = $row['monday_time'] + $row['tuesday_time'] + $row['wednesday_time'] + $row['thursday_time'] + $row['friday_time'] + $row['saturday_time'] + $row['bon_time'] + $row['over_time'];
                                                echo $totalHours; 
                                                ?>
                                            </td>
                                            <td>
                                                <form method="post" class="d-inline">
                                                    <input type="hidden" name="entry_id" value="<?php echo $row['id']; ?>">
                                                    <input type="number" name="bonus_time" value="<?php echo $row['bon_time']; ?>" class="form-control" style="width: 80px ; display: inline;">
                                                    <button type="submit" name="update_bonus" class="btn btn-warning btn-sm">Update</button>
                                                </form>
                                            </td>
                                            <td><?php echo $row['over_time']; ?></td> <!-- Display Overtime -->
                                            <td><?php echo $row['remaining_time']; ?></td>
                                            <td><?php echo $row['render_time']; ?></td>
                                            <td><?php echo $row['date_created']; ?></td> <!-- Display Date Created -->
                                            <td>
                                                <a href="<?php echo htmlspecialchars($row['image_link']); ?>" target="_blank" class="btn btn-info btn-sm">View Image</a>
                                            </td>
                                            <td>
                                                <a href="?action=approve&Id=<?php echo $row['id']; ?>" class="btn btn-success">Approve</a>
                                            </td>
                                            <td>
                                                <button class="btn btn-danger" data-toggle="modal" data-target="#denyModal<?php echo $row['id']; ?>">Deny</button>
                                            </td>
                                        </tr>

                                        <!-- Deny Modal -->
                                        <div class="modal fade" id="denyModal<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="denyModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="denyModalLabel">Deny Submission</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form method="post" action="">
                                                        <div class="modal-body">
                                                            <input type="hidden" name="entry_id" value="<?php echo $row['id']; ?>">
                                                            <div class="form-group">
                                                                <label for="remarks">Remarks:</label>
                                                                <textarea class="form-control" name="remarks" id="remarks" required></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            <button type="submit" name="deny_submission" class="btn btn-danger">Deny</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="20" class="text-center">No pending submissions.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <!---Container Fluid-->
                </div>
                    <br><br><br><br><br><br>
                <!-- Approved Submissions Table -->
                <div class="container-fluid" id="container-wrapper">
                    <h2 class="h4 mb-4">Approved Submissions</h2>
                   
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table" id="approvedTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Week Start Date</th>
                                    <th>Student ID</th>
                                    <th>Student Full Name</th>
                                    <th>Course</th>
                                    <th>Company</th>
                                    <th>Session ID</th>
                                    <th>Session Name</th>
                                    <th>Total Hours</th>
                                    <th>Remaining Time</th>
                                    <th>DTR Image Link</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                // Fetch approved submissions with search functionality
                                $approvedQuery = "SELECT * FROM tbl_weekly_time_entries WHERE status = 'submitted'";
                                if (isset($_POST['search_approved_btn']) && !empty($_POST['search_approved'])) {
                                    $searchApprovedTerm = mysqli_real_escape_string($conn, $_POST['search_approved']);
                                    $approvedQuery .= " AND student_fullname LIKE '%$searchApprovedTerm%'";
                                }
                                $approvedResult = mysqli_query($conn, $approvedQuery);

                                if (mysqli_num_rows($approvedResult) > 0): 
                                    $sn = 1; while ($row = mysqli_fetch_assoc($approvedResult)): ?>
                                        <tr>
                                            <td><?php echo $sn++; ?></td>
                                            <td><?php echo $row['week_start_date']; ?></td>
                                            <td><?php echo $row['admissionNumber']; ?></td> 
                                            <td><?php echo $row['student_fullname']; ?></td>
                                            <td><?php echo $row['course']; ?></td>
                                            <td><?php echo $row['comp_name']; ?></td>
                                            <td><?php echo $row['sessionId']; ?></td>
                                            <td><?php echo $row['sessionName']; ?></td>
                                            <td>
                                                <?php 
                                                // Calculate total hours for display
                                                $totalHours = $row['monday_time'] + $row['tuesday_time'] + $row['wednesday_time'] + $row['thursday_time'] + $row['friday_time'] + $row['saturday_time'] + $row['bon_time'] + $row['over_time'];
                                                echo $totalHours; 
                                                ?>
                                            </td>
                                            <td><?php echo $row['remaining_time']; ?></td>
                                            <td>
                                                <a href="<?php echo htmlspecialchars($row['image_link']); ?>" target="_blank" class="btn btn-info btn-sm">View Image</a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="11" class="text-center">No approved submissions.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <br><br><br><br><br><br>

                <!-- Denied Submissions Table -->
                <div class="container-fluid" id="container-wrapper">
                    <h2 class="h4 mb-4">Denied Submissions</h2>
                    
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table" id="deniedTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Week Start Date</th>
                                    <th>Student ID</th>
                                    <th>Student Full Name</th>
                                    <th>Course</th>
                                    <th>Company</th>
                                    <th>Session ID</th>
                                    <th>Session Name</th>
                                    <th>Total Hours</th>
                                    <th>Remaining Time</th>
                                    <th>DTR Image Link</th>
                                    <th>Remarks</th>
                                    <th>Edit Remarks</th> <!-- New column for Edit Remarks -->
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                // Fetch denied submissions with search functionality
                                $deniedQuery = "SELECT * FROM tbl_weekly_time_entries WHERE status = 'denied'";
                                if (isset($_POST['search_denied_btn']) && !empty($_POST['search_denied'])) {
                                    $searchDeniedTerm = mysqli_real_escape_string($conn, $_POST['search_denied']);
                                    $deniedQuery .= " AND student_fullname LIKE '%$searchDeniedTerm%'";
                                }
                                $deniedResult = mysqli_query($conn, $deniedQuery);

                                if (mysqli_num_rows($deniedResult) > 0): 
                                    $sn = 1; while ($row = mysqli_fetch_assoc($deniedResult)): ?>
                                        <tr>
                                            <td><?php echo $sn++; ?></td>
                                            <td><?php echo $row['week_start_date']; ?></td>
                                            <td><?php echo $row['admissionNumber']; ?></td> 
                                            <td><?php echo $row['student_fullname']; ?></td>
                                            <td><?php echo $row['course']; ?></td>
                                            <td><?php echo $row['comp_name']; ?></td>
                                            <td><?php echo $row['sessionId']; ?></td>
                                            <td><?php echo $row['sessionName']; ?></td>
                                            <td>
                                                <?php 
                                                // Calculate total hours for display
                                                $totalHours = $row['monday_time'] + $row['tuesday_time'] + $row['wednesday_time'] + $row['thursday_time'] + $row['friday_time'] + $row['saturday_time'] + $row['bon_time'] + $row['over_time'];
                                                echo $totalHours; 
                                                ?>
                                            </td>
                                            <td><?php echo $row['remaining_time']; ?></td>
                                            <td>
                                                <a href="<?php echo htmlspecialchars($row['image_link']); ?>" target="_blank" class="btn btn-info btn-sm">View Image</a>
                                            </td>
                                            <td>
                                                <button class="btn btn-warning" data-toggle="modal" data-target="#remarksModal<?php echo $row['id']; ?>">View Remarks</button>
                                            </td>
                                            <td>
                                                <button class="btn btn-primary" data-toggle="modal" data-target="#editRemarksModal<?php echo $row['id']; ?>">Edit Remarks</button>
                                            </td>
                                        </tr>

                                        <!-- Remarks Modal -->
                                        <div class="modal fade" id="remarksModal<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="remarksModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="remarksModalLabel">Remarks for Student ID: <?php echo $row['admissionNumber']; ?></h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p><?php echo nl2br(htmlspecialchars($row['remarks'])); ?></p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Edit Remarks Modal -->
                                        <div class="modal fade" id="editRemarksModal<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editRemarksModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editRemarksModalLabel">Edit Remarks for Student ID: <?php echo $row['admissionNumber']; ?></h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form method="post" action="">
                                                        <div class="modal-body">
                                                            <input type="hidden" name="entry_id" value="<?php echo $row['id']; ?>">
                                                            <div class="form-group">
                                                                <label for="remarks">Remarks:</label>
                                                                <textarea class="form-control" name="remarks" id="remarks" required><?php echo htmlspecialchars($row['remarks']); ?></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            <button type="submit" name="update_remarks" class="btn btn-primary">Update Remarks</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="12" class="text-center">No denied submissions.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
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
        <!-- Page level plugins -->
        <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
        <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>

        <!-- Page level custom scripts -->
        <script>
            $(document).ready(function () {
                $('#dataTableHover').DataTable({
                    "paging": true,
                    "lengthChange": true,
                    "searching": true,
                    "ordering": false,
                    "info": true,
                    "autoWidth": false
                });
                $('#approvedTable').DataTable({
                    "paging": true,
                    "lengthChange": true,
                    "searching": true,
                    "ordering": false,
                    "info": true,
                    "autoWidth": false
                });
                $('#deniedTable').DataTable({
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