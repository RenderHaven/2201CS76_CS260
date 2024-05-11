<?php
session_start();
$email = $_SESSION['email'];

require "config.php";
require('fpdf186/fpdf.php');
// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Retrieve form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle form data
   // $email = $_POST['email'];
    $research_statement = $_POST['research_statement'];
    $teaching_statement = $_POST['teaching_statement'];
    $rel_in = $_POST['rel_in'];
    $prof_serv = $_POST['prof_serv'];
    $jour_details = $_POST['jour_details'];
    $conf_details = $_POST['conf_details'];

    // Prepare and execute SQL statement
    $sql = "INSERT INTO research (email, research_statement, teaching_statement, rel_in, prof_serv, jour_details, conf_details) 
            VALUES ('$email', '$research_statement', '$teaching_statement', '$rel_in', '$prof_serv', '$jour_details', '$conf_details')";
    if ($mysqli->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $mysqli->error;
    }
}

$pdf = new FPDF();
$pdf->addPage();

// Educational details table
$pdf->SetFont('Arial', 'B', 15);
$pdf->Cell(80);
$pdf->Cell(30, 10, 'Educational Details', 0, 1, 'C');
$pdf->Ln(10);
$query = "SELECT * FROM research WHERE email = '$email'";
$result = $mysqli->query($query);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {

                 $pdf->Cell(0, 10, 'Significant research contribution and future plans : ' . $row['research_statement'], 0, 1);
                $pdf->Cell(0, 10, 'Significant teaching contribution and future plans : ' . $row['teaching_statement'], 0, 1);
                $pdf->Cell(0, 10, 'Any other relevant information : ' . $row['rel_in'], 0, 1);
                $pdf->Cell(0, 10, 'Professional Service : Editorship/Reviewership: ' . $row['prof_serv'], 0, 1);
                $pdf->Cell(0, 10, 'Detailed List of Journal Publications: ' . $row['jour_details'], 0, 1);
                $pdf->Cell(0, 10, 'Detailed List of Conference Publications: ' . $row['conf_details'], 0, 1);
            }
          }
          $file_path = 'pdf_output/output7.pdf';
                $pdf->Output($file_path, 'F');
                
 $pdf->Ln();
// Close connection
$mysqli->close();
?>


<html>
<head>
	<title>Rel Info</title>
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

<script type="text/javascript" src="https://ofa.iiti.ac.in/facrec_che_2023_july_02/ckeditor/ckeditor.js"></script>

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

.Acae_data
{
  font-size: 1.1em;
  font-weight: bold;
  color: #414002;
}
p
{
  padding-top: 10px;
}
</style>

<!-- all bootstrap buttons classes -->
<!-- 
  class="btn btn-sm, btn-lg, "
  color - btn-success, btn-primary, btn-default, btn-danger, btn-info, btn-warning
-->



<a href='https://ofa.iiti.ac.in/facrec_che_2023_july_02/layout'></a>

<div class="container">
  
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 well">
            
              
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
  
<form class="form-horizontal" action="#" method="post" enctype="multipart/form-data">
<input type="hidden" name="ci_csrf_token" value="" />


<div class="row">
      <div class="col-md-12">
        <div class="panel panel-success ">
        <div class="panel-heading">14. Significant research contribution and future plans *<small class="pull-right">(not more than 500 words)</small> <br /><small>(Please provide a Research Statement describing your research plans and one or two specific research projects to be conducted at IIT Indore in 2-3 years time frame)</small></div>
          <div class="panel-body">
            <textarea style="height:150px" placeholder="Significant research contribution and future plans" class="form-control input-md" name="research_statement" maxlength="3500" required=""></textarea>

          <script>
              // CKEDITOR.replace($_POST['conf_details']);
              CKEDITOR.replace('research_statement');

           

              
          </script>
        </div>
      </div>
    </div>

    <div class="col-md-12">
      <div class="panel panel-success ">
      <div class="panel-heading">15. Significant teaching contribution and future plans * <small>(Please list UG/PG courses that you would like to develop and/or teach at IIT Indore)</small> <small class="pull-right"> (not more than 500 words)</small></div>
        <div class="panel-body">
          <textarea style="height:150px" placeholder="Significant teaching contribution and future plans" class="form-control input-md" name="teaching_statement" maxlength="3500" required=""></textarea>

         <script>
             // CKEDITOR.replace($_POST['conf_details']);
             CKEDITOR.replace('teaching_statement');

          

             
         </script>
      </div>
    </div>
    </div>


<div class="col-md-12">
      <div class="panel panel-success ">
      <div class="panel-heading">16. Any other relevant information. <small class="pull-right">(not more than 500 words)</small></div>
        <div class="panel-body">
          <textarea style="height:150px" placeholder="Any other relevant information you may like to furnish" class="form-control input-md" name="rel_in" maxlength="3500"></textarea>

         <script>
             // CKEDITOR.replace($_POST['conf_details']);
             CKEDITOR.replace('rel_in');

          

             
         </script>
      </div>
    </div>
    </div>


    <div class="col-md-12">
          <div class="panel panel-success ">
          <div class="panel-heading">17. Professional Service : Editorship/Reviewership <small class="pull-right">(not more than 500 words)</small></div>
            <div class="panel-body">
              <textarea style="height:150px" placeholder="Professional Service as Reviewer/Editor etc" class="form-control input-md" name="prof_serv" maxlength="3500"></textarea>

              <script>
                  // CKEDITOR.replace($_POST['conf_details']);
                  CKEDITOR.replace('prof_serv');

               

                  
              </script>
            
          </div>
        </div>
        </div>

<div class="col-md-12">
  <div class="panel panel-success ">
  <div class="panel-heading">18. Detailed List of Journal Publications <br />(Including Sr. No., Author's Names, Paper Title, Volume, Issue, Year, Page Nos., Impact Factor (if any), DOI, Status[Published/Accepted] )</div>
    <div class="panel-body">


      <textarea style="height:15px!important" placeholder="Detailed List of Journal Publications" id="jour_details" class="form-control input-md" name="jour_details"></textarea>

      <script>
          // CKEDITOR.replace($_POST['conf_details']);
          CKEDITOR.replace('jour_details');

       

          
      </script>
  </div>
</div>
</div>


<div class="col-md-12">
  <div class="panel panel-success ">
  <div class="panel-heading">19. Detailed List of Conference Publications<br />(Including Sr. No.,  Author's Names, Paper Title, Name of the conference, Year, Page Nos., DOI [If any] )</div>
    <div class="panel-body">
      <textarea style="height:150px" placeholder="Detailed List of Conference Publications" id="conf_details" class="form-control input-md" name="conf_details"></textarea>

      <script>
          CKEDITOR.replace('conf_details');
          
      </script>

  </div>
</div>
</div>



 </div>      
 
<div class="form-group">
<div class="col-md-10">
  <!-- <a href="https://ofa.iiti.ac.in/facrec_che_2023_july_02/acde" class="btn btn-primary pull-left">BACK</a> -->
  <a href="https://ofa.iiti.ac.in/facrec_che_2023_july_02/thesis_course" class="btn btn-primary pull-left"><i class="glyphicon glyphicon-fast-backward"></i></a>


</div>

<div class="col-md-2">

   <button id="submit" type="submit" name="submit" value="Submit" class="btn btn-success pull-right" style="margin-left: 75%;">SAVE</button>
    <strong class=" pull-right" style="color: green;"><a href='Page8.php' class="btn btn-sm btn-success">NEXT</a></strong>

</div>


</div>
 

</div> 
            




</fieldset>
</form>



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