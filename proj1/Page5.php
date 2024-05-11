<?php
// Database connection parameters
session_start();
$email = $_SESSION['email'];
require "config.php";
require('fpdf186/fpdf.php');
// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Function to create input fields

    // Check if form data for Sponsored Projects is submitted
    if (isset($_POST['agency'])) {
        // Retrieve form data for Sponsored Projects
        $agency = $_POST['agency'];
        $title = $_POST['stitle'];
        $amount = $_POST['samount'];
        $period = $_POST['speriod'];
        $role = $_POST['s_role'];
        $status = $_POST['s_status'];

        // Prepare and execute SQL statement for Sponsored Projects
        for ($i = 0; $i < count($agency); $i++) {
            $sql = "INSERT INTO SponsoredProjects (email, agency, title, amount, period, role, status) 
                VALUES ('$email', '$agency[$i]', '$title[$i]', '$amount[$i]', '$period[$i]', '$role[$i]', '$status[$i]')";
            $mysqli->query($sql);
        }
    }

    // Check if form data for Awards is submitted
    if (isset($_POST['award_nature'])) {
        // Retrieve form data for Awards
        $award_nature = $_POST['award_nature'];
        $award_org = $_POST['award_org'];
        $award_year = $_POST['award_year'];

        // Prepare and execute SQL statement for Awards
        for ($i = 0; $i < count($award_nature); $i++) {
            $sql = "INSERT INTO Awards (email, award_nature, award_org, award_year) 
                VALUES ('$email', '$award_nature[$i]', '$award_org[$i]', '$award_year[$i]')";
            $mysqli->query($sql);
        }
    }

    // Check if form data for Professional Training is submitted
    if (isset($_POST['trg'])) {
        // Retrieve form data for Professional Training
        $training_received = $_POST['trg'];
        $organization = $_POST['porg'];
        $year = $_POST['pyear'];
        $duration = $_POST['pduration'];

        // Prepare and execute SQL statement for Professional Training
        for ($i = 0; $i < count($training_received); $i++) {
            $sql = "INSERT INTO ProfessionalTraining (email, training_received, organization, year, duration) 
                VALUES ('$email', '$training_received[$i]', '$organization[$i]', '$year[$i]', '$duration[$i]')";
            $mysqli->query($sql);
        }
    }

    // Check if form data for Memberships is submitted
    if (isset($_POST['mname'])) {
        // Retrieve form data for Memberships
        $name_of_society = $_POST['mname'];
        $membership_status = $_POST['mstatus'];

        // Prepare and execute SQL statement for Memberships
        for ($i = 0; $i < count($name_of_society); $i++) {
            $sql = "INSERT INTO Memberships (email, name_of_society, membership_status) 
                VALUES ('$email', '$name_of_society[$i]', '$membership_status[$i]')";
            $mysqli->query($sql);
        }
    }

    // Check if form data for Consultancy is submitted
    if (isset($_POST['c_org'])) {
        // Retrieve form data for Consultancy
        $organization = $_POST['c_org'];
        $project_title = $_POST['ctitle'];
        $amount = $_POST['camount'];
        $period = $_POST['cperiod'];
        $role = $_POST['c_role'];
        $status = $_POST['c_status'];

        // Prepare and execute SQL statement for Consultancy
        for ($i = 0; $i < count($organization); $i++) {
            $sql = "INSERT INTO Consultancy (email, organization, project_title, amount, period, role, status) 
                VALUES ('$email', '$organization[$i]', '$project_title[$i]', '$amount[$i]', '$period[$i]', '$role[$i]', '$status[$i]')";
            $mysqli->query($sql);
        }
    }

    // After successfully submitting the form data, redirect to the next page
    // You can redirect to the desired page using header function
   // header("Location: Page6.php");
}

$pdf = new FPDF();
$pdf->addPage();

