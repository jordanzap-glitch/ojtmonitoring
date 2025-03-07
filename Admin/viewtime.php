<?php
include '../Includes/session.php';
include '../Includes/dbcon.php';

// Initialize variables for editing
$editMode = false;
$editData = [];

// Check if the user is trying to edit an entry
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['Id'])) {
    $editId = $_GET['Id'];
    $editQuery = "SELECT * FROM tbl_weekly_time_entries WHERE id = '$editId'";
    $editResult = mysqli_query($conn, $editQuery);
    if ($editResult) {
        $editData = mysqli_fetch_assoc($editResult);
        $editMode = true; // Set edit mode to true
    }
}

// Handle the update submission
if (isset($_POST['update_time'])) {
    $id = $_POST['id']; // Get the ID of the entry to update
    $weekStartDate = $_POST['week_start_date'];
    $mondayTime = floatval($_POST['monday_time']);
    $tuesdayTime = floatval($_POST['tuesday_time']);
    $wednesdayTime = floatval($_POST['wednesday_time']);
    $thursdayTime = floatval($_POST['thursday_time']);
    $fridayTime = floatval($_POST['friday_time']);
    $saturdayTime = floatval($_POST['saturday_time']);
    $totalHours = $mondayTime + $tuesdayTime + $wednesdayTime + $thursdayTime + $fridayTime + $saturdayTime;
    $imageLink = $_POST['image_link']; // New link input

    // Update the record in the database
    $updateQuery = "UPDATE tbl_weekly_time_entries SET 
        week_start_date = '$weekStartDate',
        monday_time = '$mondayTime',
        tuesday_time = '$tuesdayTime',
        wednesday_time = '$wednesdayTime',
        thursday_time = '$thursdayTime',
        friday_time = '$fridayTime',
        saturday_time = '$saturdayTime',
        total_hours = '$totalHours',
        image_link = '$imageLink'
        WHERE id = '$id'";

    if (mysqli_query($conn, $updateQuery)) {
        $statusMsg = "<div class='alert alert-success'>Record updated successfully!</div>";
        // Optionally redirect to the same page to see the updated list
        header("Location: your_page.php"); // Change 'your_page.php' to the actual page
        exit();
    } else {
        $statusMsg = "<div class='alert alert-danger'>Error updating record: " . mysqli_error($conn) . "</div>";
    }
}

