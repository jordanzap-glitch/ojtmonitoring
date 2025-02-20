<?php 
ob_start();
include '../Includes/session.php';
include '../Includes/dbcon.php';
$statusMsg = "";

?>
        <table border="1">
        <thead>
            <tr>
           
            <th>Student ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Contact</th>
            <th>Email</th>
            <th>Address</th>
            <th>Session</th>
            <th>Term</th>
            <th>Status</th>
            <th>Date</th>
            </tr>
        </thead>

<?php 
$filename="Attendance list";
$dateTaken = date("Y-m-d");

$cnt=1;			
$ret = mysqli_query($conn,"SELECT tblattendance.Id,tblattendance.status,tblattendance.dateTimeTaken,tblclass.className, 
        tblclassarms.classArmName,tblsessionterm.sessionName,tblsessionterm.termId,tblterm.termName, 
        tblstudents.admissionNumber,tblstudents.firstName, tblstudents.lastName,tblstudents.contact, 
        tblstudents.email,tblstudents.address 
        FROM tblattendance 
        INNER JOIN tblclass ON tblclass.Id = tblattendance.classId 
        INNER JOIN tblclassarms ON tblclassarms.Id = tblattendance.classArmId 
        INNER JOIN tblsessionterm ON tblsessionterm.Id = tblattendance.sessionTermId 
        INNER JOIN tblterm ON tblterm.Id = tblsessionterm.termId 
        INNER JOIN tblstudents ON tblstudents.admissionNumber = tblattendance.admissionNo
        where tblattendance.dateTimeTaken = '$dateTaken' and tblattendance.classId = '$_SESSION[classId]' and tblattendance.classArmId = '$_SESSION[classArmId]'");

if(mysqli_num_rows($ret) > 0 )
{
while ($row=mysqli_fetch_array($ret)) 
{ 
    
    if($row['status'] == '1'){$status = "Present"; $colour="#00FF00";}else{$status = "Absent";$colour="#FF0000";}

echo '  
<tr>   

<td>'.$admissionNumber= $row['admissionNumber'].'</td>
<td>'.$firstName= $row['firstName'].'</td> 
<td>'.$lastName= $row['lastName'].'</td> 
<td>'.$contact= $row['contact'].'</td> 
<td>'.$email= $row['email'].'</td>
<td>'.$address= $row['address'].'</td> 
<td>'.$sessionName=$row['sessionName'].'</td>	 
<td>'.$termName=$row['termName'].'</td>	
<td>'.$status=$status.'</td>	 	
<td>'.$dateTimeTaken=$row['dateTimeTaken'].'</td>	 					
</tr>  
';
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=".$filename."-report.xls");
header("Pragma: no-cache");
header("Expires: 0");
			$cnt++;
			}
	}
ob_end_flush();
?>
</table>