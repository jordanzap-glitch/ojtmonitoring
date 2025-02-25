<?php
include '../Includes/session.php';
include '../Includes/dbcon.php';

// Assuming you have validated the user and fetched their details
// Store admission number in session

if (isset($_SESSION['email'])) {
    // Fetch the admission number, full name, company name, and company link from the database
    $username = $_SESSION['email']; // Assuming username is stored in session
    $query = "SELECT admissionNumber, firstName, lastName, comp_name, comp_link, classId FROM tblstudents WHERE email = '$username'";
    $result = mysqli_query($conn, $query);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['admissionNumber'] = $row['admissionNumber']; // Store admission number in session
        
        // Concatenate names to form the full name
        $fullName = trim($row['firstName'] . ' ' . $row['lastName']);
        $_SESSION['student_fullname'] = $fullName; // Store full name in session

        // Store company name and link in session
        $_SESSION['comp_name'] = $row['comp_name']; // Store company name in session
        $_SESSION['comp_link'] = $row['comp_link']; // Store company link in session

        $_SESSION['classId'] = $row['classId']; // Store classId in session
    }
}

if (isset($_SESSION['classId'])) {
    $classId = $_SESSION['classId'];
    $classQuery = "SELECT className FROM tblclass WHERE Id = '$classId'";
    $classResult = mysqli_query($conn, $classQuery);
    if ($classResult) {
        $classRow = mysqli_fetch_assoc($classResult);
        $_SESSION['className'] = $classRow['className']; // Store class name in session
    }
}

// Reset status to 'not submitted' every Sunday
if (date('N') == 7) { // 7 means Sunday
    mysqli_query($conn, "UPDATE tbl_weekly_time_entries SET status = 'not submitted'");
}

// Check if the last submission date is not today, reset the submission status
if (isset($_SESSION['last_submission_date']) && $_SESSION['last_submission_date'] !== date('Y-m-d')) {
    $_SESSION['form_submitted'] = false; // Reset the submission status
}

