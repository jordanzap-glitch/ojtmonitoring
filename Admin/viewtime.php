<?php
error_reporting(0);
include '../Includes/session.php';
include '../Includes/dbcon.php';

// Initialize variables for editing
$editMode = false;
$editData = [];

// Check if the user is trying to edit an entry
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['Id'])) {
    $editId = $_GET['Id'];
    $editQuery = "SELECT * FROM tbl_weekly_time_entries WHERE id = '$editId'";
    $editResult = mysqli_query($conn, $editQuery);
    if ($editResult) {
        $editData = mysqli_fetch_assoc($editResult);
        $editMode = true; // Set edit mode to true
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['Id'])) {
    $deleteId = $_GET['Id'];
    $deleteQuery = "DELETE FROM tbl_weekly_time_entries WHERE id = '$deleteId'";
    if (mysqli_query($conn, $deleteQuery)) {
        $statusMsg = "<div class='alert alert-success'>Record deleted successfully!</div>";
    } else {
        $statusMsg = "<div class='alert alert-danger'>Error deleting record!</div>";
    }
}

if (isset($_POST['submit_time'])) {
    // Get the input values
    $admissionNumber = $_POST['admissionNumber']; // Get the admission number
    $studentFullname = $_POST['student_fullname'];
    $course = $_POST['course'];
    $weekStartDate = $_POST['week_start_date'];
    $comp_name = $_POST['comp_name'];
    $comp_link = $_POST['comp_link'];
    $sessionId = $_POST['sessionId']; // Get the session ID

    // Validate that the week start date is a Monday
    $date = new DateTime($weekStartDate);
    if ($date->format('N') != 1) { // 1 means Monday
        $statusMsg = "<div class='alert alert-danger'>The selected date must be a Monday.</div>";
    } else {
        // Proceed with the rest of the code
        $mondayTime = floatval($_POST['monday_time']);
        $tuesdayTime = floatval($_POST['tuesday_time']);
        $wednesdayTime = floatval($_POST['wednesday_time']);
        $thursdayTime = floatval($_POST['thursday_time']);
        $fridayTime = floatval($_POST['friday_time']);
        $saturdayTime = floatval($_POST['saturday_time']); // New Saturday time

        // Calculate total time submitted
        $totalTimeSubmitted = $mondayTime + $tuesdayTime + $wednesdayTime + $thursdayTime + $fridayTime + $saturdayTime;

        // Handle file upload
        $uploadDir = '../uploads/'; // Directory to save uploaded files
        $uploadFile = $uploadDir . basename($_FILES['photo']['name']);
        $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
        $uploadOk = 1;

        // Check if image file is a actual image or fake image
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] != UPLOAD_ERR_NO_FILE) {
            $check = getimagesize($_FILES['photo']['tmp_name']);
            if ($check === false) {
                $statusMsg = "<div class='alert alert-danger'>File is not an image.</div>";
                $uploadOk = 0;
            }

            // Check file size (limit to 2MB)
            if ($_FILES['photo']['size'] > 2000000) {
                $statusMsg = "<div class='alert alert-danger'>Sorry, your file is too large. Maximum size is 2MB.</div>";
                $uploadOk = 0;
            }

            // Allow certain file formats
            if ($imageFileType != "jpg" && $imageFileType != "png") {
                $statusMsg = "<div class='alert alert-danger'>Sorry, only JPG and PNG files are allowed.</div>";
                $uploadOk = 0;
            }

            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                $statusMsg .= "<div class='alert alert-danger'>Your file was not uploaded.</div>";
            } else {
                // If everything is ok, try to upload file
                if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadFile)) {
                    $statusMsg = "<div class='alert alert-success'>The file ". htmlspecialchars(basename($_FILES['photo']['name'])). " has been uploaded.</div>";
                } else {
                    $statusMsg = "<div class='alert alert-danger'>Sorry, there was an error uploading your file.</div>";
                }
            }
        }

        // Prepare the update query
        $updateQuery = "UPDATE tbl_weekly_time_entries SET week_start_date = '$weekStartDate', monday_time = '$mondayTime', tuesday_time = '$tuesdayTime', wednesday_time = '$wednesdayTime', thursday_time = '$thursdayTime', friday_time = '$fridayTime', saturday_time = '$saturdayTime', sessionId = '$sessionId'";

        // If a new photo was uploaded, include it in the update query
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] != UPLOAD_ERR_NO_FILE) {
            $updateQuery .= ", photo = '$uploadFile'";
        }

        // Complete the update query with the WHERE clause
        $updateQuery .= " WHERE id = '$editId'";

        // Execute the update query
        if (mysqli_query($conn, $updateQuery)) {
            $statusMsg = "<div class='alert alert-success'>Weekly time updated successfully!</div>";
            header("Location: viewtime.php");
            exit; // Ensure no further code is executed after redirection
        } else {
            $statusMsg = "<div class='alert alert-danger'>Error updating weekly time!</div>";
        }
    }
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
  <?php include 'Includes/title.php'; ?>
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/ruang-admin.min.css" rel="stylesheet">

  <script>
    document.addEventListener('DOMContentLoaded', function() {
        const weekStartDateInput = document.querySelector('input[name="week_start_date"]');
        
        // Function to set the date to the previous Monday
        function setPreviousMonday() {
            const today = new Date();
            const previousMonday = new Date(today);
            const dayOfWeek = today.getDay();
            const daysSinceMonday = (dayOfWeek + 6) % 7; // Calculate days since last Monday
            previousMonday.setDate(today.getDate() - daysSinceMonday);
            weekStartDateInput.value = previousMonday.toISOString().split('T')[0]; // Set the input value to previous Monday
        }

        // Set the previous Monday on page load
        setPreviousMonday();
    });
