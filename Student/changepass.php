<?php 
include '../Includes/session.php';
include '../Includes/dbcon.php';

$error_message = '';
$success_message = '';

// Check if the user is logged in and has an admission number
if (!isset($_SESSION['userId'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// Handle password change
if (isset($_POST['change_password'])) {
    $admissionNumber = $_SESSION['admissionNumber'];
    $email = $_SESSION['email']; // Assuming the email is stored in the session
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate the new password
    if ($new_password !== $confirm_password) {
        $error_message = "New password and confirm password do not match.";
    } else {
        // Check the current password in tblstudent
        $query_student = "SELECT * FROM tblstudents WHERE email = ? AND password = ?";
        $stmt_student = $conn->prepare($query_student);
        $stmt_student->bind_param("ss", $email, $current_password); // Assuming passwords are stored in plain text
        $stmt_student->execute();
        $result_student = $stmt_student->get_result();

        if ($result_student->num_rows > 0) {
            // Current password is correct, update the password in tblstudent
            $update_student_query = "UPDATE tblstudents SET password = ? WHERE email = ?";
            $update_student_stmt = $conn->prepare($update_student_query);
            $update_student_stmt->bind_param("ss", $new_password, $email);
            $update_student_stmt->execute();

            // Update the password in tbluser
            $update_user_query = "UPDATE tbluser SET password = ? WHERE emailAddress = ?";
            $update_user_stmt = $conn->prepare($update_user_query);
            $update_user_stmt->bind_param("ss", $new_password, $email);
            $update_user_stmt->execute();

            $success_message = "Password changed successfully.";
        } else {
            $error_message = "Current password is incorrect.";
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
  <link href="img/logo/attnlg.jpg" rel="icon">
  <title>Change Password</title>
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/ruang-admin.min.css" rel="stylesheet">
  <script>
    function togglePasswordVisibility() {
      var currentPasswordField = document.getElementById("current_password");
      var newPasswordField = document.getElementById("new_password");
      var confirmPasswordField = document.getElementById("confirm_password");
      var currentPasswordToggle = document.getElementById("current_password_toggle");
      var newPasswordToggle = document.getElementById("new_password_toggle");
      var confirmPasswordToggle = document.getElementById("confirm_password_toggle");

      currentPasswordField.type = currentPasswordToggle.checked ? "text" : "password";
      newPasswordField.type = newPasswordToggle.checked ? "text" : "password";
      confirmPasswordField.type = confirmPasswordToggle.checked ? "text" : "password";
    }
  </script>
</head>

<body id="page-top">
  <div id="wrapper">
    <!-- Sidebar -->
   
    <!-- Sidebar -->
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <!-- TopBar -->
        <?php include "Includes/topbar.php"; ?>
        <!-- Topbar -->

        <!-- Container Fluid-->
        <div class="container-fluid" id="container-wrapper">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Change Password</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Change Password</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Change Your Password</h6>
                </div>
                <div class="card-body">
                  <?php if (!empty($error_message)): ?>
                    <div class="alert alert-danger" role="alert">
                      <?php echo htmlspecialchars($error_message); ?>
                    </div>
                  <?php endif; ?>

                  <?php if (!empty($success_message)): ?>
                    <div class="alert alert-success" role="alert">
                      <?php echo htmlspecialchars($success_message); ?>
                    </div>
                  <?php endif; ?>

                  <form method="POST" action="">
                    <div class="form-group">
                      <label for="email">Email Address:</label>
                      <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="form-group">
                      <label for="current_password">Current Password:</label>
                      <input type="password" class="form-control" id="current_password" name="current_password" required>
                      <input type="checkbox" id="current_password_toggle" onclick="togglePasswordVisibility()"> Show Password
                    </div>
                    <div class="form-group">
                      <label for="new_password">New Password:</label>
                      <input type="password" class="form-control" id="new_password" name="new_password" required>
                      <input type="checkbox" id="new_password_toggle" onclick="togglePasswordVisibility()"> Show Password
                    </div>
                    <div class="form-group">
                      <label for="confirm_password">Confirm New Password:</label>
                      <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                      <input type="checkbox" id="confirm_password_toggle" onclick="togglePasswordVisibility()"> Show Password
                    </div>
                    <button type="submit" name="change_password" class="btn btn-primary">Change Password</button>
                  </form>
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
  <?php include "Includes/speeddial.php"; ?>  
  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="js/ruang-admin.min.js"></script>
</body>

</html>