if (isset($_POST['submit_time'])) {
  // Get the input values
  $admissionNumber = $_POST['admissionNumber']; // Get the admission number
  $studentFullname = $_POST['student_fullname'];
  $course = $_POST['course'];
  $weekStartDate = $_POST['week_start_date'];
  $comp_name = $_POST['comp_name'];
  $comp_link = $_POST['comp_link'];

  // Check if today is Sunday
  if (date('N') != 2) { // 7 means Sunday
      $statusMsg = "<div class='alert alert-danger'>The form can only be submitted on Sundays.</div>";
  } else {
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
          $saturdayTime = floatval($_POST['saturday_time']);

          // Calculate total time submitted
          $totalTimeSubmitted = $mondayTime + $tuesdayTime + $wednesdayTime + $thursdayTime + $fridayTime + $saturdayTime;

          // Handle file upload
          $uploadDir = '../uploads/'; // Directory to save uploaded files
          $uploadFile = $uploadDir . basename($_FILES['photo']['name']);
          $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
          $uploadOk = 1;

          // Check if image file is a actual image or fake image
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

          // Check if an entry for the week already exists
          $checkEntryQuery = "SELECT * FROM tbl_weekly_time_entries WHERE week_start_date = '$weekStartDate' AND admissionNumber = '$admissionNumber'";
          $checkEntryResult = mysqli_query($conn, $checkEntryQuery);

          if (mysqli_num_rows($checkEntryResult) > 0) {
              // Entry exists, update it
              $updateQuery = "UPDATE tbl_weekly_time_entries SET 
                  monday_time = '$mondayTime', 
                  tuesday_time = '$tuesdayTime', 
                  wednesday_time = '$wednesdayTime', 
                  thursday_time = '$thursdayTime', 
                  friday_time = '$fridayTime', 
                  saturday_time = '$saturdayTime', 
                  comp_name = '$comp_name', 
                  comp_link = '$comp_link', 
                  photo = '$uploadFile', 
                  status = 'pending' 
                  WHERE week_start_date = '$weekStartDate' AND admissionNumber = '$admissionNumber'";

              if (mysqli_query($conn, $updateQuery)) {
                  $statusMsg = "<div class='alert alert-success'>Weekly time updated successfully! Your submission is pending approval.</div>";
              } else {
                  $statusMsg = "<div class='alert alert-danger'>Error updating weekly time!</div>";
              }
          } else {
              // Insert a new record with status 'pending'
              $insertQuery = mysqli_query($conn, "INSERT INTO tbl_weekly_time_entries (week_start_date, monday_time, tuesday_time, wednesday_time, thursday_time, friday_time, saturday_time, admissionNumber, student_fullname, course, comp_name, comp_link, remaining_time, photo, status) 
                  VALUES ('$weekStartDate', '$mondayTime', '$tuesdayTime', '$wednesdayTime', '$thursdayTime', '$fridayTime', '$saturdayTime', '$admissionNumber', '$studentFullname', '$course', '$comp_name', '$comp_link', 500, '$uploadFile', 'pending')");

              if ($insertQuery) {
                  $statusMsg = "<div class='alert alert-success'>Weekly time submitted successfully! Your submission is pending approval.</div>";
              } else {
                  $statusMsg = "<div class='alert alert-danger'>Error submitting weekly time!</div>";
              }
          }

          $_SESSION['form_submitted'] = true; // Mark the form as submitted
          $_SESSION['last_submission_date'] = date('Y-m-d'); // Store today's date
          $_SESSION['week_start_date'] = $weekStartDate; // Store the week start date
          header("Location: index.php");
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
       <?php include "Includes/topbar.php";?> <!-- Topbar -->

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
                  <h6 class="m-0 font-weight-bold text-primary">Submit Weekly Time</h6>
                  <?php echo $statusMsg; ?>
                </div>
                <div class="card-body">
                <form method="post" enctype="multipart/form-data">
                <div class="form-group row mb-3">
                        <div class ="col-xl-6">
                            <label class="form-control-label">Student ID (Double Check Your Student ID)<span class="text-danger ml-2">*</span></label>
                            <input type="text" class="form-control" name="admissionNumber" value="<?php echo isset($_SESSION['admissionNumber']) ? $_SESSION['admissionNumber'] : ''; ?>" required readonly>
                        </div>
                        <div class="col-xl-6">
                          <label class="form-control-label">Company<span class="text-danger ml-2">*</span></label>
                          <input type="text" class="form-control" name="comp_name" value="<?php echo isset($_SESSION['comp_name']) ? $_SESSION['comp_name'] : ''; ?>" required readonly>
                      </div>
                    </div>
                    <div class="form-group row mb-3">
                    <div class="col-xl-6">
                        <label class="form-control-label">Student Full Name<span class="text-danger ml-2">*</span></label>
                        <input type="text" class="form-control" name="student_fullname" value="<?php echo isset($_SESSION['student_fullname']) ? $_SESSION['student_fullname'] : ''; ?>" required readonly>
                    </div>
                    <div class="col-xl-6">
                      <label class="form-control-label">Section<span class="text-danger ml-2">*</span></label>
                      <input type="text" class="form-control" name="course" value="<?php echo isset($_SESSION['className']) ? $_SESSION['className'] : ''; ?>" required readonly>
                  </div>
                    </div>
                    <div class="form-group row mb-3">
                    <div class="col-xl-6">
                        <label class="form-control-label">Company Link (Website link or Facebook Link)<span class="text-danger ml-2">*</span></label>
                        <input type="text" class="form-control" name="comp_link" id="comp_link" value="<?php echo isset($_SESSION['comp_link']) ? $_SESSION['comp_link'] : ''; ?>" required readonly>
                    </div>
                        <div class="col-xl-6">
                            <label class="form-control-label">Week Start Date (Select Monday)<span class="text-danger ml-2">*</span></label>
                            <input type="date" class="form-control" name="week_start_date" required>
                            <small class="form-text text-muted">Please select a Monday as the start date.</small>
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <div class="col-xl-6">
                            <label class="form-control-label">Monday Time (in hours)<span class="text-danger ml-2">*</span></label>
                            <input type="number" class="form-control" name="monday_time" min="0" max="8" step="0.1" required>
                        </div>
                        <div class="col-xl-6">
                            <label class="form-control-label">Tuesday Time (in hours)<span class="text-danger ml-2">*</span></label>
                            <input type="number" class="form-control" name="tuesday_time" min="0" max="8" step="0.1" required>
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <div class="col-xl-6">
                            <label class="form-control-label">Wednesday Time (in hours)<span class="text-danger ml-2">*</span></label>
                            <input type="number" class="form-control" name="wednesday_time" min="0" max="8" step="0.1" required>
                        </div>
                        <div class="col-xl-6">
                            <label class="form-control-label">Thursday Time (in hours)<span class="text-danger ml-2">*</span></label>
                            <input type="number" class="form-control" name="thursday_time" min="0" max="8" step="0.1" required>
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <div class="col-xl-6">
                            <label class="form-control-label">Friday Time (in hours)<span class="text-danger ml-2">*</span></label>
                            <input type="number" class="form-control" name="friday_time" min="0" max="8" step="0.1" required>
                        </div>
                        <div class="col-xl-6">
                            <label class="form-control-label">Saturday Time (in hours)<span class="text-danger ml-2">*</span></label>
                            <input type="number" class="form-control" name="saturday_time" min="0" max="8" step="0.1" placeholder="Put Zero(0) if only Monday to Friday" required>
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <div class="col-xl-12">
                            <label class="form-control-label">Upload Photo (DTR) (JPEG or PNG)<span class="text-danger ml-2">*</span></label>
                            <input type="file" class="form-control" name="photo" accept=".jpg, .jpeg, .png" required>
                            <small class="form-text text-muted">Maximum file size: 2MB.</small>
                        </div>
                    </div>
                    <button type="submit" name="submit_time" class="btn btn-primary">Submit Time</button>
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
                            <th>Photo</th>
                            <th>Edit</th>
                            <th>Delete</th>
                          </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Fetch all entries
                            $query = "SELECT * FROM tbl_weekly_time_entries";
                            $rs = $conn->query($query);
                            $num = $rs->num_rows;
                            $sn = 0;
                            if ($num > 0) {
                                while ($rows = $rs->fetch_assoc()) {
                                    $sn++;
                                    $totalHours = $rows['monday_time'] + $rows['tuesday_time'] + $rows['wednesday_time'] + $rows['thursday_time'] + $rows['friday_time'] + $rows['saturday_time'];
                                    $remainingTime = $rows['remaining_time'];
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
                                        <td>".$rows['saturday_time']."</td>
                                        <td>".$totalHours."</td>
                                        <td>".$remainingTime."</td>
                                        <td><a href='".$rows['photo']."' target='_blank'><img src='".$rows['photo']."' alt='Uploaded Photo' style='width: 50px; height: auto;'></a></td>
                                        <td><a href='?action=edit&Id=".$rows['id']."'><i class='fas fa-fw fa-edit'></i>Edit</a></td>
                                        <td><a href='?action=delete&Id=".$rows['id']."'><i class='fas fa-fw fa-trash'></i>Delete</a></td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='14' class='text-center'>No Record Found!</td></tr>";
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