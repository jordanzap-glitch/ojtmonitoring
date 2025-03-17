<?php 
error_reporting(0);
session_start(); // Ensure session is started

// Include database connection
include '../Includes/dbcon.php'; 

// Fetch admin details
$query = "SELECT * FROM tbladmin WHERE Id = ".$_SESSION['userId']."";
$rs = $conn->query($query);
$num = $rs->num_rows;
$rows = $rs->fetch_assoc();
$fullName = $rows['firstName']." ".$rows['lastName'];

// Fetch total reports count with status 'pending'
$queryReports = "SELECT COUNT(*) as totalPendingReports FROM tblreports WHERE status = 'pending'";
$resultReports = $conn->query($queryReports);
$rowReports = $resultReports->fetch_assoc();
$totalPendingReportsCount = $rowReports['totalPendingReports'];

// Fetch latest reports data
$queryFetchReports = "SELECT * FROM tblreports WHERE status = 'pending' ORDER BY created_at DESC LIMIT 5"; // Fetch the latest 5 pending reports
$resultFetchReports = $conn->query($queryFetchReports);
?>

<nav class="navbar navbar-expand navbar-light bg-gradient-primary topbar mb-4 static-top">
    <button id="sidebarToggleTop" class="btn btn-link rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>
    <div class="text-white big" style="margin-left:100px;"><b></b></div>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                aria-labelledby="searchDropdown">
                <form class="navbar-search">
                    <div class="input-group">
                        <input type="text" class="form-control bg-light border-1 small" placeholder="What do you want to look for?"
                            aria-label="Search" aria-describedby="basic-addon2" style="border-color:rgb(145, 41, 127);">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button">
                                <i class="fas fa-search fa-sm"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </li>

        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Bell Icon for Pending Reports -->
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" id="reportsDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell fa-fw"></i>
                <!-- Counter for pending reports -->
                <span class="badge badge-danger badge-counter"><?php echo $totalPendingReportsCount; ?></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="reportsDropdown">
                <h6 class="dropdown-header">Pending Reports Notifications:</h6>
                <div class="dropdown-divider"></div>
                <?php if ($resultFetchReports->num_rows > 0): ?>
                    <?php while ($report = $resultFetchReports->fetch_assoc()): ?>
                        <a class="dropdown-item" href="inbox.php?reportId=<?php echo htmlspecialchars($report['admissionNumber']); ?>">
                            <strong><?php echo htmlspecialchars($report['fullname']); ?></strong>: <?php echo htmlspecialchars($report['report']); ?>
                        </a>
                        <div class="dropdown-divider"></div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <a class="dropdown-item" href="#">No new pending reports</a>
                <?php endif; ?>
                <a class="dropdown-item" href="reports.php">View All Reports</a>
            </div>
        </li>

        <div class="topbar-divider d-none d-sm-block"></div>
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <img class="img-profile rounded-circle" src="img/user-icn.png" style="max-width: 60px">
                <span class="ml-2 d-none d-lg-inline text-white small"><b>Welcome <?php echo htmlspecialchars($fullName); ?></b></span>
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