<ul class="navbar-nav sidebar sidebar-light accordion" id="accordionSidebar">
    <a class="sidebar-brand d-flex align-items-center bg-gradient-primary justify-content-center" href="index.php">
        <div class="sidebar-brand-icon">
            <img src="img/logo/src-logo.jpg">
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
        Admin Management
    </div>
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAdmin"
           aria-expanded="true" aria-controls="collapseAdmin">
            <i class="fas fa-user-shield"></i>
            <span>Manage Admin</span>
        </a>
        <div id="collapseAdmin" class="collapse" aria-labelledby="headingAdmin" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Admin Actions</h6>
                <a class="collapse-item" href="createAdmin.php">Create Admin Account</a>
              
            </div>
        </div>
    </li>
    <hr class="sidebar-divider">
    <div class="sidebar-heading">
        Class Section(s)
    </div>
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBootstrap4"
           aria-expanded="true" aria-controls="collapseBootstrap4">
            <i class="fas fa-chalkboard"></i>
            <span>Manage Course</span>
        </a>
        <div id="collapseBootstrap4" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Manage Course</h6>
                <a class="collapse-item" href="createClass.php">Add New Course and Section</a>
            </div>
        </div>
    </li>

    <hr class="sidebar-divider">
    <div class="sidebar-heading">
        DTR(s)
    </div>
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBootstrapassests"
           aria-expanded="true" aria-controls="collapseBootstrapassests">
            <i class="fas fa-chalkboard-teacher"></i>
            <span>Weekly Time Entries</span>
        </a>
        <div id="collapseBootstrapassests" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Student Time</h6>
                <a class="collapse-item" href="approval.php">Approval</a>
                <a class="collapse-item" href="viewtime.php">Time Table</a>
                <!--<a class="collapse-item" href="dtrpictures.php">DTR Pictures</a> !-->
            </div>
        </div>
    </li>

    <hr class="sidebar-divider">
    <div class="sidebar-heading">
        Students
    </div>
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBootstrap2"
           aria-expanded="true" aria-controls="collapseBootstrap2">
            <i class="fas fa-user-graduate"></i>
            <span>Manage Students</span>
        </a>
        <div id="collapseBootstrap2" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Manage Students</h6>
                <a class="collapse-item" href="createStudents.php">Add/Manage Student</a>
            </div>
        </div>
    </li>

    <hr class="sidebar-divider">
    <div class="sidebar-heading">
        Company(s)
    </div>
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBootstrap3"
           aria-expanded="true" aria-controls="collapseBootstrap3">
            <i class="fas fa-building"></i>
            <span>Manage Company</span>
        </a>
        <div id="collapseBootstrap3" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Manage Company</h6>
                <a class="collapse-item" href="createCompany.php">Add New Company</a>
            </div>
        </div>
    </li>

    <hr class="sidebar-divider">
    <div class="sidebar-heading">
        Session & Term
    </div>
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBootstrapcon"
           aria-expanded="true" aria-controls="collapseBootstrapcon">
            <i class="fa fa-calendar-alt"></i>
            <span>Manage Session & Term</span>
        </a>
        <div id="collapseBootstrapcon" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Contribution</h6>
                <a class="collapse-item" href="createSessionTerm.php">Create Session and Term</a>
            </div>
        </div>
    </li>

    

    <hr class="sidebar-divider">
</ul>