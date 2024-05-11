<?php
session_start();
$email = $_SESSION['email'];

require "config.php";
require('fpdf186/fpdf.php');


if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Retrieve form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve email from form data
    //$email = $_POST['email'];

    // PhD Details
    $college_phd = $_POST['college_phd'];
    $stream = $_POST['stream'];
    $supervisor = $_POST['supervisor'];
    $yoj_phd = $_POST['yoj_phd'];
    $dod_phd = $_POST['dod_phd'];
    $doa_phd = $_POST['doa_phd'];
    $phd_title = $_POST['phd_title'];

    // PG Details
    $pg_degree = $_POST['pg_degree'];
    $pg_college = $_POST['pg_college'];
    $pg_subjects = $_POST['pg_subjects'];
    $pg_yoj = $_POST['pg_yoj'];
    $pg_yog = $_POST['pg_yog'];
    $pg_duration = $_POST['pg_duration'];
    $pg_perce = $_POST['pg_perce'];
    $pg_rank = $_POST['pg_rank'];

    // UG Details
    $ug_degree = $_POST['ug_degree'];
    $ug_college = $_POST['ug_college'];
    $ug_subjects = $_POST['ug_subjects'];
    $ug_yoj = $_POST['ug_yoj'];
    $ug_yog = $_POST['ug_yog'];
    $ug_duration = $_POST['ug_duration'];
    $ug_perce = $_POST['ug_perce'];
    $ug_rank = $_POST['ug_rank'];

    // School Details
    $hsc_ssc12 = $_POST['hsc_ssc12'];
    $school12 = $_POST['school12'];
    $passing_year12 = $_POST['passing_year12'];
    $s_perce12 = $_POST['s_perce12'];
    $s_rank12 = $_POST['s_rank12'];
    $hsc_ssc10 = $_POST['hsc_ssc10'];
    $school10 = $_POST['school10'];
    $passing_year10 = $_POST['passing_year10'];
    $s_perce10 = $_POST['s_perce10'];
    $s_rank10 = $_POST['s_rank10'];

    // Prepare and execute SQL statements for each table
    // PhD Details
    $sql_phd = "INSERT INTO phddetails (email, college_phd, stream, supervisor, yoj_phd, dod_phd, doa_phd, phd_title)
                VALUES ('$email', '$college_phd', '$stream', '$supervisor', '$yoj_phd', '$dod_phd', '$doa_phd', '$phd_title')";

    // PG Details
    $sql_pg = "INSERT INTO pgdetails (email, pg_degree, pg_college, pg_subjects, pg_yoj, pg_yog, pg_duration, pg_perce, pg_rank)
                VALUES ('$email', '$pg_degree', '$pg_college', '$pg_subjects', '$pg_yoj', '$pg_yog', '$pg_duration', '$pg_perce', '$pg_rank')";

    // UG Details
    $sql_ug = "INSERT INTO ugdetails (email, ug_degree, ug_college, ug_subjects, ug_yoj, ug_yog, ug_duration, ug_perce, ug_rank)
                VALUES ('$email', '$ug_degree', '$ug_college', '$ug_subjects', '$ug_yoj', '$ug_yog', '$ug_duration', '$ug_perce', '$ug_rank')";

    // School Details
    $sql_school = "INSERT INTO schooldetails (email, hsc_ssc12, school12, passing_year12, s_perce12, s_rank12, hsc_ssc10, school10, passing_year10, s_perce10, s_rank10)
                    VALUES ('$email', '$hsc_ssc12', '$school12', '$passing_year12', '$s_perce12', '$s_rank12', '$hsc_ssc10', '$school10', '$passing_year10', '$s_perce10', '$s_rank10')";

    // Execute SQL statements
    $result_phd = $mysqli->query($sql_phd);
    $result_pg = $mysqli->query($sql_pg);
    $result_ug = $mysqli->query($sql_ug);
    $result_school = $mysqli->query($sql_school);

    if ($result_phd && $result_pg && $result_ug && $result_school) {
        echo "Data inserted successfully";
    } else {
        echo "Error: " . $mysqli->error;
    }
} else {
    echo "Form not submitted!";
}

$pdf = new FPDF();
$pdf->addPage();

