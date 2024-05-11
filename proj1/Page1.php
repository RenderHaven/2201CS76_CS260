<?php
// Database connection parameters
session_start();
$email = $_SESSION['email'];
require "config.php";
require_once('library/tcpdf.php');
require('fpdf186/fpdf.php');

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $adv_num = $_POST['adv_num'];
    $doa = $_POST['doa'];
    $ref_num = $_POST['ref_num'];
    $post = $_POST['post'];
    $dept = $_POST['dept'];

    $fname = $_POST['fname'];
    $mname = $_POST['mname'];
    $lname = $_POST['lname'];
    $nationality = $_POST['nationality'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $mstatus = $_POST['mstatus'];
    $cast = $_POST['cast'];
    $id_proof = $_POST['id_proof'];
    $father_name = $_POST['father_name'];
    $cadd = $_POST['cadd'];
    $cadd1 = $_POST['cadd1'];
    $cadd2 = $_POST['cadd2'];
    $cadd3 = $_POST['cadd3'];
    $cadd4 = $_POST['cadd4'];
    $padd = $_POST['padd'];
    $padd1 = $_POST['padd1'];
    $padd2 = $_POST['padd2'];
    $padd3 = $_POST['padd3'];
    $padd4 = $_POST['padd4'];
    $mobile = $_POST['mobile'];
    $mobile_2 = $_POST['mobile_2'];
    $email_2 = $_POST['email_2'];
    $landline = $_POST['landline'];

    // Upload files
    $upload_dir = "uploads/"; // Directory where files will be uploaded
    $userfile = $_FILES['userfile']['name'];
    $userfile2 = $_FILES['userfile2']['name'];
    move_uploaded_file($_FILES["userfile"]["tmp_name"], $upload_dir . $userfile);
    move_uploaded_file($_FILES["userfile2"]["tmp_name"], $upload_dir . $userfile2);


    // Prepare SQL statement
    $sql_personal = "INSERT INTO personaldetails (fname, mname, lname, nationality, dob, gender, mstatus, cast, id_proof, userfile2, father_name, userfile, cadd, cadd1, cadd2, cadd3, cadd4, padd, padd1, padd2, padd3, padd4, mobile, mobile_2, email, email_2, landline)
            VALUES ('$fname', '$mname', '$lname', '$nationality', '$dob', '$gender', '$mstatus', '$cast', '$id_proof', '$userfile2', '$father_name', '$userfile', '$cadd', '$cadd1', '$cadd2', '$cadd3', '$cadd4', '$padd', '$padd1', '$padd2', '$padd3', '$padd4', '$mobile', '$mobile_2', '$email', '$email_2', '$landline')";
    $sql_profile = "INSERT INTO profile (adv_num, doa, ref_num, post, dept, email) 
                        VALUES ('$adv_num', '$doa', '$ref_num', '$post', '$dept', '$email')";

    $result_personal = $mysqli->query($sql_personal);
    $result_profile = $mysqli->query($sql_profile);

  

    // Execute SQL statement
    if ($result_personal && $result_profile) {
        echo "New record created successfully";
        // After successfully registering the user
        $_SESSION['email'] = $email; // $email is the user's email address
        // Redirect to the page where PhD details are input
        header("Location: page2.php");
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement
    //$stmt->close();
} else {
    echo "Form not submitted!";
}
  // Fetch data from the database
// Fetch data from the database
$query = "SELECT * FROM profile WHERE email = '$email'";
$result = $mysqli->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Fetch personal details from the database
        $personal_query = "SELECT * FROM personaldetails WHERE email = '$email'";
        $personal_result = $mysqli->query($personal_query);

        if ($personal_result->num_rows > 0) {
            while ($personal_row = $personal_result->fetch_assoc()) {
                // Create a new PDF instance
                $pdf = new FPDF();
                $pdf->AddPage();

                // Set font for heading
                $pdf->SetFont('Arial', 'B', 16);

                // Output user details to PDF
                $pdf->Cell(0, 10, 'User Details', 0, 1, 'C');
                $pdf->Ln();
                 $pdf->SetFont('Arial', '', 12);
                $pdf->Cell(0, 10, 'Email: ' . $row['email'], 0, 1);
                $pdf->Cell(0, 10, 'Adv Num: ' . $row['adv_num'], 0, 1);
                $pdf->Cell(0, 10, 'Date of Appointment: ' . $row['doa'], 0, 1);
                $pdf->Cell(0, 10, 'Reference Number: ' . $row['ref_num'], 0, 1);
                $pdf->Cell(0, 10, 'Post: ' . $row['post'], 0, 1);
                $pdf->Cell(0, 10, 'Department: ' . $row['dept'], 0, 1);
                 $pdf->Ln();
                // Personal Details Table
                $pdf->SetFont('Arial', 'B', 14);
                $pdf->Cell(0, 10, '1. Personal Details', 0, 1);
                $pdf->SetFont('Arial', '', 12);
                $pdf->Cell(60, 10, 'First Name: ', 0, 0);
                $pdf->Cell(0, 10, $personal_row['fname'], 0, 1);
                $pdf->Cell(60, 10, 'Middle Name: ', 0, 0);
                $pdf->Cell(0, 10, $personal_row['mname'], 0, 1);
                $pdf->Cell(60, 10, 'Last Name: ', 0, 0);
                $pdf->Cell(0, 10, $personal_row['lname'], 0, 1);
                $pdf->Cell(60, 10, 'Nationality: ', 0, 0);
                $pdf->Cell(0, 10, $personal_row['nationality'], 0, 1);
                $pdf->Cell(60, 10, 'Date of Birth: ', 0, 0);
                $pdf->Cell(0, 10, $personal_row['dob'], 0, 1);
                $pdf->Cell(60, 10, 'Gender: ', 0, 0);
                $pdf->Cell(0, 10, $personal_row['gender'], 0, 1);
                $pdf->Cell(60, 10, 'Marital Status: ', 0, 0);
                $pdf->Cell(0, 10, $personal_row['mstatus'], 0, 1);
                $pdf->Cell(60, 10, 'Cast: ', 0, 0);
                $pdf->Cell(0, 10, $personal_row['cast'], 0, 1);
                $pdf->Cell(60, 10, 'ID Proof: ', 0, 0);
                $pdf->Cell(0, 10, $personal_row['id_proof'], 0, 1);
                $pdf->Cell(60, 10, 'Father Name: ', 0, 0);
                $pdf->Cell(0, 10, $personal_row['father_name'], 0, 1);
                $pdf->Ln();

                // Address Details Table
                $pdf->SetFont('Arial', 'B', 14);
                $pdf->Cell(0, 10, '2. Address Details', 0, 1);
                $pdf->SetFont('Arial', '', 12);
                $pdf->Cell(60, 10, 'Current Address: ', 0, 0);
                $pdf->Cell(0, 10, $personal_row['cadd'], 0, 1);
                $pdf->Cell(60, 10, '', 0, 0);
                $pdf->Cell(0, 10, $personal_row['cadd1'], 0, 1);
                $pdf->Cell(60, 10, '', 0, 0);
                $pdf->Cell(0, 10, $personal_row['cadd2'], 0, 1);
                $pdf->Cell(60, 10, '', 0, 0);
                $pdf->Cell(0, 10, $personal_row['cadd3'], 0, 1);
                $pdf->Cell(60, 10, '', 0, 0);
                $pdf->Cell(0, 10, $personal_row['cadd4'], 0, 1);
                $pdf->Ln();
                $pdf->Cell(60, 10, 'Permanent Address: ', 0, 0);
                $pdf->Cell(0, 10, $personal_row['padd'], 0, 1);
                $pdf->Cell(60, 10, '', 0, 0);
                $pdf->Cell(0, 10, $personal_row['padd1'], 0, 1);
                $pdf->Cell(60, 10, '', 0, 0);
                $pdf->Cell(0, 10, $personal_row['padd2'], 0, 1);
                $pdf->Cell(60, 10, '', 0, 0);
                $pdf->Cell(0, 10, $personal_row['padd3'], 0, 1);
                $pdf->Cell(60, 10, '', 0, 0);
                $pdf->Cell(0, 10, $personal_row['padd4'], 0, 1);
                $pdf->Ln();

                // Contact Details Table
                $pdf->SetFont('Arial', 'B', 14);
                $pdf->Cell(0, 10, '3. Contact Details', 0, 1);
                $pdf->SetFont('Arial', '', 12);
                $pdf->Cell(60, 10, 'Mobile: ', 0, 0);
                $pdf->Cell(0, 10, $personal_row['mobile'], 0, 1);
                $pdf->Cell(60, 10, 'Alternate Mobile: ', 0, 0);
                $pdf->Cell(0, 10, $personal_row['mobile_2'], 0, 1);
                $pdf->Cell(60, 10, 'Email 2: ', 0, 0);
                $pdf->Cell(0, 10, $personal_row['email_2'], 0, 1);
                $pdf->Cell(60, 10, 'Landline: ', 0, 0);
                $pdf->Cell(0, 10, $personal_row['landline'], 0, 1);

                // Output PDF to file
                $file_path = 'pdf_output/output.pdf';
                $pdf->Output($file_path, 'F');
            }
        }
    }
}

