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
/* Fixed position for speed dial button */
    .fixed-speed-dial {
      position: fixed;
      right: 20px; /* Distance from the right */
      bottom: 20px; /* Distance from the bottom */
      z-index: 10; /* Ensure it stays above other elements */
    }
  </style>
</head>

<body id="page-top" class="bg-gray-100">
  <div id="wrapper">
    <!-- Sidebar -->
    
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
<br><br>
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
  <?php include "Includes/speeddial.php"; ?>  
</html>
<?php
ob_end_flush();
?>