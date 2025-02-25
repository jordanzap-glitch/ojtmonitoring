<?php
include '../Includes/session.php';
include '../Includes/dbcon.php';

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

// Fetch all submissions for admin to approve
$query = "SELECT * FROM tbl_weekly_time_entries WHERE status = 'pending'";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Approval</title>
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1>Pending Submissions</h1>
        <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
            <div class="alert alert-success">Submission approved successfully!</div>
        <?php endif; ?>
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Week Start Date</th>
                    <th>Student Full Name</th>
                    <th>Course</th>
                    <th>Company</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php $sn = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $sn++; ?></td>
                            <td><?php echo $row['week_start_date']; ?></td>
                            <td><?php echo $row['student_fullname']; ?></td>
                            <td><?php echo $row['course']; ?></td>
                            <td><?php echo $row['comp_name']; ?></td>
                            <td>
                                <a href="?action=approve&Id=<?php echo $row['id']; ?>" class="btn btn-success">Approve</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No pending submissions.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>