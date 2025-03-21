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

// Fetch the latest active announcement including image_path
$announcementQuery = "SELECT adminName, content, date_created, image_path FROM tblannouncement WHERE is_active = 1 ORDER BY date_created DESC LIMIT 1"; // Fetch the latest active announcement
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
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet"> <!-- Tailwind CSS -->
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
    /* Bell icon animation */
    .bell-icon {
      font-size: 2rem; /* Size of the bell icon */
      color: #007bff; /* Color of the bell icon */
      animation: ring 1s infinite; /* Ringing animation */
      position: absolute; /* Positioning for animation */
      right: 20px; /* Position from the right */
      top: 20px; /* Position from the top */
    }
    /* Keyframes for ringing animation */
    @keyframes ring {
      0% { transform: translateY(0); }
      25% { transform: translateY(-5px); }
      50% { transform: translateY(0); }
      75% { transform: translateY(5px); }
      100% { transform: translateY(0); }
    }
    /* Rotate animation for speed dial button */
    .rotate {
      transition: transform 0.3s ease;
      transform: rotate(45deg);
    }

    .speed-dial-menu {
      min-width: 200px; /* Minimum width */
      max-width: 300px; /* Maximum width */
    }

    @keyframes shake {
  0% { transform: translateX(0); }
  25% { transform: translateX(-5px); }
  50% { transform: translateX(5px); }
  75% { transform: translateX(-5px); }
  100% { transform: translateX(0); }
}

.shake {
  animation: shake 0.5s ease-in-out infinite;
}
  </style>
</head>

<body id="page-top" class="bg-gray-100">
  <div id="wrapper">
    <!-- Sidebar -->
    <?php include "Includes/sidebar.php"; ?>
    <!-- Sidebar -->
    <div id="content-wrapper" class="flex flex-col">
      <div id="content">
        <!-- TopBar -->
        <?php include "Includes/topbar.php"; ?>
        <!-- Topbar -->

        <!-- Container Fluid-->
        <div class="container mx-auto p-4">
          <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold text-gray-800">Student Dashboard (<?php echo htmlspecialchars($rrw['className']); ?>)</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./" class="text-blue-500">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-3">
  <!-- Remaining Time Card Example -->
  <div class="bg-white shadow-lg rounded-lg p-6 transition-transform transform hover:scale-105 hover:shadow-xl flex items-center">
    <div class="flex-shrink-0">
      <div class="flex items-center justify-center w-16 h-16 bg-green-100 rounded-full">
        <i class="fas fa-clock fa-3x text-green-500"></i>
      </div>
    </div>
    <div class="ml-4">
      <div class="text-xs font-semibold text-gray-600 uppercase mb-1">Remaining Time</div>
      <div class="text-3xl font-extrabold text-gray-800"><?php echo htmlspecialchars($remainingTime); ?> hours</div>
    </div>
  </div>
</div>

<div class="mb-4">
  <div class="bg-white shadow-lg rounded-lg p-6 announcement-card relative">
    <div class="flex justify-between items-center">
      <h6 class="announcement-header text-xl font-bold text-blue-600 text-center w-full">Latest Announcement</h6>
      <i class="fas fa-bell text-blue-600 text-2xl shake"></i> <!-- Bell icon with shake animation -->
    </div>
    <div class="mt-4">
      <ul class="list-none">
        <?php if ($announcementResult->num_rows > 0): ?>
          <?php $announcement = $announcementResult->fetch_assoc(); ?>
          <li class="border-b border-gray-200 pb-4 mb-4">
            <p class="mb-1 text-gray-600">Published by: <strong><?php echo htmlspecialchars($announcement['adminName']); ?></strong></p>
            <p class="announcement-content text-lg font-semibold text-gray-800"><strong><?php echo htmlspecialchars($announcement['content']); ?></strong></p>
            <?php if (!empty($announcement['image_path'])): ?>
              <div class="mt-2">
                <img src="uploads/<?php echo htmlspecialchars($announcement['image_path']); ?>" alt="Announcement Image" class="w-full h-auto rounded-lg cursor-pointer" data-toggle="modal" data-target="#imageModal" data-image="uploads/<?php echo htmlspecialchars($announcement['image_path']); ?>">
              </div>
            <?php endif; ?>
            <p class="mt-2 text-gray-500"><small><?php echo date('F j, Y, g:i a', strtotime($announcement['date_created'])); ?></small></p>
          </li>
        <?php else: ?>
          <li class="border-b border-gray-200 pb-4 mb-4">No announcements available.</li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</div>
          <!---Container Fluid-->
        </div>
      </div>
    </div>
  </div>
  <div class="w-full relative min-h-[380px]">
    <div class="absolute right-6 bottom-6 group z-50 speeddial-button">
      <div id="speed-dial-menu-dropdown" class="speed-dial-menu flex flex-col hidden items-center mb-4 space-y-2 bg-white shadow-[0px_15px_60px_-4px_rgba(16,24,40,0.10)] rounded-xl border border-gray-200">
        <ul class="text-sm text-gray-600 p-5">
        <li>
            <a href="time.php" class="flex items-center mb-5 hover:text-gray-900">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
          </svg>
              <span class="text-sm font-medium">Submit Time</span>
            </a>
            <li>
              <a href="reports.php" class="flex items-center mb-5 hover:text-gray-900 dark:hover:text-white">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
              </svg>

                <span class="text-sm font-medium">Compose Message</span>
              </a>
              <li>
              <a href="inbox.php" class="flex items-center mb-5 hover:text-gray-900 dark:hover:text-white">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 0 1 2.012 1.244l.256.512a2.25 2.25 0 0 0 2.013 1.244h3.218a2.25 2.25 0 0 0 2.013-1.244l.256-.512a2.25 2.25 0 0 1 2.013-1.244h3.859m-19.5.338V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 0 0-2.15-1.588H6.911a2.25 2.25 0 0 0-2.15 1.588L2.35 13.177a2.25 2.25 0 0 0-.1.661Z" />
              </svg>
                <span class="text-sm font-medium">Inbox</span>
              </a>
            </li>
            <li>
            <li>
            <li>
            <li>
              <a href="changepass.php" class="flex items-center mb-5 hover:text-gray-900 dark:hover:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2"> <!-- Adjusted size here -->
                  <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                </svg>
                <span class="text-sm font-medium">Change Password</span>
              </a>
            </li>
          
          
        </ul>
      </div>
      <button type="button" aria-expanded="false" class="flex items-center justify-center text-white bg-indigo-600 rounded-full w-16 h-16 hover:bg-indigo-700 focus:outline-none ml-auto" id="speed-dial-button">
        <svg class="w-5 h-5 transition-transform group-hover:rotate-45" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16" />
        </svg>
        <span class="sr-only">Open actions menu</span>
      </button>
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
    // Toggle speed dial menu visibility and rotate button
    $('#speed-dial-button').on('click', function() {
      $('#speed-dial-menu-dropdown').toggleClass('hidden');
      $(this).find('svg').toggleClass('rotate'); // Rotate the button
    });
  </script>
</html>
<?php
ob_end_flush();
?>