// Educational details table
$pdf->SetFont('Arial', 'B', 15);
$pdf->Cell(80);
$pdf->Cell(30, 10, 'Sponsored Projects ', 0, 1, 'C');
$pdf->Ln(10);
$query = "SELECT * FROM SponsoredProjects  WHERE email = '$email'";
$result = $mysqli->query($query);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {

$pdf->Ln();
                 $pdf->SetFont('Arial', '', 12);
                $pdf->Cell(0, 10, 'Sponsoring Agency : ' . $row['agency'], 0, 1);
                $pdf->Cell(0, 10, 'Title of Project : ' . $row['title'], 0, 1);
                $pdf->Cell(0, 10, 'Sanctioned Amount : ' . $row['amount'], 0, 1);
                $pdf->Cell(0, 10, 'Period : ' . $row['period'], 0, 1);
                $pdf->Cell(0, 10, 'Role : ' . $row['role'], 0, 1);
                $pdf->Cell(0, 10, 'Status : ' . $row['status'], 0, 1);
            }
          }
 $pdf->Ln();
 $pdf->SetFont('Arial', 'B', 15);
$pdf->Cell(80);
$pdf->Cell(30, 10, 'Awards', 0, 1, 'C');
$pdf->Ln(10);
$query1 = "SELECT * FROM Awards WHERE email = '$email'";
$result1 = $mysqli->query($query1);
        if ($result1->num_rows > 0) {
            while ($row = $result1->fetch_assoc()) {

                 $pdf->SetFont('Arial', '', 12);
                 $pdf->Cell(0, 10, 'Name of award : ' . $row['award_nature'], 0, 1);
                $pdf->Cell(0, 10, 'Awarded by : ' . $row['award_org'], 0, 1);
                $pdf->Cell(0, 10, 'Year: ' . $row['award_year'], 0, 1);
            }
          }
$pdf->Ln();
$query2 = "SELECT * FROM ProfessionalTraining WHERE email = '$email'";
$result2 = $mysqli->query($query2);
$pdf->Ln();
 $pdf->SetFont('Arial', 'B', 15);
$pdf->Cell(80);
$pdf->Cell(30, 10, 'Professional Training', 0, 1, 'C');
$pdf->Ln(10);
        if ($result2->num_rows > 0) {
            while ($row = $result2->fetch_assoc()) {

                 $pdf->SetFont('Arial', '', 12);
                 $pdf->Cell(0, 10, 'Type of Training received : ' . $row['training_received'], 0, 1);
                $pdf->Cell(0, 10, 'Organisation : ' . $row['organization'], 0, 1);
                $pdf->Cell(0, 10, 'Year : ' . $row['year'], 0, 1);
                $pdf->Cell(0, 10, 'Duration : ' . $row['duration'], 0, 1);
            }
          }   

$pdf->Ln();
$query3 = "SELECT * FROM Memberships WHERE email = '$email'";
$result3 = $mysqli->query($query3);
$pdf->Ln();
 $pdf->SetFont('Arial', 'B', 15);
$pdf->Cell(80);
$pdf->Cell(30, 10, 'Membership of Professional Societies', 0, 1, 'C');
$pdf->Ln(10);
        if ($result3->num_rows > 0) {
            while ($row = $result3->fetch_assoc()) {

                 $pdf->SetFont('Arial', '', 12);
                 $pdf->Cell(0, 10, 'Name of the Professional Society : ' . $row['name_of_society'], 0, 1);
                $pdf->Cell(0, 10, 'Membership Status (Lifetime/Annual) : ' . $row['membership_status'], 0, 1);
            }
          } 

$pdf->Ln();
$query4 = "SELECT * FROM Consultancy WHERE email = '$email'";
$result4 = $mysqli->query($query4);
$pdf->Ln();
 $pdf->SetFont('Arial', 'B', 15);
$pdf->Cell(80);
$pdf->Cell(30, 10, 'Consultancy Projects', 0, 1, 'C');
$pdf->Ln(10);
        if ($result4->num_rows > 0) {
            while ($row = $result4->fetch_assoc()) {

                 $pdf->SetFont('Arial', '', 12);
                 $pdf->Cell(0, 10, 'Organization : ' . $row['organization'], 0, 1);
                $pdf->Cell(0, 10, 'Title of Project : ' . $row['project_title'], 0, 1);
                $pdf->Cell(0, 10, 'Amount of Grant : ' . $row['amount'], 0, 1);
                $pdf->Cell(0, 10, 'Period : ' . $row['period'], 0, 1);
                $pdf->Cell(0, 10, 'Role : ' . $row['role'], 0, 1);
                $pdf->Cell(0, 10, 'Status: ' . $row['status'], 0, 1);
            }
          }           
          // Output PDF to file
                $file_path = 'pdf_output/output5.pdf';
                $pdf->Output($file_path, 'F');
                 $pdf->Ln();

