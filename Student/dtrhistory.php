<?php 
error_reporting(0);
include '../Includes/session.php';
include '../Includes/dbcon.php';

// Check if the user is logged in and has an admission number
if (!isset($_SESSION['admissionNumber'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// Fetch the admission number from the session
$admissionNumber = $_SESSION['admissionNumber'];

//------------------------SAVE--------------------------------------------------

//---------------------------------------EDIT------------------------------------------------------------

//--------------------EDIT------------------------------------------------------------

if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "edit") {
    $Id = $_GET['Id'];
    $query = mysqli_query($conn, "SELECT * FROM tbltask WHERE Id ='$Id'");
    $row = mysqli_fetch_array($query);
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
  <?php include 'includes/title.php'; ?>
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
            <h1 class="h3 mb-0 text-gray-800">Submission History</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Submission History</li>
            </ol>
          </div>
          <div class="row">
            <div class="col-lg-12">
              <!-- Input Group -->
              <div class="row">
                <div class="col-lg-12">
                  <div class="card mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                      <h6 class="m-0 font-weight-bold text-primary">History List</h6>
                    </div>
                    <div class="table-responsive p-3">
                      <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                        <thead class="thead-light">
                          <tr>
                            <th>Week Start Date</th>
                            <th>Student Full Name</th>
                            <th>Course</th>
                            <th>Company</th>
                            <th>Company Link</th>
                            <th>Monday</th>
                            <th>Tuesday</th>
                            <th>Wednesday</th>
                            <th>Thursday</th>
                            <th>Friday</th>
                            <th>Saturday</th>
                            <th>Total Hours</th>
                            <th>Remaining Time</th> <!-- New column for Remaining Time -->
                            <th>Photo</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          // Fetch the submission history for the logged-in student
                          $query = "SELECT * FROM tbl_weekly_time_entries WHERE admissionNumber = '$admissionNumber'";
                          $rs = $conn->query($query);
                          $num = $rs->num_rows;
                          $sn = 0;

                          if ($num > 0) {
                              while ($rows = $rs->fetch_assoc()) {
                                  $sn++;
                                  $totalHours = $rows['monday_time'] + $rows['tuesday_time'] + $rows['wednesday_time'] + $rows['thursday_time'] + $rows['friday_time'] + $rows['saturday_time'];
                                  echo "
                                  <tr>
                                      <td>" . $rows['week_start_date'] . "</td>
                                      <td>" . $rows['student_fullname'] . "</td>
                                      <td>" . $rows['course'] . "</td>
                                      <td>" . $rows['comp_name'] . "</td>
                                      <td>" . $rows['comp_link'] . "</td>
                                      <td>" . $rows['monday_time'] . "</td>
                                      <td>" . $rows['tuesday_time'] . "</td>
                                      <td>" . $rows['wednesday_time'] . "</td>
                                      <td>" . $rows['thursday_time'] . "</td>
                                      <td>" . $rows['friday_time'] . "</td>
                                      <td>" . $rows['saturday_time'] . "</td>
                                      <td>" . $totalHours . "</td>
                                      <td>" . $rows['remaining_time'] . "</td> <!-- Display Remaining Time -->
                                      <td><a href='" . $rows['photo'] . "' target='_blank'><img src='" . $rows['photo'] . "' alt='Uploaded Photo' style='width: 50px; height: auto;'></a></td>
                                  </tr>";
                              }
                          } else {
                              echo "<tr><td colspan='14' class='text-center'>No Record Found!</td></tr>";
                          }
                          ?>
                        </tbody>
                      </table>
                    </div>
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