// Educational details table
$pdf->SetFont('Arial', 'B', 15);
$pdf->Cell(80);
$pdf->Cell(30, 10, 'Educational Details', 0, 1, 'C');
$pdf->Ln(10);
$query = "SELECT * FROM phddetails WHERE email = '$email'";
$result = $mysqli->query($query);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'PhD Details', 0, 1);
$pdf->Ln();
                 $pdf->SetFont('Arial', '', 12);
                $pdf->Cell(0, 10, 'University/Institute: ' . $row['college_phd'], 0, 1);
                $pdf->Cell(0, 10, 'Department: ' . $row['stream'], 0, 1);
                $pdf->Cell(0, 10, 'Name of Ph. D. Supervisor: ' . $row['supervisor'], 0, 1);
                $pdf->Cell(0, 10, 'Year  of Joining: ' . $row['yoj_phd'], 0, 1);
                $pdf->Cell(0, 10, 'Date of successful thesis Defence: ' . $row['dod_phd'], 0, 1);
                $pdf->Cell(0, 10, 'Date of Award: ' . $row['doa_phd'], 0, 1);
                $pdf->Cell(0, 10, 'Title of Ph. D. Thesis: ' . $row['phd_title'], 0, 1);
            }
          }
 $pdf->Ln();
$query1 = "SELECT * FROM pgdetails WHERE email = '$email'";
$result1 = $mysqli->query($query1);

        if ($result1->num_rows > 0) {
            while ($row = $result1->fetch_assoc()) {

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'PG Details', 0, 1);
$pdf->Ln();
                 $pdf->SetFont('Arial', '', 12);
                 $pdf->Cell(0, 10, 'Degree: ' . $row['pg_degree'], 0, 1);
                $pdf->Cell(0, 10, 'University/Institute: ' . $row['pg_college'], 0, 1);
                $pdf->Cell(0, 10, 'Subjects: ' . $row['pg_subjects'], 0, 1);
                $pdf->Cell(0, 10, 'Year of joining: ' . $row['pg_yoj'], 0, 1);
                $pdf->Cell(0, 10, 'Year  of graduation: ' . $row['pg_yog'], 0, 1);
                $pdf->Cell(0, 10, 'Duration (in years): ' . $row['pg_duration'], 0, 1);
                $pdf->Cell(0, 10, 'Percentage/CGPA: ' . $row['pg_perce'], 0, 1);
                $pdf->Cell(0, 10, 'Division/Class: ' . $row['pg_rank'], 0, 1);
            }
          }
$pdf->Ln();
$query2 = "SELECT * FROM ugdetails WHERE email = '$email'";
$result2 = $mysqli->query($query2);

        if ($result2->num_rows > 0) {
            while ($row = $result2->fetch_assoc()) {

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'UG Details', 0, 1);
$pdf->Ln();
                 $pdf->SetFont('Arial', '', 12);
                 $pdf->Cell(0, 10, 'Degree: ' . $row['ug_degree'], 0, 1);
                $pdf->Cell(0, 10, 'University/Institute: ' . $row['ug_college'], 0, 1);
                $pdf->Cell(0, 10, 'Subjects: ' . $row['ug_subjects'], 0, 1);
                $pdf->Cell(0, 10, 'Year of joining: ' . $row['ug_yoj'], 0, 1);
                $pdf->Cell(0, 10, 'Year  of graduation: ' . $row['ug_yog'], 0, 1);
                $pdf->Cell(0, 10, 'Duration (in years): ' . $row['ug_duration'], 0, 1);
                $pdf->Cell(0, 10, 'Percentage/CGPA: ' . $row['ug_perce'], 0, 1);
                $pdf->Cell(0, 10, 'Division/Class: ' . $row['ug_rank'], 0, 1);
            }
          }   