// Close connection
$mysqli->close();
?>




<html>
<head>
	<title>Update your personal details</title>
	<link rel="stylesheet" type="text/css" href="https://ofa.iiti.ac.in/facrec_che_2023_july_02/images/favicon.ico" type="image/x-icon">
	<link rel="icon" href="https://ofa.iiti.ac.in/facrec_che_2023_july_02/images/favicon.ico" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="https://ofa.iiti.ac.in/facrec_che_2023_july_02/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="https://ofa.iiti.ac.in/facrec_che_2023_july_02/css/bootstrap-datepicker.css">
	<script type="text/javascript" src="https://ofa.iiti.ac.in/facrec_che_2023_july_02/js/jquery.js"></script>
	<script type="text/javascript" src="https://ofa.iiti.ac.in/facrec_che_2023_july_02/js/bootstrap.js"></script>
	<script type="text/javascript" src="https://ofa.iiti.ac.in/facrec_che_2023_july_02/js/bootstrap-datepicker.js"></script>

	<link href="https://fonts.googleapis.com/css?family=Sintony" rel="stylesheet"> 
	<link href="https://fonts.googleapis.com/css?family=Fjalla+One" rel="stylesheet"> 
	<link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet"> 
	<link href="https://fonts.googleapis.com/css?family=Hind&display=swap" rel="stylesheet"> 
	<link href="https://fonts.googleapis.com/css?family=Noto+Sans&display=swap" rel="stylesheet"> 
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Noto+Serif&display=swap" rel="stylesheet">


	
</head>
<style type="text/css">
	body { background-color: lightgray; padding-top:0px!important;}

