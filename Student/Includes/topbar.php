<?php 
error_reporting(0);

// Fetch student information
$query = "SELECT * FROM tblstudents WHERE Id = ".$_SESSION['userId']."";
$rs = $conn->query($query);
$num = $rs->num_rows;
$rows = $rs->fetch_assoc();
$fullName = $rows['firstName']." ".$rows['lastName'];

// Fetch the count of replied reports
$notificationQuery = "SELECT COUNT(*) as count FROM tblreports WHERE status = 'replied' AND admissionNumber = ?";
$notificationStmt = $conn->prepare($notificationQuery);
$notificationStmt->bind_param("s", $_SESSION['admissionNumber']); // Assuming admissionNumber is stored in session
$notificationStmt->execute();
$notificationResult = $notificationStmt->get_result();
$notificationRow = $notificationResult->fetch_assoc();
$repliedCount = $notificationRow['count'];
?>

<nav class="navbar navbar-expand navbar-light bg-gradient-primary topbar mb-4 static-top">
    <button id="sidebarToggleTop" class="btn btn-link rounded-circle mr-3">
      
    </button>
    <div class="text-white big" style="margin-left:100px;"></div>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="notificationDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell fa-fw"></i> <!-- Bell Icon -->
                <span class="badge badge-danger badge-counter"><?php echo $repliedCount; ?></span> <!-- Notification count -->
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="notificationDropdown">
                <h6 class="dropdown-header">
                    Notifications
                </h6>
                <div class="dropdown-divider"></div>
                <?php if ($repliedCount > 0): ?>
                    <a class="dropdown-item" href="#">
                        <i class="fas fa-exclamation-circle fa-sm fa-fw mr-2 text-gray-400"></i>
                        You have <?php echo $repliedCount; ?> new replies.
                    </a>
                <?php else: ?>
                    <a class="dropdown-item text-center" href="#">
                        No new notifications.
                    </a>
                <?php endif; ?>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item text-center" href="inbox.php">Show All Notifications</a>
            </div>
        </li>

        <div class="topbar-divider d-none d-sm-block"></div>
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <img class="img-profile rounded-circle" src="img/user-icn.png" style="max-width: 60px">
                <span class="ml-2 d-none d-lg-inline text-white small"><b>Welcome <?php echo $fullName;?></b></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="logout.php">
                    <i class="fas fa-power-off fa-fw mr-2 text-danger"></i>
                    Logout
                </a>
            </div>
        </li>
    </ul>
</nav>

<?php
// Close the notification statement
$notificationStmt->close();
?>