<?php 
session_start();
error_reporting(0); 

include '../Includes/dbcon.php';

$statusMsg = "";

//------------------------SAVE--------------------------------------------------

if(isset($_POST['save'])){
    
    $TaskCode=$_POST['Task_Code'];
    $TaskName=$_POST['Task_Name'];
    $Desc=$_POST['Description'];
    $Deadline=$_POST['Deadline'];
   
    $query=mysqli_query($conn,"select * from tbltask where Task_Code ='$TaskCode'");
    $ret=mysqli_fetch_array($query);

    if($ret > 0){ 

        $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>This Class Already Exists!</div>";
    }
    else{

        $query=mysqli_query($conn,"insert into tbltask(Task_Code,Task_Name, Description,Deadline,Stat) 
        value('$TaskCode', '$TaskName', '$Desc', '$Deadline', 'Pending')");

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

    $query=mysqli_query($conn,"select * from tbltask where Id ='$Id'");
    $row=mysqli_fetch_array($query);

 //------------UPDATE-----------------------------

 if(isset($_POST['update'])){
    
    $TaskCode=$_POST['Task_Code'];
    $TaskName=$_POST['Task_Name'];
    $Desc=$_POST['Description'];
    $Deadline=$_POST['Deadline'];

    $query=mysqli_query($conn,"update tbltask set Task_Code ='$TaskCode', Task_Name ='$TaskName',
    Description ='$Desc', Deadline ='$Deadline', Stat = 'Pending' where Id='$Id'");

    if ($query) {
        
        echo "<script type = \"text/javascript\">
        window.location = (\"createTask.php\")
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

    $query = mysqli_query($conn,"DELETE FROM tbltask WHERE Id='$Id'");

    if ($query == TRUE) {

            echo "<script type = \"text/javascript\">
            window.location = (\"createTask.php\")
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
            <h1 class="h3 mb-0 text-gray-800">Task Entry</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Task Entry</li>
            </ol>
          </div>
          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Add Task</h6>
                    <?php echo $statusMsg; ?>
                </div>
                <div class="card-body">
                  <form method="post">
                    <div class="form-group row mb-3">
                        <div class="col-xl-6">
                            <label class="form-control-label">Task Code<span class="text-danger ml-2">*</span></label>
                      <input type="text" class="form-control" name="Task_Code"  id="exampleInputFirstName">
                        </div>

                         <div class="col-xl-6">
                            <label class="form-control-label">Task Name<span class="text-danger ml-2">*</span></label>
                      <input type="text" class="form-control" name="Task_Name"  id="exampleInputFirstName">
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                    <div class="col-xl-6">
                            <label class="form-control-label">Description<span class="text-danger ml-2">*</span></label>
                      <input type="text" class="form-control" name="Description"  id="exampleInputFirstName">
                        </div>

                        <div class="col-xl-6">
                        <label class="form-control-label">Select Date of Deadline<span class="text-danger ml-2">*</span></label>
                        <input type="date" class="form-control" name="Deadline"  id="exampleInputFirstName" min="<?= date('Y-m-d'); ?>">

                        
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
                  <h6 class="m-0 font-weight-bold text-primary">Task List</h6>
                </div>
                <div class="table-responsive p-3">
                  <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                    <thead class="thead-light">
                      <tr>
                        <th>Task Code</th>
                        <th>Task Name</th>
                        <th>Description</th>
                        <th>Deadline</th>
                        <th>Edit</th>
                        <th>Delete</th>
                      </tr>
                    </thead>
                  
                    <tbody>
                    <?php
                      $query = "SELECT * FROM tbltask";
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
                                <td>".$rows['Task_Code']."</td>
                                <td>".$rows['Task_Name']."</td>
                                <td>".$rows['Description']."</td>
                                <td>".$rows['Deadline']."</td>

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


<?php 
session_start();
error_reporting(0); 

include '../Includes/dbcon.php';

$statusMsg = "";

//------------------------SAVE--------------------------------------------------

if(isset($_POST['save'])){
    
    $TaskCode=$_POST['Task_Code'];
    $TaskName=$_POST['Task_Name'];
    $Desc=$_POST['Description'];
    $Deadline=$_POST['Deadline'];
   
    $query=mysqli_query($conn,"select * from tbltask where Task_Code ='$TaskCode'");
    $ret=mysqli_fetch_array($query);

    if($ret > 0){ 

        $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>This Class Already Exists!</div>";
    }
    else{

        $query=mysqli_query($conn,"insert into tbltask(Task_Code,Task_Name, Description,Deadline,Stat) 
        value('$TaskCode', '$TaskName', '$Desc', '$Deadline', 'Pending')");

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

    $query=mysqli_query($conn,"select * from tbltask where Id ='$Id'");
    $row=mysqli_fetch_array($query);

 //------------UPDATE-----------------------------

 if(isset($_POST['update'])){
    
    $TaskCode=$_POST['Task_Code'];
    $TaskName=$_POST['Task_Name'];
    $Desc=$_POST['Description'];
    $Deadline=$_POST['Deadline'];

    $query=mysqli_query($conn,"update tbltask set Task_Code ='$TaskCode', Task_Name ='$TaskName',
    Description ='$Desc', Deadline ='$Deadline', Stat = 'Pending' where Id='$Id'");

    if ($query) {
        
        echo "<script type = \"text/javascript\">
        window.location = (\"createTask.php\")
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

    $query = mysqli_query($conn,"DELETE FROM tbltask WHERE Id='$Id'");

    if ($query == TRUE) {

            echo "<script type = \"text/javascript\">
            window.location = (\"createTask.php\")
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
            <h1 class="h3 mb-0 text-gray-800">Task Entry</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Task Entry</li>
            </ol>
          </div>
          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Add Task</h6>
                    <?php echo $statusMsg; ?>
                </div>
                <div class="card-body">
                  <form method="post">
                    <div class="form-group row mb-3">
                        <div class="col-xl-6">
                            <label class="form-control-label">Task Code<span class="text-danger ml-2">*</span></label>
                      <input type="text" class="form-control" name="Task_Code"  id="exampleInputFirstName">
                        </div>

                         <div class="col-xl-6">
                            <label class="form-control-label">Task Name<span class="text-danger ml-2">*</span></label>
                      <input type="text" class="form-control" name="Task_Name"  id="exampleInputFirstName">
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                    <div class="col-xl-6">
                            <label class="form-control-label">Description<span class="text-danger ml-2">*</span></label>
                      <input type="text" class="form-control" name="Description"  id="exampleInputFirstName">
                        </div>

                        <div class="col-xl-6">
                        <label class="form-control-label">Select Date of Deadline<span class="text-danger ml-2">*</span></label>
                        <input type="date" class="form-control" name="Deadline"  id="exampleInputFirstName" min="<?= date('Y-m-d'); ?>">

                        
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
                  <h6 class="m-0 font-weight-bold text-primary">Task List</h6>
                </div>
                <div class="table-responsive p-3">
                  <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                    <thead class="thead-light">
                      <tr>
                        <th>Task Code</th>
                        <th>Task Name</th>
                        <th>Description</th>
                        <th>Deadline</th>
                        <th>Edit</th>
                        <th>Delete</th>
                      </tr>
                    </thead>
                  
                    <tbody>
                    <?php
                      $query = "SELECT * FROM tbltask";
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
                                <td>".$rows['Task_Code']."</td>
                                <td>".$rows['Task_Name']."</td>
                                <td>".$rows['Description']."</td>
                                <td>".$rows['Deadline']."</td>

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