</style>
<body>
<div class="container-fluid" style="background-color: #f7ffff; margin-bottom: 10px;">
	<div class="container">
        <div class="row" style="margin-bottom:10px; ">
        	<div class="col-md-8 col-md-offset-2">

        		<!--  <img src="https://ofa.iiti.ac.in/facrec_che_2023_july_02/images/IITIndorelogo.png" alt="logo1" class="img-responsive" style="padding-top: 5px; height: 120px; float: left;"> -->

        		<h3 style="text-align:center;color:#414002!important;font-weight: bold;font-size: 2.3em; margin-top: 3px; font-family: 'Noto Sans', sans-serif;">भारतीय प्रौद्योगिकी संस्थान पटना</h3>
    			<h3 style="text-align:center;color: #414002!important;font-weight: bold;font-family: 'Oswald', sans-serif!important;font-size: 2.2em; margin-top: 0px;">Indian Institute of Technology Patna</h3>
    			

        	</div>
        	

    	   
        </div>
		    <!-- <h3 style="text-align:center; color: #414002; font-weight: bold;  font-family: 'Fjalla One', sans-serif!important; font-size: 2em;">Application for Academic Appointment</h3> -->
    </div>
   </div> 
			<h3 style="color: #e10425; margin-bottom: 20px; font-weight: bold; text-align: center;font-family: 'Noto Serif', serif;" class="blink_me">Application for Faculty Position</h3>

