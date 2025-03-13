<ul class="navbar-nav sidebar sidebar-light accordion" id="accordionSidebar">
    <a class="sidebar-brand d-flex align-items-center bg-gradient-primary justify-content-center" href="index.php">
        <div class="sidebar-brand-icon">
            <img src="img/logo/attnlg.jpg">
        </div>
        <div class="sidebar-brand-text mx-3">SRC OJT-MS</div>
    </a>
    <hr class="sidebar-divider my-0">
    <li class="nav-item active">
        <a class="nav-link" href="index.php">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <hr class="sidebar-divider">
    <div class="sidebar-heading">
        DTR
    </div>
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBootstrapcon4"
           aria-expanded="true" aria-controls="collapseBootstrapcon4">
            <i class="fa fa-calendar-alt"></i>
            <span>DAILY TIME RECORD/MESSAGE</span>
        </a>
        <div id="collapseBootstrapcon4" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <script>
                    // Assuming you have a way to check if the student has submitted their time
                    var hasSubmitted = <?php echo isset($_SESSION['form_submitted']) && $_SESSION['form_submitted'] === true ? 'true' : 'false'; ?>;

                    // Always show the "Submit Time" link
                    if (!hasSubmitted) {
                        document.write('<a class="collapse-item" href="time.php">Submit Time</a>');
                    } else {
                        document.write('<p class="collapse-item disabled">Submit Time (Already Submitted)</p>');
                    }

                    // Always show the "Compose Message" link
                    document.write('<a class="collapse-item" href="reports.php">Compose Message</a>');
                    
                    // Add the "Inbox" link
                    document.write('<a class="collapse-item" href="inbox.php">Inbox</a>');
                </script>
            </div>
        </div>
    </li>

    <hr class="sidebar-divider">
    <div class="sidebar-heading">
        Account Settings
    </div>
    <li class="nav-item">
        <a class="nav-link" href="changepass.php">
            <i class="fas fa-fw fa-lock"></i>
            <span>Change Password</span>
        </a>
    </li>
</ul>