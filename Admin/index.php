<?php 
error_reporting(E_ALL);
include '../Includes/session.php'; // Include session management
include '../Includes/dbcon.php'; // Include database connection

$error_message = '';
$success_message = '';

$userId = $_SESSION['userId'];

// Check if the user is logged in
// Fetch the count of pending DTR submissions
$queryPendingDTR = "SELECT COUNT(*) as pendingCount FROM tbl_weekly_time_entries WHERE status = 'pending'";
$resultPendingDTR = $conn->query($queryPendingDTR);
$rowPendingDTR = $resultPendingDTR->fetch_assoc();
$pendingDTRCount = $rowPendingDTR['pendingCount'];

// Fetch class and arm information
$query = "SELECT tblclass.className, tblclassarms.classArmName 
FROM tblclassteacher
INNER JOIN tblclass ON tblclass.Id = tblclassteacher.classId
INNER JOIN tblclassarms ON tblclassarms.Id = tblclassteacher.classArmId
WHERE tblclassteacher.Id = $userId";

$rs = $conn->query($query);
$num = $rs->num_rows;
$rrw = $rs->fetch_assoc();

// Fetch total reports count
$queryReports = "SELECT COUNT(*) as totalReports FROM tblreports";
$resultReports = $conn->query($queryReports);
$rowReports = $resultReports->fetch_assoc();
$totalReportsCount = $rowReports['totalReports'];
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
  <title>Dashboard</title>
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
            <h1 class="h3 mb-0 text-gray-800">Administrator Dashboard</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
          </div>

          <div class="row mb-3">
            <!-- Students Card -->
            <?php 
            $query1 = mysqli_query($conn, "SELECT * from tblstudents");                       
            $students = mysqli_num_rows($query1);
            ?>
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Students</div>
                      <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $students; ?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-users fa-2x text-info"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Total Companies Card -->
            <?php 
            $query1 = mysqli_query($conn, "SELECT * from tblcompany");                       
            $totAttendance = mysqli_num_rows($query1);
            ?>
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Total Companies</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totAttendance; ?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-building fa-2x text-secondary"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Pending DTR Card -->
            <div class="col-xl-3 col-md-6 mb-4">
              <a href="approval.php" style="text-decoration: none; color: inherit;"> <!-- Make the card clickable -->
                <div class="card h-100">
                  <div class="card-body">
                    <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Pending DTR</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $pendingDTRCount; ?></div>
                      </div>
                      <div class="col-auto">
                        <i class="fas fa-clock fa-2x text-warning"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </a>
            </div>

            <!-- Total Reports Card -->
            <div class="col-xl-3 col-md-6 mb-4">
              
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
  <script src="../vendor/chart.js/Chart.min.js"></script>
  <script src="js/demo/chart-area-demo.js"></script>  
</body>

</html>