<!-- <body onload="updateAb()">  -->

<style type="text/css">
body { padding-top:30px; }
.floating-box {
display: inline-block;
width: 150px;
height: 75px;
margin: 10px;
border: 3px solid #73AD21;  
}
</style>
<style type="text/css">
body { padding-top:30px; }
.form-control { margin-bottom: 20px; }
label{
padding: 0 !important;
text-align: left!important;
font-family: 'Noto Serif', serif;
}

span{
font-size: 1.2em;
font-family: 'Oswald', sans-serif!important;
text-align: left!important;
padding: 0px 10px 0px 0px!important;
/*font-family: 'Noto Serif', serif;*/
font-weight: bold;
color: #414002;
/*margin-bottom: 20px!important;*/

}
hr{
border-top: 1px solid #025198 !important;
border-style: dashed!important;
border-width: 1.2px;
}

.panel-heading{
font-size: 1.3em;
font-family: 'Oswald', sans-serif!important;
letter-spacing: .5px;
}
.btn-primary{
padding: 9px;
}
</style>


<body>

<script type="text/javascript">  
//   $("#dob").focusout(function(){
//     alert();
//   ageCalculator();
// });
function ageCalculator() 
{
// alert('HI');  

debugger;  
var birthdate = document.getElementById('dob').value; // in "dd/MM/yyyy" format  
var senddate = document.getElementById('Date').value; // in "dd/MM/yyyy" format  
var x = birthdate.split("/");  
var y = senddate.split("/");  
var bdays = x[0];  
var bmonths = x[1];  
var byear = x[2];  
//alert(bdays);  
var sdays = y[0];  
var smonths = y[1];  
var syear = y[2];  
// alert(sdays);  
if (sdays < bdays) {  
sdays = parseInt(sdays) + 30;  
smonths = parseInt(smonths) - 1;  
//alert(sdays);  
var fdays = sdays - bdays;  
//alert(fdays);  
}  
else {  
var fdays = sdays - bdays;  
}  
if (smonths < bmonths) {  
smonths = parseInt(smonths) + 12;  
syear = syear - 1;  
var fmonths = smonths - bmonths;  
}  
else {  
var fmonths = smonths - bmonths;  
}  
var fyear = syear - byear; 
var year_to_days = fyear*365; 
var month_to_days = fmonths*30;
var newage = (fyear + ' years ' + fmonths + ' months ' + fdays + ' days');


var newage_year = (year_to_days+month_to_days+fdays);

document.getElementById("age").value = newage;
document.getElementById("age_days").value = newage_year;
// alert(newage);  
// document.getElementById("btnClickedValue").value = newage;
// window.location.href = window.location.href+'?newage='+newage;
}  


</script> 

<script type="text/javascript">
function updateAb(){     

alert('hi');   
}
</script>
<script type="text/javascript">
$(function () 
{
// $('#dob').datepicker({
//     format: 'dd/mm/yyyy',
//     autoclose: true,
//     onSelect: function() {
//              updateAb(selected);
//         }

// });
});
</script>
<!-- all bootstrap buttons classes -->
<!-- 
class="btn btn-sm, btn-lg, "
color - btn-success, btn-primary, btn-default, btn-danger, btn-info, btn-warning
-->



<a href='https://ofa.iiti.ac.in/facrec_che_2023_july_02/layout'></a>