// Handle delete action
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['Id'])) {
    $deleteId = $_GET['Id'];
    $deleteQuery = "DELETE FROM tbl_weekly_time_entries WHERE id = '$deleteId'";
    if (mysqli_query($conn, $deleteQuery)) {
        $statusMsg = "<div class='alert alert-success'>Record deleted successfully!</div>";
    } else {
        $statusMsg = "<div class='alert alert-danger'>Error deleting record!</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link href="img/logo/attnlg.jpg" rel="icon">
  <?php include 'Includes/title.php'; ?>
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
            <h1 class="h3 mb-0 text-gray-800">Weekly Time Entries</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Weekly Time Entries</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Display Submitted Weekly Time -->
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Submitted Weekly Time</h6>
                  <?php echo isset($statusMsg) ? $statusMsg : ''; ?>
                </div>
                <div class="table-responsive p-3">
                  <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                    <thead class="thead-light">
                      <tr>
                        <th>#</th>
                        <th>Week Start Date</th>
                        <th>Admission Number</th>
                        <th>Student Full Name</th>
                        <th>Course</th>
                        <th>Session ID</th>
                        <th>Monday</th>
                        <th>Tuesday</th>
                        <th>Wednesday</th>
                        <th>Thursday</th>
                        <th>Friday</th>
                        <th>Saturday</th>
                        <th>Total Hours</th>
                        <th>Remaining Time</th>
                        <th>Date Created</th>
                        <th>Status</th>
                        <th>Image Link</th>
                        <th>Edit</th>
                        <th>Delete</th>
                      </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT * FROM tbl_weekly_time_entries";
                        $rs = $conn->query($query);
                        $num = $rs->num_rows;
                        $sn = 0;
                        if ($num > 0) {
                            while ($rows = $rs->fetch_assoc()) {
                                $sn++;
                                echo "
                                <tr>
                                    <td>".$sn."</td>
                                    <td>".$rows['week_start_date']."</td>
                                    <td>".$rows['admissionNumber']."</td>
                                    <td>".$rows['student_fullname']."</td>
                                    <td>".$rows['course']."</td>
                                    <td>".$rows['sessionId']."</td>
                                    <td>".$rows['monday_time']."</td>
                                    <td>".$rows['tuesday_time']."</td>
                                    <td>".$rows['wednesday_time']."</td>
                                    <td>".$rows['thursday_time']."</td>
                                    <td>".$rows['friday_time']."</td>
                                    <td>".$rows['saturday_time']."</td>
                                    <td>".$rows['total_hours']."</td>
                                    <td>".$rows['remaining_time']."</td>
                                    <td>".$rows['date_created']."</td>
                                    <td>".$rows['status']."</td>
                                    <td><a href='".$rows['image_link']."' target='_blank'>View Image</a></td>
                                    <td><a href='?action=edit&Id=".$rows['id']."'><i class='fas fa-fw fa-edit'></i>Edit</a></td>
                                    <td><a href='?action=delete&Id=".$rows['id']."'><i class='fas fa-fw fa-trash'></i>Delete</a></td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='15' class='text-center'>No Record Found!</td></tr>";
                        }
                        ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <!--Row-->

          <!-- Edit Form -->
          <?php if ($editMode): ?>
          <div class="row">
            <div class="col-lg-12">
              <div class="card mb-4">
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">Edit Weekly Time Entry</h6>
                </div>
                <div class="card-body">
                  <form method="post" action="">
                    <input type="hidden" name="id" value="<?php echo $editData['id']; ?>">
                    <div class="form-group">
                      <label for="week_start_date">Week Start Date</label>
                      <input type="date" class="form-control" name="week_start_date" value="<?php echo $editData['week_start_date']; ?>" required>
                    </div>
                    <div class="form-group">
                      <label for="monday_time">Monday Time (in hours)</label>
                      <input type="number" class="form-control" name="monday_time" value="<?php echo $editData['monday_time']; ?>" min="0" max="8" step="0.1" required>
                    </div>
                    <div class="form-group">
                      <label for="tuesday_time">Tuesday Time (in hours)</label>
                      <input type="number" class="form-control" name="tuesday_time" value="<?php echo $editData['tuesday_time']; ?>" min="0" max="8" step="0.1" required>
                    </div>
                    <div class="form-group">
                      <label for="wednesday_time">Wednesday Time (in hours)</label>
                      <input type="number" class="form-control" name="wednesday_time" value="<?php echo $editData['wednesday_time']; ?>" min="0" max="8" step="0.1" required>
                    </div>
                    <div class="form-group">
                      <label for="thursday_time">Thursday Time (in hours)</label>
                      <input type="number" class="form-control" name="thursday_time" value="<?php echo $editData['thursday_time']; ?>" min="0" max="8" step="0.1" required>
                    </div>
                    <div class="form-group">
                      <label for="friday_time">Friday Time (in hours)</label>
                      <input type="number" class="form-control" name="friday_time" value="<?php echo $editData['friday_time']; ?>" min="0" max="8" step="0.1" required>
                    </div>
                    <div class="form-group">
                      <label for="saturday_time">Saturday Time (in hours)</label>
                      <input type="number" class="form-control" name="saturday_time" value="<?php echo $editData['saturday_time']; ?>" min="0" max="8" step="0.1" required>
                    </div>
                    <div class="form-group">
                      <label for="image_link">Image Link</label>
                      <input type="text" class="form-control" name="image_link" value="<?php echo $editData['image_link']; ?>" required>
                    </div>
                    <button type="submit" name="update_time" class="btn btn-primary">Update Entry</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
          <?php endif; ?>
          <!-- End Edit Form -->

        </div>
        <!---Container Fluid-->
      </div>
      <!-- Footer -->
       <?php include "Includes/footer.php";?>
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
      $('#dataTableHover').DataTable(); // ID From dataTable with Hover
    });
  </script>
</body>
</html>