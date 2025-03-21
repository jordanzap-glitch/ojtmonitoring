<?php
ob_start();
error_reporting(0);
include '../Includes/session.php';
include '../Includes/dbcon.php';

$statusMsg = "";

// Check if the form is submitted
if (isset($_POST['submit'])) {
    $admin_id = $_SESSION['userId']; // Assuming admin_id is stored in session
    $firstName = $_SESSION['firstName']; // Assuming firstName is stored in session
    $lastName = $_SESSION['lastName']; // Assuming lastName is stored in session
    $name = $firstName . ' ' . $lastName; // Combine first and last name
    $content = $_POST['content'];
    $date_created = date('Y-m-d H:i:s'); // Current date and time

    // Handle file upload
    $targetDir = "../Student/uploads/"; // Directory where images will be uploaded
    $imagePath = ""; // Initialize image path
    $uploadOk = 1;

    // Check if an image is uploaded
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] != UPLOAD_ERR_NO_FILE) {
        $targetFile = $targetDir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check === false) {
            $statusMsg = "<div class='alert alert-danger'>File is not an image.</div>";
            $uploadOk = 0;
        }

        // Check file size (limit to 2MB)
        if ($_FILES["image"]["size"] > 2000000) {
            $statusMsg = "<div class='alert alert-danger'>Sorry, your file is too large. Maximum size is 2MB.</div>";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
            $statusMsg = "<div class='alert alert-danger'>Sorry, only JPG, JPEG, PNG & GIF files are allowed.</div>";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            $statusMsg .= "<div class='alert alert-danger'>Your file was not uploaded.</div>";
        } else {
            // If everything is ok, try to upload file
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                $imagePath = basename($_FILES["image"]["name"]); // Set image path if upload is successful
            } else {
                $statusMsg .= "<div class='alert alert-danger'>Sorry, there was an error uploading your file.</div>";
            }
        }
    }

    // Insert the announcement into the database
    $query = "INSERT INTO tblannouncement (admin_id, adminName, content, date_created, image_path, is_active) VALUES ('$admin_id', '$name', '$content', '$date_created', '$imagePath', 1)";
    if (mysqli_query($conn, $query)) {
        // Deactivate all other announcements
        $updateQuery = "UPDATE tblannouncement SET is_active = 0 WHERE admin_id = '$admin_id' AND id != LAST_INSERT_ID()";
        mysqli_query($conn, $updateQuery);
        
        $statusMsg = "<div class='alert alert-success'>Announcement created successfully!</div>";
    } else {
        $statusMsg = "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
    }
}

// Fetch announcements for the logged-in admin
$admin_id = $_SESSION['userId'];
$announcementQuery = "SELECT * FROM tblannouncement WHERE admin_id = '$admin_id' ORDER BY date_created DESC";
$announcementResult = $conn->query($announcementQuery);

// Handle activation/deactivation
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $action = $_GET['action'];

    if ($action == 'activate') {
        // Deactivate all other announcements
        $updateQuery = "UPDATE tblannouncement SET is_active = 0 WHERE admin_id = '$admin_id'";
        mysqli_query($conn, $updateQuery);

        // Activate the selected announcement
        $updateQuery = "UPDATE tblannouncement SET is_active = 1 WHERE id = '$id'";
        mysqli_query($conn, $updateQuery);
    } elseif ($action == 'deactivate') {
        $updateQuery = "UPDATE tblannouncement SET is_active = 0 WHERE id = '$id'";
        mysqli_query($conn, $updateQuery);
    }

    // Redirect to avoid resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Handle delete action
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $deleteQuery = "DELETE FROM tblannouncement WHERE id = '$delete_id'";
    if (mysqli_query($conn, $deleteQuery)) {
        $statusMsg = "<div class='alert alert-danger'>Announcement deleted successfully!</div>";
    } else {
        $statusMsg = "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
    }

    // Redirect to avoid resubmission
    header("Location: " . $_SERVER['PHP_SELF'] . "?statusMsg=" . urlencode($statusMsg));
    exit();
}

// Check for status message in the URL
if (isset($_GET['statusMsg'])) {
    $statusMsg = $_GET['statusMsg'];
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
    <title>Create Announcement</title>
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
                        <h1 class="h3 mb-0 text-gray-800">Create Announcement</h1>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="./">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Create Announcement</li>
                        </ol>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card mb-4">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Announcement Form</h6>
                                </div>
                                <div class="card-body">
                                    <?php echo $statusMsg; ?>
                                    <form method="post" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label for="content">Content<span class="text-danger ml-2">*</span></label>
                                            <textarea class="form-control" name="content" id="content" rows="5" required placeholder="Enter announcement content"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="image">Upload Image<span class="text-danger ml-2"></span></label>
                                            <input type="file" class="form-control" name="image" id="image">
                                            <small class="form-text text-muted">Max size: 2MB. Allowed formats: JPG, JPEG, PNG, GIF. (Optional)</small>
                                        </div>
                                        <button type="submit" name="submit" class="btn btn-primary">Create Announcement</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Row-->

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card mb-4">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Your Announcements</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>Admin ID</th>
                                                    <th>Admin Name</th>
                                                    <th>Content</th>
                                                    <th>Image</th>
                                                    <th>Date Created</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                    <th>Delete</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if ($announcementResult->num_rows > 0): ?>
                                                    <?php while ($row = $announcementResult->fetch_assoc()): ?>
                                                        <tr>
                                                            <td><?php echo htmlspecialchars($row['admin_id']); ?></td>
                                                            <td><?php echo htmlspecialchars($row['adminName']); ?></td>
                                                            <td><?php echo htmlspecialchars($row['content']); ?></td>
                                                            <td>
                                                                <?php if (!empty($row['image_path'])): ?>
                                                                    <img src="../Student/uploads/<?php echo htmlspecialchars($row['image_path']); ?>" alt="Announcement Image" style="width: 100px; height: auto;">
                                                                <?php else: ?>
                                                                    No Image
                                                                <?php endif; ?>
                                                            </td>
                                                            <td><?php echo date('F j, Y, g:i a', strtotime($row['date_created'])); ?></td>
                                                            <td>
                                                                <?php echo $row['is_active'] ? 'Active' : 'Inactive'; ?>
                                                            </td>
                                                            <td>
                                                                <?php if ($row['is_active']): ?>
                                                                    <a href="?action=deactivate&id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">Deactivate</a>
                                                                <?php else: ?>
                                                                    <a href="?action=activate&id=<?php echo $row['id']; ?>" class="btn btn-success btn-sm">Activate</a>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td><a href="?delete_id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">Delete</a></td>
                                                        </tr>
                                                    <?php endwhile; ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="8" class="text-center">No announcements found.</td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
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
</body>

</html>
<?php
ob_end_flush();
?>