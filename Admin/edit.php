<?php 
error_reporting(0);
include '../Includes/session.php';
include '../Includes/dbcon.php';

//------------------------SAVE--------------------------------------------------

if(isset($_POST['save'])){
  
  $admissionNumber=$_POST['admissionNumber'];
  $firstName=$_POST['firstName'];
  $lastName=$_POST['lastName'];
  $classId=$_POST['classId'];
  $classArmId=$_POST['classArmId'];
  $contact=$_POST['contact'];
  $email=$_POST['email'];
  $address=$_POST['address'];
  $company=$_POST['comp_name'];
  $remaining_time=$_POST['remaining_time'];
  
  $password=$_POST['password'];
  $sampPass_2 = ($password);

  $dateCreated = date("Y-m-d");

  $usertype = $_POST['user_type'];
  $usernew = "Student";
   
  $query=mysqli_query($conn,"select * from tblstudents where admissionNumber ='$admissionNumber'");
  $query1=mysqli_query($conn,"select * from tbluser where emailAddress ='$email'");

  $ret=mysqli_fetch_array($query);

  if($ret > 0){ 
      $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>This Email Address Already Exists!</div>";
  } else {
      $query=mysqli_query($conn,"insert into tblstudents(admissionNumber,firstName,lastName,classId,classArmId,contact,email,address,comp_name,password,dateCreated, remaining_time) 
      value('$admissionNumber','$firstName','$lastName','$classId','$classArmId','$contact','$email','$address','$company','$sampPass_2','$dateCreated','$remaining_time')");

      $query1=mysqli_query($conn,"INSERT into tbluser(emailAddress,password, user_type) 
      value('$email','$sampPass_2','$usernew')");
    
      if ($query) {
          $statusMsg = "<div class='alert alert-success'  style='margin-right:700px;'>Created Successfully!</div>";
      } else {
          $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
      }
  }
}
//-------update---
if(isset($_POST['update'])){
  $Id = $_POST['Id'];
  $admissionNumber = $_POST['admissionNumber'];
  $firstName = $_POST['firstName'];
  $lastName = $_POST['lastName'];
  $classId = $_POST['classId'];
  $classArmId = $_POST['classArmId'];
  $contact = $_POST['contact'];
  $email = $_POST['email'];
  $address = $_POST['address'];
  $company = $_POST['comp_name'];
  $remaining_time = $_POST['remaining_time'];
  $password = $_POST['password'];

  $query = mysqli_query($conn, "UPDATE tblstudents SET admissionNumber='$admissionNumber', firstName='$firstName', lastName='$lastName', classId='$classId', classArmId='$classArmId', contact='$contact', email='$email', address='$address', comp_name='$company', password='$password', remaining_time='$remaining_time' WHERE Id='$Id'");

  if ($query) {
      $statusMsg = "<div class='alert alert-success' style='margin-right:700px;'>Updated Successfully!</div>";
  } else {
      $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
  }
}
//--------------------------------DELETE------------------------------------------------------------------

if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "delete") {
    $Id= $_GET['Id'];
    $query = mysqli_query($conn,"DELETE FROM tblstudents WHERE Id='$Id'");
    $query1 = mysqli_query($conn,"DELETE FROM tbluser WHERE emailAddress='$email'");

    if ($query == TRUE) {
        echo "<script type = \"text/javascript\">
        window.location = (\"createStudents.php\")
        </script>";
    } else {
        $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>"; 
    }
}



if (isset($_GET['editId'])) {
  $editId = $_GET['editId'];
  $query = mysqli_query($conn, "SELECT * FROM tblstudents WHERE Id='$editId'");
  $studentData = mysqli_fetch_array($query);
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
  <?php include 'includes/title.php';?>
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
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
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
        <?php include "Includes/sidebar.php";?>
        <!-- Sidebar -->
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <!-- TopBar -->
                <?php include "Includes/topbar.php";?>
                <!-- Topbar -->

                <!-- Container Fluid-->
                <div class="container-fluid" id="container-wrapper">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Student Entry</h1>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="./">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Student Entry</li>
                        </ol>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <!-- Form Basic -->
                            <div class="card mb-4">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary"><?php echo isset($studentData) ? 'Edit Student' : 'Create Students'; ?></h6>
                                    <?php echo $statusMsg; ?>
                                </div>
                                <div class="card-body">
                                    <form method="post">
                                        <input type="hidden" name="Id" value="<?php echo isset($studentData) ? $studentData['Id'] : ''; ?>">
                                        <div class="form-group row mb-3">
                                            <div class="col-xl-6">
                                                <label class="form-control-label">School ID Number<span class="text-danger ml-2">*</span></label>
                                                <input type="text" class="form-control" name="admissionNumber" value="<?php echo isset($studentData) ? $studentData['admissionNumber'] : ''; ?>" required>
                                            </div>
                                            <div class="col-xl-6">
                                                <label class="form-control-label">Select Company<span class="text-danger ml-2">*</span></label>
                                                <?php
                                                $qry = "SELECT * FROM tblcompany ORDER BY comp_name ASC";
                                                $result = $conn->query($qry);
                                                $num = $result->num_rows;		
                                                if ($num > 0) {
                                                    echo '<select required name="comp_name" class="form-control mb-3">';
                                                    echo '<option value="">--Select Company--</option>';
                                                    while ($rows = $result->fetch_assoc()) {
                                                        $selected = (isset($studentData) && $studentData['comp_name'] == $rows['comp_name']) ? 'selected' : '';
                                                        echo '<option value="' . $rows['comp_name'] . '" ' . $selected . '>' . $rows['comp_name'] . '</option>';
                                                    }
                                                    echo '</select>';
                                                }
                                                ?>  
                                            </div>
                                        </div>

                                        <div class="form-group row mb-3">
                                            <div class="col-xl-6">
                                                <label class="form-control-label">Firstname<span class="text-danger ml-2">*</span></label>
                                                <input type="text" class="form-control" name="firstName" value="<?php echo isset($studentData) ? $studentData['firstName'] : ''; ?>" required>
                                            </div>
                                            <div class="col-xl-6">
                                                <label class="form-control-label">Lastname<span class="text-danger ml-2">*</span></label>
                                                <input type="text" class="form-control" name="lastName" value="<?php echo isset($studentData) ? $studentData['lastName'] : ''; ?>" required>
                                            </div>
                                        </div>

                                        <div class="form-group row mb-3">
                                            <div class="col-xl-6">
                                                <label class="form-control-label">Select Class<span class="text-danger ml-2">*</span></label>
                                                <?php
                                                $qry = "SELECT * FROM tblclass ORDER BY className ASC";
                                                $result = $conn->query($qry);
                                                $num = $result->num_rows;		
                                                if ($num > 0) {
                                                    echo '<select required name="classId" class="form-control mb-3">';
                                                    echo '<option value="">--Select Class--</option>';
                                                    while ($rows = $result->fetch_assoc()) {
                                                        $selected = (isset($studentData) && $studentData['classId'] == $rows['Id']) ? 'selected' : '';
                                                        echo '<option value="' . $rows['Id'] . '" ' . $selected . '>' . $rows['className'] . '</option>';
                                                    }
                                                    echo '</select>';
                                                }
                                                ?>  
                                            </div>
                                            <div class="col-xl-6">
                                            <label class="form-control-label">Hours need to Render <span class="text-danger ml-2">*</span></label>
                                                <input type="number" class="form-control" name="remaining_time" value="<?php echo isset($studentData) ? $studentData['remaining_time'] : ''; ?>" required>
                                            </div>
                                            </div>

                                        <div class="form-group row mb-3">
                                            <div class="col-xl-6">
                                                <label class="form-control-label">Contact Number<span class="text-danger ml-2">*</span></label>
                                                <input type="text" class="form-control" name="contact" value="<?php echo isset($studentData) ? $studentData['contact'] : ''; ?>" required>
                                            </div>
                                            <div class="col-xl-6">
                                                <label class="form-control-label">Email Address<span class="text-danger ml-2">*</span></label>
                                                <input type="email" class="form-control" name="email" value="<?php echo isset($studentData) ? $studentData['email'] : ''; ?>" required>
                                            </div>
                                        </div>

                                        <div class="form-group row mb-3">
                                            <div class="col-xl-6">
                                                <label class="form-control-label">Home Address<span class="text-danger ml-2">*</span></label>
                                                <input type="text" class="form-control" name="address" value="<?php echo isset($studentData) ? $studentData['address'] : ''; ?>" required>
                                            </div>
                                            <div class="col-xl-6">
                                            <label class="form-control-label">Password <span class="text-danger ml-2">*</span></label>
                                                <input type="password" class="form-control" name="password" value="<?php echo isset($studentData) ? $studentData['password'] : ''; ?>" required>
                                            </div>
                                           
                                               
                                        </div>

                                        <button type="submit" name="<?php echo isset($studentData) ? 'update' : 'save'; ?>" class="btn btn-primary"><?php echo isset($studentData) ? 'Update' : 'Save'; ?></button>
                                    </form>
                                </div>
                            </div>

                            <!-- Input Group -->
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card mb-4">
                                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                            <h6 class="m-0 font-weight-bold text-primary">All Students</h6>
                                        </div>
                                        <div class="table-responsive p-3">
                                            <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Student ID</th>
                                                        <th>First Name</th>
                                                        <th>Last Name</th>
                                                        <th>Course</th>
                                                        <th>Contact</th>
                                                        <th>Email</th>
                                                        <th>Address</th>
                                                        <th>Company</th>
                                                        <th>Date Created</th>
                                                        <th>Edit</th>
                                                        <th>Delete</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $query = "SELECT tblstudents.Id, tblclass.className, tblstudents.admissionNumber, tblstudents.firstName, tblstudents.lastName, tblstudents.contact, tblstudents.email, tblstudents.address, tblstudents.dateCreated, tblstudents.comp_name FROM tblstudents 
                                                    INNER JOIN tblclass ON tblclass.Id = tblstudents.classId";
                                                    $rs = $conn->query($query);
                                                    $num = $rs->num_rows;
                                                    $sn = 0;

                                                    if ($num > 0) { 
                                                        while ($rows = $rs->fetch_assoc()) {
                                                            $sn++;
                                                            echo "
                                                            <tr>
                                                                <td>".$sn."</td>
                                                                <td>".$rows['admissionNumber']."</td>
                                                                <td>".$rows['firstName']."</td>
                                                                <td>".$rows['lastName']."</td>
                                                                <td>".$rows['className']."</td>
                                                                <td>".$rows['contact']."</td>
                                                                <td>".$rows['email']."</td>
                                                                <td>".$rows['address']."</td>
                                                                <td>".$rows['comp_name']."</td>
                                                                <td>".$rows['dateCreated']."</td>
                                                                <td><a href='?editId=".$rows['Id']."'><i class='fas fa-fw fa-edit'></i></a></td>
                                                                <td><a href='?action=delete&Id=".$rows['Id']."'><i class='fas fa-fw fa-trash'></i></a></td>
                                                            </tr>";
                                                        }
                                                    } else {
                                                        echo "<div class='alert alert-danger' role='alert'>No Record Found!</div>";
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