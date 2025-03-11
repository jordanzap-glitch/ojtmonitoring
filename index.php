<?php
ob_start();
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'Includes/dbcon.php';
if (isset($_POST['login'])) {

    // Get the submitted username and password
    $username = $_POST['username'];
    $password = $_POST['password'];
    $password = ($password); // Assuming you're using md5 for password hashing

    // Check in Administrator table
    $query_admin = "SELECT * FROM tbladmin WHERE emailAddress = '$username' AND password = '$password'";
    $rs_admin = $conn->query($query_admin);
    $num_admin = $rs_admin->num_rows;
    $rows_admin = $rs_admin->fetch_assoc();

    if ($num_admin > 0) {
        // Admin user detected
        $_SESSION['userId'] = $rows_admin['Id'];
        $_SESSION['firstName'] = $rows_admin['firstName'];
        $_SESSION['lastName'] = $rows_admin['lastName'];
        $_SESSION['emailAddress'] = $rows_admin['emailAddress'];
        $_SESSION['user_type'] = 'Administrator'; // Set session user type

        // Redirect to admin dashboard
        header('Location:Admin/index.php');
        exit();
    }
    else {
        // Check in Class Teacher table
        $query_teacher = "SELECT * FROM tblclassteacher WHERE emailAddress = '$username' AND password = '$password'";
        $rs_teacher = $conn->query($query_teacher);
        $num_teacher = $rs_teacher->num_rows;
        $rows_teacher = $rs_teacher->fetch_assoc();

        if ($num_teacher > 0) {
            // Class Teacher detected
            $_SESSION['userId'] = $rows_teacher['Id'];
            $_SESSION['firstName'] = $rows_teacher['firstName'];
            $_SESSION['lastName'] = $rows_teacher['lastName'];
            $_SESSION['emailAddress'] = $rows_teacher['emailAddress'];
            $_SESSION['classId'] = $rows_teacher['classId'];
            $_SESSION['user_type'] = 'ClassTeacher'; // Set session user type

            
            
            header('Location:ClassTeacher/index.php');
            exit();
        }
        else {
            // Check in Student table
            $query_student = "SELECT * FROM tblstudents WHERE email = '$username' AND password = '$password'";
            $rs_student = $conn->query($query_student);
            $num_student = $rs_student->num_rows;
            $rows_student = $rs_student->fetch_assoc();

            if ($num_student > 0) {
                // Student detected
                $_SESSION['userId'] = $rows_student['Id'];
                $_SESSION['admissionNumber'] = $rows_student['admissionNumber'];
                $_SESSION['firstName'] = $rows_student['firstName'];
                $_SESSION['lastName'] = $rows_student['lastName'];
                $_SESSION['email'] = $rows_student['email'];
                $_SESSION['classId'] = $rows_student['classId'];
                $_SESSION['user_type'] = 'Student';
                // Set session user type

                // Redirect to student dashboard
                header('Location:Student/index.php');
            exit();
            } 
            else {
                // Invalid username or password
                echo "<div class='alert alert-danger' role='alert'>
                Invalid Username/Password!
                </div>";
            }
        }
    }
}
ob_end_flush();
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
  <title>OJT-MS - Login</title>
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/ruang-admin.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-login">
  <!-- Login Content -->
  <div class="container-login">
    <div class="row justify-content-center">
      <div class="col-xl-10 col-lg-12 col-md-9">
        <div class="card shadow-sm my-5">
          <div class="card-body p-0">
            <div class="row">
              <div class="col-lg-12">
                <div class="login-form">
                <h5 align="center">OJT MONITORING SYSTEM</h5>
                  <div class="text-center">
                    <img src="img/logo/src-logo.jpg" style="width:100px;height:100px">
                    <br><br>
                    <h1 class="h4 text-gray-900 mb-4">Login Panel</h1>
                  </div>
                  <form class="user" method="Post" action="">
                  
                   
                    <div class="form-group">
                    
                      <input type="text" class="form-control" required name="username" id="exampleInputEmail" placeholder="Enter Email Address">
                    </div>
                    <div class="form-group">
    <input 
        type="password" 
        name="password" 
        required 
        class="form-control" 
        id="exampleInputPassword" 
        placeholder="Enter Password"
    >
</div>
                    <div class="form-group">
                      <div class="custom-control custom-checkbox small" style="line-height: 1.5rem;">
                        <input type="checkbox" class="custom-control-input" id="customCheck">
                        <!-- <label class="custom-control-label" for="customCheck">Remember
                          Me</label> -->
                      </div>
                    </div>
                    <div class="form-group">
                        <input type="submit"  class="btn btn-success btn-block" value="Login" name="login" />
                    </div>
                     </form>

                    <!-- <hr>
                    <a href="index.html" class="btn btn-google btn-block">
                      <i class="fab fa-google fa-fw"></i> Login with Google
                    </a>
                    <a href="index.html" class="btn btn-facebook btn-block">
                      <i class="fab fa-facebook-f fa-fw"></i> Login with Facebook
                    </a> -->

                
                  <div class="text-center">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Login Content -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="js/ruang-admin.min.js"></script>
</body>

</html>