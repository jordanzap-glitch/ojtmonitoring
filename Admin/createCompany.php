<?php 
error_reporting(E_ALL);
include '../Includes/session.php';
include '../Includes/dbcon.php';


//------------------------SAVE--------------------------------------------------

if(isset($_POST['save'])){
    
    $CompName=$_POST['comp_name'];
    $ContPerson=$_POST['contact_person'];
    $ContNum=$_POST['contact_num'];
    $CompAdd=$_POST['comp_address'];
    $comp_link=$_POST['comp_link'];

   
    $query=mysqli_query($conn,"select * from tblcompany where comp_name ='$CompName'");
    $ret=mysqli_fetch_array($query);

    if($ret > 0){ 

        $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>This Company Already Exists!</div>";
    }
    else{

        $query=mysqli_query($conn,"insert into tblcompany(comp_name,contact_person, contact_num,comp_address, comp_link) 
        value('$CompName', '$ContPerson', '$ContNum', '$CompAdd','$comp_link')");

    if ($query) {
        
        $statusMsg = "<div class='alert alert-success'  style='margin-right:700px;'>Created Successfully!</div>";
    }
    else
    {
         $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
    }
  }
}
//---------------------------------------EDIT-------------------------------------------------------------






//--------------------EDIT------------------------------------------------------------

if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "edit")
{
    $Id= $_GET['Id'];

    $query=mysqli_query($conn,"select * from tblcompany where Id ='$Id'");
    $row=mysqli_fetch_array($query);

 //------------UPDATE-----------------------------

 if(isset($_POST['update'])){
    
    $CompName=$_POST['comp_name'];
    $ContPerson=$_POST['contact_person'];
    $ContNum=$_POST['contact_num'];
    $CompAdd=$_POST['comp_address'];
    $comp_link=$_POST['comp_link'];

    $query=mysqli_query($conn,"update tblcompany set comp_name ='$CompName', contact_person ='$ContPerson',
    contact_num ='$ContNum', comp_address ='$CompAdd', comp_link ='$comp_link' where Id='$Id'");

    if ($query) {
        
        echo "<script type = \"text/javascript\">
        window.location = (\"createCompany.php\")
        </script>"; 
    }
    else
    {
        $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
    }
}
}

//--------------------------------DELETE------------------------------------------------------------------

if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "delete")
{
    $Id= $_GET['Id'];

    $query = mysqli_query($conn,"DELETE FROM tblcompany WHERE Id='$Id'");

    if ($query == TRUE) {

            echo "<script type = \"text/javascript\">
            window.location = (\"createCompany.php\")
            </script>";  
    }
    else{

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
  <link href="img/logo/attnlg.jpg" rel="icon">
<?php include 'includes/title.php';?>
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/ruang-admin.min.css" rel="stylesheet">
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
            <h1 class="h3 mb-0 text-gray-800">Company Entry</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Company Entry</li>
            </ol>
          </div>
          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Add Company</h6>
                    <?php echo $statusMsg; ?>
                </div>
                <div class="card-body">
                  <form method="post">
                    <div class="form-group row mb-3">
                        <div class="col-xl-6">
                            <label class="form-control-label">Company Name<span class="text-danger ml-2">*</span></label>
                      <input type="text" class="form-control" name="comp_name" value="<?php echo $row['comp_name'];?>" id="exampleInputFirstName">
                        </div>

                         <div class="col-xl-6">
                            <label class="form-control-label">Contact Person<span class="text-danger ml-2">*</span></label>
                      <input type="text" class="form-control" name="contact_person" value="<?php echo $row['contact_person'];?>" id="exampleInputFirstName" placeholder="Full Name">
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                    <div class="col-xl-6">
                            <label class="form-control-label">Contact Number<span class="text-danger ml-2">*</span></label>
                            <input type="text" class="form-control" name="contact_num" value="<?php echo $row['contact_num'];?>" id="exampleInputFirstName">
                            <label class="form-control-label">Company Link (Optional)<span class="text-danger ml-2"></span></label>
                            <input type="text" class="form-control" name="comp_link" value="<?php echo $row['contact_num'];?>" id="exampleInputFirstName">
                        </div>

                        <div class="col-xl-6">
                            <label class="form-control-label">Company Address<span class="text-danger ml-2">*</span></label>
                            <input type="text" class="form-control" name="comp_address" value="<?php echo $row['comp_address'];?>" id="exampleInputFirstName">
                        </div>

                        </div>
                        <?php
                    if (isset($Id))
                    {
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
<div class="row">
              <div class="col-lg-12">
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Companies</h6>
                </div>
                <div class="table-responsive p-3">
                  <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                    <thead class="thead-light">
                      <tr>
                        <th>#</th>
                        <th>Company Name</th>
                        <th>Contact Person</th>
                        <th>Contact Number</th>
                        <th>Company Address</th>
                        <th>Company Link</th>
                        <th>Edit</th>
                        <th>Delete</th>
                      </tr>
                    </thead>
                  
                    <tbody>
                    <?php
                      $query = "SELECT * FROM tblcompany";
                      $rs = $conn->query($query);
                      $num = $rs->num_rows;
                      $sn=0;
                      if($num > 0)
                      { 
                        while ($rows = $rs->fetch_assoc())
                          {
                             $sn = $sn + 1;
                            echo"
                              <tr>
                                <td>".$sn."</td>
                                <td>".$rows['comp_name']."</td>
                                <td>".$rows['contact_person']."</td>
                                <td>".$rows['contact_num']."</td>
                                <td>".$rows['comp_address']."</td>
                                <td>".$rows['comp_link']."</td>

                                <td><a href='?action=edit&Id=".$rows['Id']."'><i class='fas fa-fw fa-edit'></i>Edit</a></td>
                                <td><a href='?action=delete&Id=".$rows['Id']."'><i class='fas fa-fw fa-trash'></i>Delete</a></td>
                              </tr>";
                          }
                      }
                      else
                      {
                           echo   
                           "<div class='alert alert-danger' role='alert'>
                            No Record Found!
                            </div>";
                      }
                      
                      ?>
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
      $('#dataTable').DataTable(); // ID From dataTable 
      $('#dataTableHover').DataTable(); // ID From dataTable with Hover
    });
  </script>
</body>

</html>


