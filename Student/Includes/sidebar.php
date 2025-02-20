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
            <span>DAILY TIME RECORD</span>
        </a>
        <div id="collapseBootstrapcon4" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <script>
                    // Get the current day of the week (0 = Sunday, 1 = Monday, ..., 5 = Friday, 6 = Saturday)
                    var today = new Date();
                    var dayOfWeek = today.getDay();

                    // Check if today is Friday (5)
                    if (dayOfWeek === 4) {
                        document.write('<a class="collapse-item" href="time.php">Submit Time</a>');
                    } else {
                        document.write('<p class="collapse-item disabled">Submit Time (Sunday)</p>');
                    }
                </script>
                <a class="collapse-item" href="ViewTask.php">Task List</a>
                <a class="collapse-item" href="ViewTaskHistory.php">Submission History</a>
            </div>
        </div>
    </li>
</ul>

      
        
      </li>


     


     
      <!-- <li class="nav-item">
        <a class="nav-link" href="forms.html">
          <i class="fab fa-fw fa-wpforms"></i>
          <span>Forms</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTable" aria-expanded="true"
          aria-controls="collapseTable">
          <i class="fas fa-fw fa-table"></i>
          <span>Tables</span>
        </a>
        <div id="collapseTable" class="collapse" aria-labelledby="headingTable" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Tables</h6>
            <a class="collapse-item" href="simple-tables.html">Simple Tables</a>
            <a class="collapse-item" href="datatables.html">DataTables</a>
          </div>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="ui-colors.html">
          <i class="fas fa-fw fa-palette"></i>
          <span>UI Colors</span>
        </a>
      </li>
      <hr class="sidebar-divider">
      <div class="sidebar-heading">
        Examples
      </div>
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePage" aria-expanded="true"
          aria-controls="collapsePage">
          <i class="fas fa-fw fa-columns"></i>
          <span>Pages</span>
        </a>
        <div id="collapsePage" class="collapse" aria-labelledby="headingPage" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Example Pages</h6>
            <a class="collapse-item" href="login.html">Login</a>
            <a class="collapse-item" href="register.html">Register</a>
            <a class="collapse-item" href="404.html">404 Page</a>
            <a class="collapse-item" href="blank.html">Blank Page</a>
          </div>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="charts.html">
          <i class="fas fa-fw fa-chart-area"></i>
          <span>Charts</span>
        </a>
      </li> -->
      <hr class="sidebar-divider">
     
    </ul>