</script>
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
            <h1 class="h3 mb-0 text-gray-800">Weekly Time Submission</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Weekly Time Submission</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary"><?php echo $editMode ? 'Edit Weekly Time' : 'Submit Weekly Time'; ?></h6>
                  <?php echo isset($statusMsg) ? $statusMsg : ''; ?>
                </div>
                <div class="card-body">
                <form method="post" enctype="multipart/form-data">
                <div class="form-group row mb-3">
                <div class="col-xl-6">
                            <label class="form-control-label">Student ID (Double Check Your Student ID)<span class="text-danger ml-2">*</span></label>
                            <input type="text" class="form-control" name="admissionNumber" value="<?php echo $editMode ? htmlspecialchars($editData['admissionNumber']) : ''; ?>" required readonly>
                        </div>
                    </div>
                <div class="form-group row mb-3">
              
                    <div class="col-xl-6">
                        <label class="form-control-label">Student Full Name<span class="text-danger ml-2">*</span></label>
                        <input type="text" class="form-control" name="student_fullname" value="<?php echo $editMode ? htmlspecialchars($editData['student_fullname']) : ''; ?>" required readonly>
                    </div>
                    <div class="col-xl-6">
                        <label class="form-control-label">Company<span class="text-danger ml-2">*</span></label>
                        <input type="text" class="form-control" name="comp_name" value="<?php echo $editMode ? htmlspecialchars($editData['comp_name']) : ''; ?>" required readonly>
                    </div>
                </div>
                <div class="form-group row mb-3">
                    <div class="col-xl-6">
                      <label class="form-control-label">Section<span class="text-danger ml-2">*</span></label>
                      <input type="text" class="form-control" name="course" value="<?php echo $editMode ? htmlspecialchars($editData['course']) : ''; ?>" required readonly>
                  </div>
                    <div class="col-xl-6">
                    <label class="form-control-label">Company Link (Website link or Facebook Link)<span class="text-danger ml-2">*</span></label>
                            <input type="text" class="form-control" name="comp_link" id="comp_link" value="<?php echo $editMode ? $editData['comp_link'] : ''; ?>" required>
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <div class="col-xl-6">
                            <label class="form-control-label">Week Start Date (Select Monday)<span class="text-danger ml-2">*</span></label>
                            <input type="date" class="form-control" name="week_start_date" value="<?php echo $editMode ? $editData['week_start_date'] : ''; ?>" required>
                            <small class="form-text text-muted">Please select a Monday as the start date.</small>
                        </div>
                        <div class="col-xl-6">
                            <label class="form-control-label">Monday Time (in hours)<span class="text-danger ml-2">*</span></label>
                            <input type="number" class="form-control" name="monday_time" min="0" max="8" step="0.1" value="<?php echo $editMode ? $editData['monday_time'] : ''; ?>" required>
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <div class="col-xl-6">
                            <label class="form-control-label">Tuesday Time (in hours)<span class="text-danger ml-2">*</span></label>
                            <input type="number" class="form-control" name="tuesday_time" min="0" max="8" step="0.1" value="<?php echo $editMode ? $editData['tuesday_time'] : ''; ?>" required>
                        </div>
                        <div class="col-xl-6">
                            <label class="form-control-label">Wednesday Time (in hours)<span class="text-danger ml-2">*</span></label>
                            <input type="number" class="form-control" name="wednesday_time" min="0" max="8" step="0.1" value="<?php echo $editMode ? $editData['wednesday_time'] : ''; ?>" required>
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <div class="col-xl-6">
                            <label class="form-control-label">Thursday Time (in hours)<span class="text-danger ml-2">*</span></label>
                            <input type="number" class="form-control" name="thursday_time" min="0" max="8" step="0.1" value="<?php echo $editMode ? $editData['thursday_time'] : ''; ?>" required>
                        </div>
                        <div class="col-xl-6">
                            <label class="form-control-label">Friday Time (in hours)<span class="text-danger ml-2">*</span></label>
                            <input type="number" class="form-control" name="friday_time" min="0" max="8" step="0.1" value="<?php echo $editMode ? $editData['friday_time'] : ''; ?>" required>
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <div class="col-xl-6">
                            <label class="form-control-label">Saturday Time (in hours)<span class="text-danger ml-2">*</span></label>
                            <input type="number" class="form-control" name="saturday_time" min="0" max="8" step="0.1" value="<?php echo $editMode ? $editData['saturday_time'] : ''; ?>" required>
                        </div>
                        <div class="col-xl-6">
                            <label class="form-control-label">Upload Photo (DTR) (JPEG or PNG)<span class="text-danger ml-2">*</span></label>
                            <input type="file" class="form-control" name="photo" accept=".jpg, .jpeg, .png">
                            <small class="form-text text-muted">Maximum file size: 2MB. Leave blank if not changing.</small>
                        </div>
                    </div>
                    <button type="submit" name="submit_time" class="btn btn-primary"><?php echo $editMode ? 'Update Time' : 'Update Time '; ?></button>
                </form>
                </div>
              </div>

              <!-- Display Submitted Weekly Time -->
              <div class="row">
                <div class="col-lg-12">
                  <div class="card mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                      <h6 class="m-0 font-weight-bold text-primary">Submitted Weekly Time</h6>
                    </div>
                    <div class="table-responsive p-3">
                      <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                        <thead class="thead-light">
                          <tr>
                            <th>#</th>
                            <th>Week Start Date</th>
                            <th>Student Full Name</th>
                            <th>Course</th>
                            <th>Company</th>
                            <th>Company Link</th>
                            <th>Monday</th>
                            <th>Tuesday</th>
                            <th>Wednesday</th>
                            <th>Thursday</th>
                            <th>Friday</th>
                            <th>Saturday</th>
                            <th>Total Hours</th>
                            <th>Remaining Time</th>
                            <th>Session</th>
                            <th>Status</th>
                            <th>Photo</th>
                            <th>Edit</th>
                            <th>Delete</th>
                          </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Updated query to join with tblsessionterm
                            $query = "SELECT w.*, s.sessionName FROM tbl_weekly_time_entries w JOIN tblsessionterm s ON w.sessionId = s.id";
                            $rs = $conn->query($query);
                            $num = $rs->num_rows;
                            $sn = 0;
                            if ($num > 0) {
                                while ($rows = $rs->fetch_assoc()) {
                                    $sn++;
                                    $totalHours = $rows['monday_time'] + $rows['tuesday_time'] + $rows['wednesday_time'] + $rows['thursday_time'] + $rows['friday_time'] + $rows['saturday_time']; // Include Saturday time
                                    $remainingTime = $rows['remaining_time'];
                                    $status = $rows['status'];
                                    echo "
                                    <tr>
                                        <td>".$sn."</td>
                                        <td>".$rows['week_start_date']."</td>
                                        <td>".$rows['student_fullname']."</td>
                                        <td>".$rows['course']."</td>
                                        <td>".$rows['comp_name']."</td>
                                        <td>".$rows['comp_link']."</td>
                                        <td>".$rows['monday_time']."</td>
                                        <td>".$rows['tuesday_time']."</td>
                                        <td>".$rows['wednesday_time']."</td>
                                        <td>".$rows['thursday_time']."</td>
                                        <td>".$rows['friday_time']."</td>
                                        <td>".$rows['saturday_time']."</td> <!-- Display Saturday time -->
                                        <td>".$totalHours."</td>
                                        <td>".$remainingTime."</td>
                                        <td>".$rows['sessionName']."</td> <!-- Display sessionName -->
                                        <td>".$status."</td>
                                        <td><a href='".$rows['photo']."' target='_blank'><img src='".$rows['photo']."' alt='Uploaded Photo' style='width: 50px; height: auto;'></a></td>
                                        <td><a href='?action=edit&Id=".$rows['id']."'><i class='fas fa-fw fa-edit'></i>Edit</a></td>
                                       <td><a href='?action=delete&Id=".$rows['id']."'><i class='fas fa-fw fa-trash'></i>Delete</a></td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='15' class='text-center'>No Record Found!</td></tr>";
                            }
                            ?>
                        </tbody>
                      </table>
                    </div>
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
       <?php include "Includes/footer.php";?>
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
   <!-- Page level plugins -->
  <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>

  <!-- Page level custom scripts -->
  <script>
    $(document).ready(function () {
      $('#dataTableHover').DataTable(); // ID From dataTable with Hover
    });
  </script>
</body>
</html>