<div class="container">

<div class="row">
<div class="col-xs-12 col-sm-12 col-md-12 well">
    <form class="form-horizontal" action="#" method="post" enctype="multipart/form-data">
      <input type="hidden" name="ci_csrf_token" value="" />
    <fieldset>
        <?php
          //  session_start();
            require "config.php"; // Assuming this file contains your database connection settings

            // Query to retrieve the firstName from the signup table
            $query = "SELECT firstName FROM signup where email = '$email'";

            // Execute the query
            $result = $mysqli->query($query);

            // Check if the query was successful
            if ($result && $result->num_rows > 0) {
                // Fetch the first row as an associative array
                $row = $result->fetch_assoc();
                // Retrieve the firstName
                $firstName = $row['firstName'];
            } else {
                // Handle case where data is not found or query fails
                $firstName = "Unknown";
            }
        ?>
     
        <div class="row">
            <div class="col-md-8">
                              <h4>Welcome : <font color="#025198"><strong><?php echo $firstName; ?></strong></font></h4>
            </div>
             
            <div class="col-md-3">

             <a href='#' class='btn btn-sm btn-info pull-right' onclick="get_username('x&nbsp;y', 1193)" data-target='#passModal' data-toggle='modal'>Change Password</a>


            </div>
            <div class="col-md-1">
              <a href="login.php" class="btn btn-sm btn-success  pull-right">Logout</a>
            </div>
          </div>
        
        
</legend>




      
     
    
<div id="project_show">

<div class="row">
  <div class="col-md-12">

      <label class="col-md-2 control-label" for="adv_num">Advertisement Number *</label>    
      <div class="col-md-4">

      <select id="adv_num" name="adv_num" class="form-control input-md" required="">

          <option value="">Select</option>
          <option  value="IITI/FACREC-CHE/2023/JULY/02">IITI/FACREC-CHE/2023/JULY/02</option>
      </select>
        
      </div>

      <label class="col-md-2 control-label" for="doa">Date of Application </label>  
      <div class="col-md-4">
      <input id="doa" name="doa" type="text" readonly="readonly" value="07/05/2024" placeholder="" class="form-control input-md" required="">
     </div>

      <label class="col-md-2 control-label" for="ref_num">Application Number</label>  
      <div class="col-md-4">

      <input id="ref_num" name="ref_num" type="text" readonly="readonly" value="1715062723" placeholder="" class="form-control input-md" required="">
     </div>

      <label class="col-md-2 control-label" for="post">Post Applied for *</label>  
      <div class="col-md-4">
      <select id="post" name="post" class="form-control input-md" required="">
          <option value="">Select</option>
          <option   value="Professor">Professor</option>
          <option   value="Associate Professor">Associate Professor</option>
          <option   value="Assistant Professor Grade I">Assistant Professor Grade I</option>
          <option   value="Assistant Professor Grade II">Assistant Professor Grade II</option>
      </select>
      </div>

      <label class="col-md-2 control-label" for="dept">Department/School *</label>  
      <div class="col-md-4">
      <select id="dept" name="dept" class="form-control input-md" required="">
          <option value="">Select</option>
          <option  value="Chemical Engineering">Chemical Engineering</option>
      </select>
        
      </div>
</div>
</div>
<hr>


    <!-- Form Name -->
    
      
    <!-- Text input-->
