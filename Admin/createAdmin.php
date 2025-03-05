<?php 
include '../Includes/session.php';
include '../Includes/dbcon.php';

//------------------------SAVE--------------------------------------------------

if(isset($_POST['save'])){
    
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $emailAddress = $_POST['emailAddress'];
    $password = $_POST['password'];
    $dateCreated = date("Y-m-d");

    // Check if the email already exists in the admin table
    $query = mysqli_query($conn, "SELECT * FROM tbladmin WHERE emailAddress ='$emailAddress'");

    if(mysqli_num_rows($query) > 0){ 
        $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>This Email Address Already Exists!</div>";
    } else {
        // Insert into tbladmin
        $insertQuery = mysqli_query($conn, "INSERT INTO tbladmin (firstName, lastName, emailAddress, password, dateCreated) 
            VALUES ('$firstName', '$lastName', '$emailAddress', '$password', '$dateCreated')");

        if ($insertQuery) {
            // Insert into tbluser
            $insertUser   = mysqli_query($conn, "INSERT INTO tbluser (emailAddress, password, user_type) 
                VALUES ('$emailAddress', '$password','Admin')");
            
            if ($insertUser  ) {
                $statusMsg = "<div class='alert alert-success' style='margin-right:700px;'>Admin Created Successfully!</div>";
            } else {
                $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>Admin created, but an error occurred while adding to tbluser!</div>";
            }
        } else {
            $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
        }
    }
}

//---------------------------------------EDIT------------------------------------------------------------

if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "edit") {
    $Id = $_GET['Id'];
    $query = mysqli_query($conn, "SELECT * FROM tbladmin WHERE Id='$Id'");
    $row = mysqli_fetch_assoc($query);
}

// Handle the update
if (isset($_POST['update'])) {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $emailAddress = $_POST['emailAddress'];
    $password = $_POST['password'];
    $Id = $_POST['Id']; // Get the Id from the form

    // Update the admin details
    $updateQuery = mysqli_query($conn, "UPDATE tbladmin SET firstName='$firstName', lastName='$lastName', emailAddress='$emailAddress', password='$password' WHERE Id='$Id'");

    // Update the password in tbluser
    $updateUser = mysqli_query($conn, "UPDATE tbluser SET password='$password' WHERE emailAddress='$emailAddress'");

    if ($updateQuery && $updateUser) {
        $statusMsg = "<div class='alert alert-success' style='margin-right:700px;'>Admin Updated Successfully!</div>";
    } else {
        $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred while updating!</div>";
    }
}

//--------------------------------DELETE------------------------------------------------------------------

if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "delete") {
    $Id = $_GET['Id'];

    $query = mysqli_query($conn, "DELETE FROM tbladmin WHERE Id='$Id'");
    if ($query) {
        // Optionally delete from tbluser as well
        mysqli_query($conn, "DELETE FROM tbluser WHERE emailAddress=(SELECT emailAddress FROM tbladmin WHERE Id='$Id')");
        echo "<script type='text/javascript'>window.location = ('createAdmin.php');</script>"; 
    } else {
        $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>"; 
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
  <link href="img/logo/attnlg.jpg " rel="icon">
  <?php include 'Includes/title.php'; ?>
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/ruang-admin.min.css" rel="stylesheet">

  <script>
    function classArmDropdown(str) {
        if (str == "") {
            document.getElementById("txtHint").innerHTML = "";
            return;
        } else { 
            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("txtHint").innerHTML = this.responseText;
                }
            };
            xmlhttp.open("GET","ajaxClassArms2.php?cid="+str,true);
            xmlhttp.send();
        }
    }
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
            <h1 class="h3 mb-0 text-gray-800">Admin Entry</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Admin Entry</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Add/Edit Admin</h6>
                  <?php echo $statusMsg; ?>
                </div>
                <div class="card-body">
                  <form method="post">
                    <div class="form-group row mb-3">
                        <div class="col-xl-6">
                            <label class="form-control-label">Firstname<span class="text-danger ml-2">*</span></label>
                            <input type="text" class="form-control" required name="firstName" value="<?php echo isset($row['firstName']) ? $row['firstName'] : ''; ?>" id="exampleInputFirstName">
                        </div>
                        <div class="col-xl-6">
                            <label class="form-control-label">Lastname<span class="text-danger ml-2">*</span></label>
                            <input type="text" class="form-control" required name="lastName" value="<?php echo isset($row['lastName']) ? $row['lastName'] : ''; ?>" id="exampleInputLastName">
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <div class="col-xl-6">
                            <label class="form-control-label">Email Address<span class="text-danger ml-2">*</span></label>
                            <input type="email" class="form-control" required name="emailAddress" value="<?php echo isset($row['emailAddress']) ? $row['emailAddress'] : ''; ?>" id="exampleInputEmail">
                        </div>
                        <div class="col-xl-6">
                            <label class="form-control-label">Password <span class="text-danger ml-2">*</span></label>
                            <input type="password" class="form-control" required name="password" id="exampleInputPassword" value="<?php echo isset($row['password']) ? $row['password'] : ''; ?>">
                        </div>
                    </div>
                    <input type="hidden" name="Id" value="<?php echo isset($row['Id']) ? $row['Id'] : ''; ?>">
                    
                    <?php
                    if (isset($Id)) {
                    ?>
                    <button type="submit" name="update" class="btn btn-warning">Update</button>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <?php
                    } else {           
                    ?>
                    <button type="submit" name="save" class="btn btn-primary">Save</button>
                    <?php
                    }         
                    ?>
                  </form>
                </div>
              </div>

              <!-- Input Group -->
              < ```php
              <div class="row">
                <div class="col-lg-12">
                  <div class="card mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                      <h6 class="m-0 font-weight-bold text-primary">Admin List</h6>
                    </div>
                    <div class="table-responsive p-3">
                      <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                        <thead class="thead-light">
                          <tr>
                            <th>#</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email Address</th>
                            <th>Password</th>
                            <th>Date Created</th>
                            <th>Edit</th>
                            <th>Delete</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          // Fetch the admin list
                          $query = "SELECT * FROM tbladmin";
                          $rs = $conn->query($query);
                          $num = $rs->num_rows;
                          $sn = 0;

                          if ($num > 0) {
                              while ($rows = $rs->fetch_assoc()) {
                                  $sn++;
                                  echo "
                                  <tr>
                                      <td>".$sn."</td>
                                      <td>".$rows['firstName']."</td>
                                      <td>".$rows['lastName']."</td>
                                      <td>".$rows['emailAddress']."</td>
                                      <td>".$rows['password']."</td>
                                      <td>".$rows['dateCreated']."</td>
                                      <td><a href='?action=edit&Id=".$rows['Id']."'><i class='fas fa-fw fa-edit'></i></a></td>
                                      <td><a href='?action=delete&Id=".$rows['Id']."'><i class='fas fa-fw fa-trash'></i></a></td>
                                  </tr>";
                              }
                          } else {
                              echo "<tr><td colspan='8' class='text-center'>No Record Found!</td></tr>";
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
  <!-- Page level plugins -->
  <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>

  <!-- Page level custom scripts -->
  <script>
    $(document).ready(function () {
      $('#dataTableHover').DataTable({
        "paging": true, // Enable pagination
        "lengthChange": true, // Allow changing the number of records per page
        "searching": true, // Enable searching
        "ordering": false, // Enable sorting
        "info": true, // Show info about the table
        "autoWidth": true // Disable auto width
      });
    });
  </script>
</body>
</html>