$pdf->Ln();
$query3 = "SELECT * FROM schooldetails WHERE email = '$email'";
$result3 = $mysqli->query($query3);

        if ($result3->num_rows > 0) {
            while ($row = $result3->fetch_assoc()) {

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'School Details(12th/HSC/Diploma)', 0, 1);
$pdf->Ln();
                 $pdf->SetFont('Arial', '', 12);
                 $pdf->Cell(0, 10, '12th/HSC/Diploma: ' . $row['hsc_ssc12'], 0, 1);
                $pdf->Cell(0, 10, 'School: ' . $row['school12'], 0, 1);
                $pdf->Cell(0, 10, 'Year  of passing: ' . $row['passing_year12'], 0, 1);
                $pdf->Cell(0, 10, 'Percentage/CGPA: ' . $row['s_perce12'], 0, 1);
                $pdf->Cell(0, 10, 'Division/Class: ' . $row['s_rank12'], 0, 1);
$pdf->Ln();
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'School Details(10th)', 0, 1);
$pdf->Ln();
                 $pdf->SetFont('Arial', '', 12);
                 $pdf->Cell(0, 10, '10th: ' . $row['hsc_ssc10'], 0, 1);
                $pdf->Cell(0, 10, 'School: ' . $row['school10'], 0, 1);
                $pdf->Cell(0, 10, 'Year  of passing: ' . $row['passing_year10'], 0, 1);
                $pdf->Cell(0, 10, 'Percentage/CGPA: ' . $row['s_perce10'], 0, 1);
                $pdf->Cell(0, 10, 'Division/Class: ' . $row['s_rank10'], 0, 1);

            }
          } 
          // Output PDF to file
                $file_path = 'pdf_output/output2.pdf';
                $pdf->Output($file_path, 'F');
                 $pdf->Ln();
// Close connection
$mysqli->close();
?>




<html>
<head>
	<title>Academic Details</title>
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


<script type="text/javascript">
var tr="";
var counter_acde=4;
  $(document).ready(function(){
    $("#add_more_acde").click(function(){
        create_tr();
        create_input('add_degree[]', 'Degree','add_degree'+counter_acde, 'acde', counter_acde, 'acde');
        create_input('add_college[]', 'College', 'add_college'+counter_acde,'acde', counter_acde, 'acde');
        create_input('add_subjects[]', 'Subjects', 'add_subjects'+counter_acde,'acde', counter_acde, 'acde');
        create_input('add_yoj[]', 'Year Of Joining', 'add_yoj'+counter_acde,'acde', counter_acde, 'acde');
        create_input('add_yog[]', 'Year Of Graduation','add_yog'+counter_acde, 'acde', counter_acde, 'acde');
        create_input('add_duration[]', 'Duration','add_duration'+counter_acde, 'acde', counter_acde, 'acde');
        create_input('add_perce[]', 'Percentage','add_perce'+counter_acde, 'acde', counter_acde, 'acde');
        create_input('add_rank[]', 'Rank', 'add_rank'+counter_acde,'acde', counter_acde,'acde',true);
        counter_acde++;
        return false;
    });
    
  });

  function create_tr()
  {
    tr=document.createElement("tr");
  }
  function for_date_picker(obj)
  {
    obj.setAttribute("data-provide", "datepicker");
    obj.className += " datepicker";
    return obj;

  }
  function create_input(t_name, place_value, id, tbody_id, counter, remove_name, btn=false, datepicker_set=false, length=80)
  {
    var input=document.createElement("input");
    input.setAttribute("type", "text");
    input.setAttribute("name", t_name);
    input.setAttribute("id", id);
    input.setAttribute("placeholder", place_value);
    input.setAttribute("class", "form-control input-md");
    input.setAttribute("required", "");
    if(datepicker_set==true)
    {
      input=for_date_picker(input);
    }
    var td=document.createElement("td");
    td.appendChild(input);
    if(btn==true)
    {
      // alert();
      var but=document.createElement("button");
      but.setAttribute("class", "close");
      but.setAttribute("onclick", "remove_row('"+remove_name+"','"+counter+"')");
      but.innerHTML="<span style='color:red; font-weight:bold;'>x</span>";
      td.appendChild(but);
    }
    tr.setAttribute("id", "row"+counter);
    tr.appendChild(td);
    document.getElementById(tbody_id).appendChild(tr);
    $('.datepicker').datepicker({
        format: 'dd/mm/yyyy',
        autoclose: true
    });
  } 
  function remove_row(remove_name, n)
  {
    var tab=document.getElementById(remove_name);
    var tr=document.getElementById("row"+n);
    tab.removeChild(tr);
  }
</script>

<script type="text/javascript">
    $(function () {
        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy',
            autoclose: true
        });
    });