<!-- <h5><font color="#025198"><strong>1. Name:</strong></font></h5>             -->
    <div class="row">
      <div class="col-md-12">
        <div class="panel panel-success">
        <div class="panel-heading">1. Personal Details <small class="pull-right">Upload/Update Photo *</small></div>
          <div class="panel-body" style="height: 390px">
              <div class="col-md-10">
                <div class="row">

                  <span class="col-md-2 control-label" for="fname">First Name *</span>  
                    <div class="col-md-4">
                    <input id="fname" value="" name="fname" type="text" placeholder="First name" class="form-control input-md" maxlength="15" required="">
                  </div>
                

                  <span class="col-md-2 control-label" for="mname">Middle Name</span>  
                    <div class="col-md-4">
                    <input id="mname" value="" name="mname" name="mname" type="text" placeholder="Middle name" class="form-control input-md" maxlength="12">
                    </div>

                  <span class="col-md-2 control-label" for="lname">Last Name *</span>  
                    <div class="col-md-4">
                    <input id="lname" value="" name="lname"name="lname" type="text" placeholder="Last name" class="form-control input-md" maxlength="15" required="">
                    </div>


                  <span class="col-md-2 control-label" for="nationality">Nationality *</span>
                  <div class="col-md-4"> 
                    <select id="nationality" name="nationality" class="form-control input-md" required="">
                      <option value="">Select</option>
                      <!-- <option  value=" India"> India</option> -->
                      <option  value=" Indian"> Indian</option>
                      <!-- <option  value="PIO">PIO</option> -->
                      <option  value="OCI">OCI</option>
                    </select>
                  </div>



                 <!--  <span class="col-md-2 control-label" for="nationality">Nationality </span>  
                  <div class="col-md-4">
                  <input id="nationality" value="" name="nationality" type="text" placeholder="Nationality" class="form-control input-md" maxlength="15" required="">
                  </div> -->

                  <span class="col-md-2 control-label" for="dob">Date of Birth DD/MM/YYYY *</span>  
                  <div class="col-md-4">
                   <!--  <p id="dobdiv"> -->
                  <input id="dob" name="dob" value=""  type="text" placeholder="DD/MM/YYYY" class="form-control input-md" required="" onfocusout = "ageCalculator()">
                <!-- </p> -->

                  <input type="hidden" name="Date" id="Date" value ="31/08/2023" />
                  <!-- <br/> 
                  <input type="hidden" id="btnClickedValue" name="btnClickedValue" value="" />
                  <button type="Button" onclick="ageCalculator()">Calculate</button >   -->

                    
                  </div>

                 <!--  <span class="col-md-2 control-label" for="age">Age</span>  
                  <div class="col-md-4">
                  <input id="age" name="age"  value="" type="text" class="form-control input-md" readonly>

                  <input id="age_days" name="age_days"  value="" type="hidden" class="form-control input-md" readonly>
                  </div> -->

                 
                  <span class="col-md-2 control-label" for="gender">Gender *</span>
                  <div class="col-md-4"> 
                    <select id="gender" name="gender" class="form-control input-md" required="">
                      <option value="">Select</option>
                      <option  value="Male">Male</option>
                      <option  value="Female">Female</option>
                      <option  value="Other">Other</option>
                    </select>
                  </div>


                  <span class="col-md-2 control-label" for="mstatus">Marital Status *</span>
                  <div class="col-md-4"> 
                    <select id="mstatus" name="mstatus" class="form-control input-md" required="">
                      <option value="">Select</option>
                      <option  value="Married">Married</option>
                      <option  value="Unmarried">Unmarried</option>
                      <option  value="Other">Other</option>
                    </select>
                  </div>

                  <span class="col-md-2 control-label" for="cast">Category</span>
                  <div class="col-md-4"> 
                    <input id="cast" name="cast" type="text" placeholder="cast" readonly='readonly' value="UR" class="form-control input-md" required="">
                  </div>

                 <!--  <span class="col-md-2 control-label" for="disability_type">Type of Disability</span>
                  <div class="col-md-4"> 
                    <input id="disability_type" value="" name="disability_type"name="disability_type" type="text" placeholder="Type of Disability" class="form-control input-md" required="">
                  </div>
                     -->

                 <!--  <div class="col-md-6"> 
                  </div> -->
                </div>

                <div class="row">
                  <span class="col-md-2 control-label" for="mstatus">ID Proof *</span>
                  <div class="col-md-4"> 
                   
                      <select id="id_proof" name="id_proof" class="form-control input-md" required="">
                      <option value="">Select</option>
                       <!-- <option value="">Select</option> -->
                      <!--  <option value="AADHAR">AADHAR</option>
                       <option value="PAN-CARD">PAN-CARD</option>
                       <option value="DRIVING-LICENSE">DRIVING-LICENSE</option>
                       <option value="PASSPORT">PASSPORT</option>
                       <option value="OTHER">OTHER</option> -->


                      <option  value="AADHAR">AADHAR</option>
                      <option  value="PAN-CARD">PAN-CARD</option>
                      <option  value="DRIVING-LICENSE">
                      DRIVING-LICENSE</option>
                      <option  value="VOTER ID">VOTER ID</option>
                      <option  value="PASSPORT">PASSPORT</option>
                      <option  value="RATION CARD">RATION CARD</option>
                      
                      <option  value="OTHERS">OTHERS</option>
                    </select>
                  </div>

                 
                  
                                        <span class="col-md-2 control-label" for="cast">Upload ID Proof *</span>
                     <div class="col-md-4"> 
                    <input id="id_card_file" name="userfile2" type="file" class="form-control input-md" required="">
                  </div>
                    
                    <span class="col-md-2 control-label" for="father_name">Father's Name *</span>  
                      <div class="col-md-4">
                      <input id="father_name" value="" name="father_name"name="father_name" type="text" placeholder="Father's Name" class="form-control input-md" maxlength="30" required="">
                      </div>
                  </div>
            </div>

        <div class="col-md-2 pull-right">
          
          <img src="https://ofa.iiti.ac.in/facrec_che_2023_july_02/images/no-photo.png" class="thumbnail pull-right" height="150" width="130" />
          <input id="photo" name="userfile" type="file" class="form-control input-md" required="">
          <strong>Please upload your recent photo <font color="red">( <1 MB) in JPG | JPEG format</font> only.</strong>
         
        </div>
        </div>
      </div>
    </div>
  </div>




