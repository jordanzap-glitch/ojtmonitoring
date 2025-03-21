<?php 
error_reporting(0);
include '../Includes/session.php';
include '../Includes/dbcon.php';

$statusMsg = "";

// Fetch student class information
$query = "SELECT tblclass.className, tblstudents.remaining_time
          FROM tblstudents
          INNER JOIN tblclass ON tblclass.Id = tblstudents.classId
          WHERE tblstudents.Id = '$_SESSION[userId]'";

$rs = $conn->query($query);
$num = $rs->num_rows;
$rrw = $rs->fetch_assoc();

$remainingTime = $rrw['remaining_time']; // Get remaining time from the fetched data

// Fetch the latest announcement including image_path
$announcementQuery = "SELECT adminName, content, date_created, image_path FROM tblannouncement ORDER BY date_created DESC LIMIT 1"; // Fetch the latest announcement
$announcementResult = $conn->query($announcementQuery);
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
  <style>
    /* Zoom effect for the image in the modal */
    #modalImage {
      transition: transform 0.2s; /* Animation */
      max-width: 100%; /* Responsive */
      height: auto; /* Maintain aspect ratio */
    }
    #modalImage:hover {
      transform: scale(1.5); /* Zoom in */
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
            <h1 class="h3 mb-0 text-gray-800">Student Dashboard (<?php echo htmlspecialchars($rrw['className']); ?>)</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
          </div>

          <div class="row mb-3">
            <!-- Remaining Time Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Remaining Time</div>
                      <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo htmlspecialchars($remainingTime); ?> hours</div>
                      <div class="mt-2 mb-0 text-muted text-xs"></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-clock fa-2x text-success"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <div class="card mb-4">
                <div class="card-header py-3 d-flex justify-content-center">
                  <h6 class="m-0 font-weight-bold text-primary">Latest Announcement</h6>
                </div>
                <div class="card-body">
                  <ul class="list-group">
                    <?php if ($announcementResult->num_rows > 0): ?>
                      <?php $announcement = $announcementResult->fetch_assoc(); ?>
                      <li class="list-group-item">
                        <p><strong class="mb-1">Published by: <?php echo htmlspecialchars($announcement['adminName']); ?></strong></p>
                        <strong><?php echo htmlspecialchars($announcement['content']); ?></strong>
                        <br><br><br><br><br>
                        <?php if (!empty($announcement['image_path'])): ?>
                          <div class="mt-2">
                            <img src="uploads/<?php echo htmlspecialchars($announcement['image_path']); ?>" alt="Announcement Image" class="img-fluid" data-toggle="modal" data-target="#imageModal" data-image="uploads/<?php echo htmlspecialchars($announcement['image_path']); ?>">
                          </div>
                          <br><br><br>
                        <?php endif; ?>
                        <p class="mb-0"><small><?php echo date('F j, Y, g:i a', strtotime($announcement['date_created'])); ?></small></p>
                      </li>
                    <?php else: ?>
                      <li class="list-group-item">No announcements available.</li>
                    <?php endif; ?>
                  </ul>
                </div>
              </div>
            </div>
          </div>
          <!---Container Fluid-->
        </div>
      </div>
    </div>
  </div>

  <!-- Image Modal -->
  <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <img src="" id="modalImage" class="img-fluid" alt="">
        </div>
      </div>
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

  <script>
    // Set the image source in the modal when the image is clicked
    $('#imageModal').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget); // Button that triggered the modal
      var imageSrc = button.data('image'); // Extract info from data-* attributes
      var modal = $(this);
      modal.find('#modalImage').attr('src', imageSrc); // Update the modal's image source
    });
  </script>
</body>

</html>