// Close connection
$mysqli->close();
?>





<html>
<head>
	<title>Academic Industrial Experience</title>
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
<script type="text/javascript">
             
            $(function () {
                $('.datepicker').datepicker({
                    format: 'dd/mm/yyyy',
                    autoclose: true
                });
            });
</script>

<script type="text/javascript">
var tr="";
var counter_s_proj=1;
var counter_award=1;
var counter_prof_trg=1;
var counter_membership=1;
var counter_consultancy=1;

  $(document).ready(function(){
  

$("#add_more_s_proj").click(function(){
        create_tr();
        create_serial('s_proj');
        create_input('agency[]', 'Sponsoring Agency','agency'+counter_s_proj, 's_proj',counter_s_proj, 's_proj');
        create_input('stitle[]', 'Title of Project', 'stitle'+counter_s_proj,'s_proj',counter_s_proj, 's_proj');
        create_input('samount[]', 'Amount of grant', 'samount'+counter_s_proj,'s_proj',counter_s_proj, 's_proj');
        create_input('speriod[]', 'Period', 'speriod'+counter_s_proj,'s_proj',counter_s_proj, 's_proj');
        create_input('s_role[]', 'Role', 's_role'+counter_s_proj,'s_proj',counter_s_proj, 's_proj',false,true);
        create_input('s_status[]', 'Status', 's_status'+counter_s_proj,'s_proj',counter_s_proj, 's_proj',true);
        counter_s_proj++;
        return false;
  });
  
  $("#add_more_award").click(function(){
          create_tr();
          create_serial('award');
          create_input('award_nature[]', 'Name of Award','award_nature'+counter_award, 'award',counter_award, 'award');
          create_input('award_org[]', 'Granting body/Organization', 'award_org'+counter_award,'award',counter_award, 'award');
          create_input('award_year[]', 'Year', 'award_year'+counter_award,'award',counter_award, 'award',true);
          counter_award++;
          return false;
    });

  $("#add_more_prog_trg").click(function(){
          create_tr();
          create_serial('prof_trg');
          create_input('trg[]', 'Taining Received','trg'+counter_prof_trg, 'prof_trg',counter_prof_trg, 'prof_trg');
          create_input('porg[]', 'Organization', 'porg'+counter_prof_trg,'prof_trg',counter_prof_trg, 'prof_trg');
          create_input('pyear[]', 'Year', 'pyear'+counter_prof_trg,'prof_trg',counter_prof_trg, 'prof_trg');
          create_input('pduration[]', 'Duration', 'pduration'+counter_prof_trg,'prof_trg',counter_prof_trg, 'prof_trg',true);
          counter_prof_trg++;
          return false;
    });

  $("#add_membership").click(function(){
          create_tr();
          create_serial('membership');
          create_input('mname[]', 'Name of the Professional Society','mname'+counter_membership, 'membership',counter_membership, 'membership');
          create_input('mstatus[]', 'Membership Status (Lifetime/Annual)', 'mstatus'+counter_membership,'membership',counter_membership, 'membership',true);
          counter_membership++;
          return false;
    });

  $("#add_consultancy").click(function(){
          create_tr();
          create_serial('consultancy');
          create_input('c_org[]', 'Organization','c_org'+counter_consultancy, 'consultancy',counter_consultancy, 'consultancy');
          create_input('ctitle[]', 'Title of Project','ctitle'+counter_consultancy, 'consultancy',counter_consultancy, 'consultancy');
          create_input('camount[]', 'Amount of grant','camount'+counter_consultancy, 'consultancy',counter_consultancy, 'consultancy');
          create_input('cperiod[]', 'Period','cperiod'+counter_consultancy, 'consultancy',counter_consultancy, 'consultancy');

          create_input('c_role[]', 'Role', 'c_role'+counter_consultancy,'consultancy',counter_consultancy, 'consultancy',false,true);
          create_input('c_status[]', 'Status', 'c_status'+counter_consultancy,'consultancy',counter_consultancy, 'consultancy',true);
          counter_consultancy++;
          return false;
    });


});
  function create_select()
  {
    
  }
  function create_tr()
  {
    tr=document.createElement("tr");
  }
  function create_serial(tbody_id)
  {
    //console.log(tbody_id);
    var td=document.createElement("td");
    // var x=0;
     var x = document.getElementById(tbody_id).rows.length;
    // if(document.getElementById(tbody_id).rows)
    // {
    // }
    td.innerHTML=x;
     tr.appendChild(td);
  }
   function for_date_picker(obj)
  {
    obj.setAttribute("data-provide", "datepicker");
    obj.className += " datepicker";
    return obj;

  }
  function deleterow(e){
    var rowid=$(e).attr("data-id");
    var textbox=$("#id"+rowid).val();
    $.ajax({
            type: "POST",
            url  : "https://ofa.iiti.ac.in/facrec_che_2023_july_02/Acd_ind/deleterow/",
            data: {id: textbox},
                success: function(result) { 
                if(result.status=="OK"){
                $('.row_'+rowid).remove();
                            //remove_row('award',rowid, 'award');
                }
                   
                }});

   
    }
  function create_input(t_name, place_value, id, tbody_id, counter, remove_name, btn=false, select=false, datepicker_set=false)
  {
    //console.log(counter);
    if(select==false)
    {

      var input=document.createElement("input");
      input.setAttribute("type", "text");
      input.setAttribute("name", t_name);
      input.setAttribute("id", id);
      input.setAttribute("placeholder", place_value);
      input.setAttribute("class", "form-control input-md");
      input.setAttribute("required", "");
      var td=document.createElement("td");
      td.appendChild(input);
    }
    if(select==true)
    {

      var sel=document.createElement("select");
      sel.setAttribute("name", t_name);
      sel.setAttribute("id", id);
      sel.setAttribute("class", "form-control input-md");
      sel.innerHTML+="<option>Select</option>";
      sel.innerHTML+="<option value='Principal Investigator'>Principal Investigator</option>";
      sel.innerHTML+="<option value='Co-investigator'>Co-investigator</option>";
      // sel.innerHTML+="<option value='in_preparation'>In-Preparation</option>";
      var td=document.createElement("td");
      td.appendChild(sel);
    }
    if(datepicker_set==true)
    {
      input=for_date_picker(input);
    }
    if(btn==true)
    {
      // alert();
      var but=document.createElement("button");
      but.setAttribute("class", "close");
      but.setAttribute("onclick", "remove_row('"+remove_name+"','"+counter+"', '"+tbody_id+"')");
      but.innerHTML="x";
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
  function remove_row(remove_name, n, tbody_id)
  {
    var tab=document.getElementById(remove_name);
    var tr=document.getElementById("row"+n);
    tab.removeChild(tr);
    var x = document.getElementById(tbody_id).rows.length;
    for(var i=0; i<=x; i++)
    {
      $("#"+tbody_id).find("tr:eq("+i+") td:first").text(i);
      
    }
    
  }
</script>




<a href='https://ofa.iiti.ac.in/facrec_che_2023_july_02/layout'></a>

<div class="container">
  
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-8 well">
            <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
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
              <input type="hidden" name="ci_csrf_token" value="" />
             
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



<!-- Membership of Professional Societies -->

<h4 style="text-align:center; font-weight: bold; color: #6739bb;">9. Membership of Professional Societies </h4>

<div class="row">
<div class="col-md-12">
<div class="panel panel-success">
<div class="panel-heading">Fill the Details  &nbsp;&nbsp;&nbsp;<button class="btn btn-sm btn-danger" id="add_membership">Add Details</button></div>
  <div class="panel-body">

        <table class="table table-bordered">
            <tbody id="membership">
            
            <tr height="30px">
              <th class="col-md-1"> S. No.</th>
              <th class="col-md-3"> Name of the Professional Society </th>
              <th class="col-md-3"> Membership Status (Lifetime/Annual)</th>
              
            </tr>


                      </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Professional Training -->

<h4 style="text-align:center; font-weight: bold; color: #6739bb;">10. Professional Training </h4>

<div class="row">
  <div class="col-md-12">
    <div class="panel panel-success">
    <div class="panel-heading">Fill the Details  &nbsp;&nbsp;&nbsp;<button class="btn btn-sm btn-danger" id="add_more_prog_trg">Add Details</button></div>
      <div class="panel-body">

            <table class="table table-bordered">
                <tbody id="prof_trg">
                
                <tr height="30px">
                  <th class="col-md-1"> S. No.</th>
                  <th class="col-md-3"> Type of Training Received </th>
                  <th class="col-md-3"> Organisation</th>
                  <th class="col-md-2"> Year</th>
                  <th class="col-md-2"> Duration (in years & months)</th>
                  
                </tr>


                              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

<!-- Award(s) and Recognition(s) -->

<h4 style="text-align:center; font-weight: bold; color: #6739bb;">11. Award(s) and Recognition(s)</h4>
<div class="row">
<div class="col-md-12">
<div class="panel panel-success">
<div class="panel-heading">Fill the Details  &nbsp;&nbsp;&nbsp;<button class="btn btn-sm btn-danger" id="add_more_award">Add Details</button></div>
  <div class="panel-body">

        <table class="table table-bordered">
            <tbody id="award">
            
            <tr height="30px">
              <th class="col-md-1"> S. No.</th>
              <th class="col-md-3"> Name of Award </th>
              <th class="col-md-3"> Awarded By</th>
              <th class="col-md-2"> Year</th>
              
            </tr>


                      </tbody>
        </table>
      </div>
    </div>
  </div>
</div>


<h4 style="text-align:center; font-weight: bold; color: #6739bb;">12. Sponsored Projects/ Consultancy Details</h4>
<!-- sponsored projects  -->
<div class="row">
    <div class="col-md-12">
      <div class="panel panel-success">
      <div class="panel-heading">(A) Sponsored Projects &nbsp;&nbsp;&nbsp;<button class="btn btn-sm btn-danger" id="add_more_s_proj">Add Details</button></div>
        <div class="panel-body">

              <table class="table table-bordered">
                  <tbody id="s_proj">
                  
                  <tr height="30px">
                    <th class="col-md-1"> S. No.</th>
                    <th class="col-md-2"> Sponsoring Agency </th>
                    <th class="col-md-2"> Title of Project</th>
                    <th class="col-md-2"> Sanctioned Amount (&#8377) </th>
                    <th class="col-md-1"> Period</th>
                    <th class="col-md-2"> Role </th>
                    <th class="col-md-2"> Status (Completed/On-going)</th>
                    
                  </tr>


                                  </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

     <!-- Consultancy Details -->
             <div class="row">
                 <div class="col-md-12">
                   <div class="panel panel-success">
                   <div class="panel-heading">(B) Consultancy Projects  &nbsp;&nbsp;&nbsp;<button class="btn btn-sm btn-danger" id="add_consultancy">Add Details</button></div>
                     <div class="panel-body">

                           <table class="table table-bordered">
                               <tbody id="consultancy">
                               
                               <tr height="30px">
                                 <th class="col-md-1"> S. No.</th>
                                 <th class="col-md-3"> Organization </th>
                                 <th class="col-md-2"> Title of Project</th>
                                 <th class="col-md-2"> Amount of Grant</th>
                                 <th class="col-md-1"> Period</th>
                                 <th class="col-md-2"> Role</th>
                                 <th class="col-md-2"> Status</th>
                                 
                               </tr>


                                                            </tbody>
                           </table>
                         </div>
                       </div>
                     </div>

                   </div>
 
    


      




            <!-- Button -->

            <div class="form-group">
              
              <div class="col-md-1">
                <a href="https://ofa.iiti.ac.in/facrec_che_2023_july_02/publish" class="btn btn-primary pull-left"><i class="glyphicon glyphicon-fast-backward"></i></a>
              </div>

              <div class="col-md-11">
                <button id="submit" type="submit" name="submit" value="Submit" class="btn btn-success pull-right" style="margin-left: 75%;">SAVE</button>
    <strong class=" pull-right" style="color: green;"><a href='Page6.php' class="btn btn-sm btn-success">NEXT</a></strong>
                
              </div>
              
            </div>

            <!-- <div class="form-group">
              <label class="col-md-5 control-label" for="submit"></label>
              <div class="col-md-4">
                <button id="submit" type="submit" name="submit" value="Submit" class="btn btn-primary">SUBMIT</button>

              </div>
            </div> -->

            </fieldset>
            </form>
            
            

        </div>
    </div>
</div>

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