<div class="row">
<div class="col-md-12">
<div class="panel panel-success">
<!-- <div class="panel-heading">2. Address *</div> -->
<div class="panel-body">
    <div class="row">
      <div class="col-md-6">
        <span class="control-label" for="cadd">Correspondence Address </span>
        <br />
        <br />
       <textarea style="height:40px" placeholder="Street" class="form-control input-md" name="cadd" maxlength="200" required=""></textarea>

       <textarea style="height:40" placeholder="City" class="form-control input-md" name="cadd1" maxlength="200" required=""></textarea>

       <textarea style="height:40" placeholder="State" class="form-control input-md" name="cadd2" maxlength="200" required=""></textarea>

       <textarea style="height:40" placeholder="Country" class="form-control input-md" name="cadd3" maxlength="200" required=""></textarea>

       <textarea style="height:40;" placeholder="PIN/ZIP" class="form-control input-md" name="cadd4" maxlength="6" required=""></textarea>


      </div>


      <div class="col-md-6">
        <span class="control-label" for="padd">Permanent Address </span>
        <br />
        <br />
       <textarea style="height:40px" placeholder="Street" class="form-control input-md" name="padd" maxlength="200" required=""></textarea>

       <textarea style="height:40" placeholder="City" class="form-control input-md" name="padd1" maxlength="200" required=""></textarea>

       <textarea style="height:40" placeholder="State" class="form-control input-md" name="padd2" maxlength="200" required=""></textarea>

       <textarea style="height:40" placeholder="Country" class="form-control input-md" name="padd3" maxlength="200" required=""></textarea>


       <textarea style="height:40;" placeholder="PIN/ZIP" class="form-control input-md" name="padd4" maxlength="6" required=""></textarea>

    
      </div>

    </div>

      
    </div>
</div>
</div>
</div>