</script>
<style type="text/css">
body { padding-top:30px; }
.form-control { margin-bottom: 10px; }
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
.form-control { margin-bottom: 10px; }
label{
  padding: 0 !important;
}
hr{
  border-top: 1px solid #025198 !important;
}
span{
  font-size: 1.2em;
  font-family: 'Oswald', sans-serif!important;
  text-align: left!important;
  padding: 0px 10px 0px 0px!important;
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
.btn-primary {
  padding: 9px;
}
</style>





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
             <legend>
              <div class="row">
                <div class="col-md-10">
                    <h4>Welcome : <font color="#025198"><strong><?php echo $firstName; ?></strong></font></h4>
                </div>
                <div class="col-md-2">
                  <a href="login.php" class="btn btn-sm btn-success  pull-right">Logout</a>
                </div>
              </div>
            
            
    </legend>

<h4 style="text-align:center; font-weight: bold; color: #6739bb;">2. Educational Qualifications</h4>
<div class="row">
    <div class="col-md-12">
      <div class="panel panel-success">
      <div class="panel-heading">(A) Details of PhD *</div>
        <div class="panel-body">
          
          <span class="col-md-2 control-label" for="college_phd">University/Institute</span>  
          <div class="col-md-4">
          <input id="college_phd" value="" name="college_phd" type="text" placeholder="University/Institute" class="form-control input-md" autofocus="" required="">
          </div>

          <span class="col-md-2 control-label" for="stream">Department</span>  
          <div class="col-md-4">
          <input id="stream" value="" name="stream" type="text" placeholder="Department" class="form-control input-md" autofocus="">
          </div> 
          
          <span class="col-md-2 control-label" for="duration_phd">Name of PhD Supervisor</span>  
          <div class="col-md-4">
          <input id="supervisor" name="supervisor" type="text" placeholder="Name of Ph. D. Supervisor" value="" class="form-control input-md" required="">
          </div>

          <span class="col-md-2 control-label" for="yoj_phd">Year of Joining</span>  
          <div class="col-md-4">
          <input id="yoj_phd" value="" name="yoj_phd" type="text" placeholder="Year of Joining" class="form-control input-md" required="">
          </div>
          
          <div class="row">
          <div class="col-md-12">
          <span class="col-md-2 control-label" for="dod_phd">Date of Successful Thesis Defence</span>  
          <div class="col-md-4">
          <input id="dod_phd" name="dod_phd" type="text" data-provide="datepicker" placeholder="Date of Defence" value="" class="form-control input-md datepicker" required="">
          </div>

          <span class="col-md-2 control-label" for="doa_phd">Date of Award</span>  
          <div class="col-md-4">
          <input id="doa_phd" name="doa_phd" type="text" data-provide="datepicker" placeholder="Date of Award" value="" class="form-control input-md datepicker" required="">
          </div>
          </div>
          </div>
          <br />
          <span class="col-md-2 control-label" for="phd_title">Title of PhD Thesis</span>  
          <div class="col-md-10">
          <input id="phd_title" value="" name="phd_title" type="text" placeholder="Title of PhD Thesis" class="form-control input-md" required="">
          </div>

      </div>
    </div>
  </div>
</div>


<div class="row">
    <div class="col-md-12">
      <div class="panel panel-success">
      <div class="panel-heading">(B) Academic Details - M. Tech./ M.E./ PG Details</div>
        <div class="panel-body">
          
          <span class="col-md-2 control-label" for="pg_degree">Degree/Certificate</span>  
          <div class="col-md-4">
          <input id="pg_degree" value="" name="pg_degree" type="text" placeholder="Degree/Certificate" class="form-control input-md" autofocus="">
          </div>

          <span class="col-md-2 control-label" for="pg_college">University/Institute</span>  
          <div class="col-md-4">
          <input id="pg_college" value="" name="pg_college" type="text" placeholder="University/Institute" class="form-control input-md" autofocus="">
          </div> 
          
          <span class="col-md-2 control-label" for="pg_subjects">Branch/Stream</span>  
          <div class="col-md-4">
          <input id="pg_subjects" name="pg_subjects" type="text" placeholder="Branch/Stream" value="" class="form-control input-md" >
          </div>

          <span class="col-md-2 control-label" for="pg_yoj">Year of Joining</span>  
          <div class="col-md-4">
          <input id="pg_yoj" value="" name="pg_yoj" type="text" placeholder="Year of Joining" class="form-control input-md" >
          </div>
          
          <div class="row">
          <div class="col-md-12">
          <span class="col-md-2 control-label" for="pg_yog">Year of Completion</span>  
          <div class="col-md-4">
          <input id="pg_yog" name="pg_yog" type="text" placeholder="Year of Completion" value="" class="form-control input-md" >
          </div>

          <span class="col-md-2 control-label" for="pg_duration">Duration (in years)</span>  
          <div class="col-md-4">
          <input id="pg_duration" name="pg_duration" type="text" placeholder="Duration" value="" class="form-control input-md" >
          </div>

          <span class="col-md-2 control-label" for="pg_perce">Percentage/ CGPA</span>  
          <div class="col-md-4">
          <input id="pg_perce" name="pg_perce" type="text" placeholder="Percentage/ CGPA" value="" class="form-control input-md" >
          </div>

          <span class="col-md-2 control-label" for="pg_rank">Division/Class</span>  
          <div class="col-md-4">
          <input id="pg_rank" name="pg_rank" type="text" placeholder="Division/Class" value="" class="form-control input-md" >
          </div>

          </div>
          </div>
          <br />
          

      </div>
    </div>
  </div>
</div>



<div class="row">
    <div class="col-md-12">
      <div class="panel panel-success">
      <div class="panel-heading">(C) Academic Details - B. Tech /B.E. / UG Details *</div>
        <div class="panel-body">
          
          <span class="col-md-2 control-label" for="ug_degree">Degree/Certificate</span>  
          <div class="col-md-4">
          <input id="ug_degree" value="" name="ug_degree" type="text" placeholder="Degree/Certificate" class="form-control input-md" autofocus="" required="">
          </div>

          <span class="col-md-2 control-label" for="ug_college">University/Institute</span>  
          <div class="col-md-4">
          <input id="ug_college" value="" name="ug_college" type="text" placeholder="University/Institute" class="form-control input-md" autofocus="">
          </div> 
          
          <span class="col-md-2 control-label" for="ug_subjects">Branch/Stream</span>  
          <div class="col-md-4">
          <input id="ug_subjects" name="ug_subjects" type="text" placeholder="Branch/Stream" value="" class="form-control input-md" required="">
          </div>

          <span class="col-md-2 control-label" for="ug_yoj">Year of Joining</span>  
          <div class="col-md-4">
          <input id="ug_yoj" value="" name="ug_yoj" type="text" placeholder="Year of Joining" class="form-control input-md" required="">
          </div>
          
          <div class="row">
          <div class="col-md-12">
          <span class="col-md-2 control-label" for="ug_yog">Year of Completion</span>  
          <div class="col-md-4">
          <input id="ug_yog" name="ug_yog" type="text" placeholder="Year of Completion" value="" class="form-control input-md" required="">
          </div>

          <span class="col-md-2 control-label" for="ug_duration">Duration (in years)</span>  
          <div class="col-md-4">
          <input id="ug_duration" name="ug_duration" type="text" placeholder="Duration" value="" class="form-control input-md" required="">
          </div>

          <span class="col-md-2 control-label" for="ug_perce">Percentage/ CGPA</span>  
          <div class="col-md-4">
          <input id="ug_perce" name="ug_perce" type="text" placeholder="Percentage/ CGPA" value="" class="form-control input-md" required="">
          </div>

          <span class="col-md-2 control-label" for="ug_rank">Division/Class</span>  
          <div class="col-md-4">
          <input id="ug_rank" name="ug_rank" type="text" placeholder="Division/Class" value="" class="form-control input-md" required="">
          </div>

          

          </div>
          </div>
          <br />
          

      </div>
    </div>
  </div>
</div>


<div class="row">
    <div class="col-md-12">
      <div class="panel panel-success">
      <div class="panel-heading">(D) Academic Details - School *
        
      </div>
        <div class="panel-body">
          <table class="table table-bordered">
              
              <tr height="30px">
                <th class="col-md-3"> 10th/12th/HSC/Diploma </th>
                <th class="col-md-3"> School </th>
                <th class="col-md-1"> Year of Passing</th>
                <th class="col-md-2"> Percentage/ Grade </th>
                <th class="col-md-2"> Division/Class </th>
              </tr>
              
              
              <tr height="60px">
                <td class="col-md-2">  
                    <input id="hsc_ssc12" name="hsc_ssc12" type="text" value="12th/HSC/Diploma" placeholder="" class="form-control input-md" readonly="" required=""> 
                </td>

                <td class="col-md-2"> 
                    <input id="school12" name="school12" type="text" value=""  placeholder="School" class="form-control input-md" maxlength="80" required=""> 
                  </td>
                <td class="col-md-2"> 
                  <input id="passing_year12" name="passing_year12" type="text" value=""  placeholder="Passing Year" class="form-control input-md" maxlength="5" required=""> 
                </td>

              

                <td class="col-md-2"> 
                  <input id="s_perce12" name="s_perce12" type="text" value=""  placeholder="Percentage/Grade" class="form-control input-md" maxlength="5" required="">
                </td>

                 
                <td class="col-md-2"> 
                  <input id="s_rank12" name="s_rank12" type="text" value=""  placeholder="Percentage/Grade" class="form-control input-md" maxlength="5" required="">
                </td>


              </tr>
              
              <tr height="60px">
                <td class="col-md-2">  
                    <input id="hsc_ssc10" name="hsc_ssc10" type="text" value="10th" placeholder="" class="form-control input-md" readonly="" required=""> 
                </td>

                <td class="col-md-2"> 
                    <input id="school10" name="school10" type="text" value=""  placeholder="School" class="form-control input-md" maxlength="80" required=""> 
                  </td>
                <td class="col-md-2"> 
                  <input id="passing_year10" name="passing_year10" type="text" value=""  placeholder="Passing Year" class="form-control input-md" maxlength="5" required=""> 
                </td>

              

                <td class="col-md-2"> 
                  <input id="s_perce10" name="s_perce10" type="text" value=""  placeholder="Percentage/Grade" class="form-control input-md" maxlength="5" required="">
                </td>

                 
                <td class="col-md-2"> 
                  <input id="s_rank10" name="s_rank10" type="text" value=""  placeholder="Percentage/Grade" class="form-control input-md" maxlength="5" required="">
                </td>


              </tr>
                            
           
          </table>

      </div>
    </div>
  </div>
</div>
 
<div class="row">
    <div class="col-md-12">
      <div class="panel panel-success">
      <div class="panel-heading">(E) Additional Educational Qualification (If any)
        <button class="btn btn-sm btn-danger" id="add_more_acde">Add More</button>
      </div>
        <div class="panel-body">
          <table class="table table-bordered">
              <tbody id="acde">
              
              <tr height="30px">
                <th class="col-md-2"> Degree/Certificate </th>
                <th class="col-md-2"> University/Institute </th>
                <th class="col-md-2"> Branch/Stream </th>
                <th class="col-md-1"> Year of Joining</th>
                <th class="col-md-1"> Year of Completion </th>
                <th class="col-md-1"> Duration (in years)</th>
                <th class="col-md-3"> Percentage/ CGPA </th>
                <th class="col-md-3"> Division/Class</th>
              </tr>
              
                            
           
            </tbody>
          </table>

      </div>
    </div>
  </div>
</div>


            <!-- Form Name -->



<div class="form-group">
  
  <div class="col-md-1">
    <a href="https://ofa.iiti.ac.in/facrec_che_2023_july_02/facultypanel" class="btn btn-primary pull-left"><i class="glyphicon glyphicon-fast-backward"></i></a>
  </div>

  <div class="col-md-11">
    <button id="submit" type="submit" name="submit" value="Submit" class="btn btn-success pull-right" style="margin-left: 75%;">SAVE</button>
    <strong class=" pull-right" style="color: green;"><a href='Page3.php' class="btn btn-sm btn-success">NEXT</a></strong>
  </div>

    
</div>
          
</fieldset>
</form>

        </div>
    </div>
</div>

<script type="text/javascript">
  function yearcalc()
  { 
    // alert('hi');
    var num1=document.getElementById("yoj").value;
    var num2=document.getElementById("yog").value;

    var duration_year=parseFloat(num2)-parseFloat(num1);
    // alert(duration_year);
    document.getElementById("result_test").value = duration_year ;
   
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