<div class="row">
<div class="col-md-12">
<div class="panel panel-success">
<!-- <div class="panel-heading">3. Contact Details (with STD/ISD code)</div> -->
<div class="panel-body">
  <span class="col-md-2 control-label" for="mobile">Mobile *</span>  
  <div class="col-md-4">
  <input id="mobile" value="" name="mobile" type="text" placeholder="Mobile" class="form-control input-md" required="" maxlength="20">
  </div>

  

 <span class="col-md-2 control-label" for="email">Email</span>  
<div class="col-md-4">
    <input id="email" name="email" type="text" placeholder="email" readonly='readonly' value="<?php echo $email; ?>" class="form-control input-md" required="">
</div>

  <span class="col-md-2 control-label" for="mobile_2">Alternate Mobile </span>  
  <div class="col-md-4">
  <input id="mobile_2" value="" name="mobile_2" type="text" placeholder="Alternate Mobile " class="form-control input-md" maxlength="20">
  </div>

  <span class="col-md-2 control-label" for="email_2">Alternate Email </span>  
  <div class="col-md-4">
  <input id="email_2" value="" name="email_2" type="email" placeholder="Alternate Email" class="form-control input-md">
  </div> 
  
 
  <span class="col-md-2 control-label" for="landline">Landline Number</span>  
  <div class="col-md-4">
  <input id="landline" value="" name="landline" type="text" placeholder="Landline Number" class="form-control input-md" maxlength="20">
  </div> 
  
  

</div>
</div>
</div>
</div>

<div class="form-group">

<div class="col-md-12">
<button id="submit" type="submit" name="submit" value="Submit" class="btn btn-success pull-right">SAVE</button>
<strong class=" pull-right" style="color: green;"><a href='Page2.php' class="btn btn-sm btn-success"> Next</a></strong>
</div>

</div>

<!-- add the div for hide -->
</div>
</div>

</fieldset>
</form>


</div>
</div>


<div id="passModal" class="modal fade" role="dialog">
<form action="https://ofa.iiti.ac.in/facrec_che_2023_july_02/facultypanel/change_pass" method="post">
<div class="modal-dialog">
<div class="modal-content">
    <div class="modal-header">
        <h2>Change Password</h2>

    </div>
    <div class="modal-body">
        <h3>Change Password For : <font color="#3377a0"><strong id="username_mod"></strong></font></h3>
        <input type="hidden" name="fid" id="fid" value="" />
        <div class="form-group">
            <label>Current Password</label>
            <input type="password" name="cr_password" placeholder="Current Password" class="form-control"/>
        </div>
        <div class="form-group">
            <label>New Password</label>
            <input type="password" name="n_password" placeholder="New Password" class="form-control"/>
        </div>
        <div class="form-group">
            <label>Confirm New Password</label>
            <input type="password" name="cn_password" placeholder="New Confirm Password" class="form-control"/>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" name="submit" value="Submit" class="btn btn-info" >Submit</button>
        <button class="btn btn-danger" data-dismiss="modal">Close</button>
    </div>
</div>
</div>
</form>
</div>


<script type="text/javascript">
  $(document).ready(function(){

    var show_status = '';
    if(show_status==1){
      show1();
    }

  });
function get_username(u, fid)
{
document.getElementById("username_mod").innerHTML=u;
// document.getElementById("fname").value=u;
document.getElementById("fid").value=fid;
}
// function form_submit(a, b)
// {
//     window.location="https://ofa.iiti.ac.in/facrec_che_2023_july_02/news/change/"+a+"/"+b;
// }
</script>


<script type="text/javascript">
   function show_none()
   {
   // alert("Hello! I am an alert box!!");
   document.getElementById('project_show').style.display ='none';
   }

   function show1()
   {
   // alert("Hello! I am an alert box!!");
   document.getElementById('project_show').style.display = 'block';
   }
</script>

<div id="footer"></div>
</body>
</html>

<script type="text/javascript">
	
	function blinker() {
	    $('.blink_me').fadeOut(500);
	    $('.blink_me').fadeIn(500);
	}

	setInterval(blinker, 1000);
</script>