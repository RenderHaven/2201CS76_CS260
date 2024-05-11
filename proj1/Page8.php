<?php
// Database connection parameters
session_start();
$email = $_SESSION['email'];
require "config.php";
require('fpdf186/fpdf.php');

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Handle form submission for referee information
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ref_name'])) {
    // Insert referee data into database
    $referee_query = "INSERT INTO referee (email, referee_name, position, association_with_referee, institution, emailR, contact_no) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $referee_stmt = $mysqli->prepare($referee_query);
    $referee_stmt->bind_param("sssssss", $email, $referee_name, $position, $association_with_referee, $institution, $emailR, $contact_no);

    // Loop through referee details and insert into database
    foreach ($_POST['ref_name'] as $key => $value) {
        $referee_name = $_POST['ref_name'][$key];
        $position = $_POST['position'][$key];
        $association_with_referee = $_POST['association_referee'][$key];
        $institution = $_POST['org'][$key];
        $emailR = $_POST['emailR'][$key]; // Using session email as emailR
        $contact_no = $_POST['phone'][$key];

        $referee_stmt->execute();
    }

    $referee_stmt->close();
}

// Handle file uploads for documents
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection parameters
    $servername = "localhost:3308";
    $username = "root";
    $password = "";
    $dbname = "facultylogin";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Function to sanitize inputs
    function sanitize_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    // Array to store file names
    $fileNames = array();

    // File upload directory
    $target_dir = "uploads/";

    // File upload errors flag
    $uploadErrors = false;

    // File input names and database column names mapping
    $fileInputs = array(
        "userfile7" => "Reprints of 5 Best Research Papers",
        "userfile" => "PHD Certificate",
        "userfile1" => "PG Documents",
        "userfile2" => "UG Documents",
        "userfile3" => "12th/HSC/Diploma Documents",
        "userfile4" => "10th/SSC Documents",
        "userfile9" => "Pay Slip",
        "userfile10" => "NOC or Undertaking",
        "userfile8" => "Post phd Experience Certificate/All Experience Certificates/ Last Pay slip",
        "userfile6" => "Miscellaneous Document",
        "userfile5" => "Signature"
    );

    // Loop through each file input
    foreach ($fileInputs as $inputName => $fileType) {
        if (isset($_FILES[$inputName])) {
            // Check if file upload error occurred
            if ($_FILES[$inputName]["error"] == UPLOAD_ERR_OK) {
                // Sanitize file name
                $fileName = sanitize_input($_FILES[$inputName]["name"]);
                // Generate unique file name
                $uniqueFileName = uniqid() . "_" . $fileName;
                // Target file path
                $target_file = $target_dir . $uniqueFileName;

                // Move uploaded file to target directory
                if (move_uploaded_file($_FILES[$inputName]["tmp_name"], $target_file)) {
                    // File uploaded successfully, store file information in database
                    $sql = "INSERT INTO documents (email, filename, filetype) VALUES ('$email', '$uniqueFileName', '$fileType')";
                    if ($conn->query($sql) === TRUE) {
                        // File information stored successfully
                        $fileNames[] = $fileName;
                    } else {
                        // Error storing file information in database
                        $uploadErrors = true;
                    }
                } else {
                    // Error moving uploaded file
                    $uploadErrors = true;
                }
            } else {
                // File upload error occurred
                $uploadErrors = true;
            }
        }
    }

    // Close database connection
    $conn->close();

    // Output success or error message
    if (!$uploadErrors) {
        echo "Files uploaded successfully: " . implode(", ", $fileNames);
    } else {
        echo "Error uploading files.";
    }
}
$pdf = new FPDF();
$pdf->addPage();

// Educational details table
$pdf->SetFont('Arial', 'B', 15);
$pdf->Cell(80);
$pdf->Cell(30, 10, 'Referees', 0, 1, 'C');
$pdf->Ln(10);
$query = "SELECT * FROM referee WHERE email = '$email'";
$result = $mysqli->query($query);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {

                 $pdf->SetFont('Arial', '', 12);
                $pdf->Cell(0, 10, 'Name: ' . $row['referee_name'], 0, 1);
                $pdf->Cell(0, 10, 'Position: ' . $row['position'], 0, 1);
                $pdf->Cell(0, 10, 'Association with Referee: ' . $row['association_with_referee'], 0, 1);
                $pdf->Cell(0, 10, 'Institution/Organization: ' . $row['institution'], 0, 1);
                $pdf->Cell(0, 10, 'E-mail: ' . $row['emailR'], 0, 1);
                $pdf->Cell(0, 10, 'Contact No.: ' . $row['contact_no'], 0, 1);
            }
          }
   $pdf->Ln();       
$pdf->SetFont('Arial', 'B', 15);
$pdf->Cell(80);
$pdf->Cell(30, 10, 'Check list of documents attached with online application', 0, 1, 'C');
$pdf->Ln(10);
$query = "SELECT * FROM documents WHERE email = '$email'";
$result = $mysqli->query($query);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {

                 $pdf->SetFont('Arial', '', 12);
                $pdf->Cell(0, 10, '* ' . $row['filetype'], 0, 1);
            }
          }
          $file_path = 'pdf_output/output8.pdf';
                $pdf->Output($file_path, 'F');
                
 $pdf->Ln();
?>



<html>
<head>
	<title>Referees & Upload</title>
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
  /*padding: 10 !important;*/
  text-align: left!important;
  margin-top: -5px;
  font-family: 'Noto Serif', serif;
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

.panel-info .panel-heading{
  font-size: 1.1em;
  font-family: 'Oswald', sans-serif!important;
  padding-top: 5px;
  padding-bottom: 5px;
}

.panel-danger .panel-heading{
  font-size: 1.1em;
  font-family: 'Oswald', sans-serif!important;
  padding-top: 5px;
  padding-bottom: 5px;
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


.upload_crerti
{
  font-size: 1.1em;
  font-weight: bold;
  color: red;
  text-align: center;
}

.update_crerti
{
  font-size: 1.1em;
  font-weight: bold;
  color: green;
  text-align: center;
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
       </fieldset>



<!-- publication file upload           -->

<form class="form-horizontal" action="#" method="post" enctype="multipart/form-data">


   <!-- Reprints of 5 Best Research Papers  -->

  <h4 style="text-align:center; font-weight: bold; color: #6739bb;">20. Reprints of 5 Best Research Papers *</h4>
   <div class="row">

                
            <div class="col-md-12">
              <div class="panel panel-info">
            <div class="panel-heading">Upload 5 Best Research Papers in a single PDF < 6MB </div>
                <div class="panel-body">
                  <div class="col-md-5">
                    <p class="upload_crerti">Upload 5 best papers</p>
                  </div>
                  <div class="col-md-7">
                  <input id="full_5_paper" name="userfile7" type="file" class="form-control input-md" required="">
                  </div>
              </div>
            </div>
          </div>
                
  </div>

 
 
<!-- certificate file code start -->
<h4 style="text-align:center; font-weight: bold; color: #6739bb;">21. Check List of the documents attached with the online application *</h4>

<div class="row">
  <div class="col-md-12">
  <div class="panel panel-success">
  <div class="panel-heading">Check List of the documents attached with the online application (Documents should be uploaded in PDF format only):
    <br />
    <small style="color: red;">Uploaded PDF files will not be displayed as part of the printed form.</small>
  </div>
    <div class="panel-body">
      <div class="row">
  
        <!-- <form action="https://ofa.iiti.ac.in/facrec_che_2023_july_02/submission_complete/upload" method="post" enctype="multipart/form-data"> -->
        <input type="hidden" name="ci_csrf_token" value="" />
     
     <!-- phd certificate  -->
    
      <div class="col-md-4">
        <div class="panel panel-danger">
      <div class="panel-heading">PHD Certificate *</div>
          <div class="panel-body">
        <p class="upload_crerti">Upload PHD Certificate</p>
        <input id="phd" name="userfile" type="file" class="form-control input-md" required="">
        </div>
      </div>
    </div>
        
         

     <!-- Master certificate  -->


            
          <div class="col-md-4">
            <div class="panel panel-danger">
          <div class="panel-heading">PG Documents*</div>
              <div class="panel-body">
            <p class="upload_crerti">Upload All semester/year-Marksheets and degree certificate</p>
            <input id="post_gr" name="userfile1" type="file" class="form-control input-md" >
            </div>
          </div>
        </div>
            
              

 
 <!-- Bachelor certificate  -->


    
      <div class="col-md-4">
        <div class="panel panel-danger">
      <div class="panel-heading">UG Documents*</div>
          <div class="panel-body">
        <p class="upload_crerti">Upload All semester/year-Marksheets and degree certificate </p>
        <input id="under_gr" name="userfile2" type="file" class="form-control input-md" required="">
        </div>
      </div>
    </div>
             


      <!-- 12th certificate  -->


              
           <div class="col-md-4">
             <div class="panel panel-danger">
           <div class="panel-heading">12th/HSC/Diploma Documents *</div>
               <div class="panel-body">
             <p class="upload_crerti">Upload 12th/HSC/Diploma/Marksheet(s) and passing certificate</p>
             <input id="higher_sec" name="userfile3" type="file" class="form-control input-md" required="">
             </div>
           </div>
         </div>
                  



   <!-- 10th certificate  -->


        
        <div class="col-md-4">
          <div class="panel panel-danger">
        <div class="panel-heading">10th/SSC Documents *</div>
            <div class="panel-body">
          <p class="upload_crerti">Upload 12th/HSC/Diploma/Marksheet(s) and passing certificate</p>
          <input id="high_school" name="userfile4" type="file" class="form-control input-md" required="">
          </div>
        </div>
      </div>
            


    <!-- Pay Slip -->

        
        <div class="col-md-4">
          <div class="panel panel-danger">
        <div class="panel-heading">Pay Slip *</div>
            <div class="panel-body">
          <p class="upload_crerti">Upload Pay Slip</p>
          <input id="pay_slip" name="userfile9" type="file" class="form-control input-md" >
          </div>
        </div>
      </div>
            

<!-- Under Taking NOC -->

<!-- Pay Slip -->


    <div class="col-md-6">
      <div class="panel panel-danger">
    <div class="panel-heading">NOC or Undertaking *</div>
        <div class="panel-body">
      <p class="upload_crerti">Undertaking-in case, NOC is not available at the time of application but will be provided at the time of interview</p>
      <input id="noc_under" name="userfile10" type="file" class="form-control input-md" required="">
      </div>
    </div>
  </div>
       
        <!-- 10 years post phd exp certificate  -->

                  
             <div class="col-md-5">
               <div class="panel panel-danger">
             <div class="panel-heading">Post phd Experience Certificate/All Experience Certificates/ Last Pay slip/ *</div>
                 <div class="panel-body">
               <p class="upload_crerti">Upload Certificate</p>
               <input id="post_phd_10" name="userfile8" type="file" class="form-control input-md" required="">
               </div>
             </div>
           </div>
                 


       

     <!-- Misc certificate  -->


            
          <div class="col-md-12">
            <div class="panel panel-info">
          <div class="panel-heading">Upload any other relevant document in a single PDF (For example award certificate, experience certificate etc) . If there are multiple documents, combine all the documents in a single PDF <1MB. </div>
              <div class="panel-body">
                <div class="col-md-5">
                  <p class="upload_crerti">Upload any other document</p>
                </div>
                <div class="col-md-7">
                <input id="misc_certi" name="userfile6" type="file" class="form-control input-md">
                </div>
            </div>
          </div>
        </div>
              





        <div class="col-md-2"> 
        <!-- <input type="submit" value="Upload" name="upload_submit" class="btn btn-danger" required="" /> -->
        <!-- <br /><br /> -->
        </div>
      <!-- </form> -->
      </div> 

      
    
   </div>
  </div>
<!-- </div> -->

</div>
</div>



<!-- Signature certificate  -->

<div class="row">
  
     <div class="col-md-4">
       <div class="panel panel-danger">
     <div class="panel-heading">Upload your Signature in JPG only *</div>
         <div class="panel-body">
       <p class="upload_crerti">Upload your signature</p>
       <input id="signature" name="userfile5" type="file" class="form-control input-md" required="">
       </div>
     </div>
     
   </div>
         

   <div class="col-md-12">
  
   </div>

</div>

<h4 style="text-align:center; font-weight: bold; color: #6739bb;">22. Referees *</h4>

       <div class="row">
       <div class="col-md-12">
         <div class="panel panel-success">
         <div class="panel-heading">Fill the Details</div>
           <div class="panel-body">
             <table class="table table-bordered">
                 <tbody id="acde">
                 
                 <tr height="30px">
                   <th class="col-md-2"> Name </th>
                   <th class="col-md-3"> Position </th>
                   <th class="col-md-3"> Association with Referee</th>
                   <th class="col-md-3"> Institution/Organization</th>
                   <th class="col-md-2"> E-mail </th>
                   <th class="col-md-2"> Contact No.</th>
                 </tr>
                 
                 
               

                 <tr height="60px">
                   <td class="col-md-2">  
                       <input id="ref_name1" name="ref_name[]" type="text" value="" placeholder="Name" class="form-control input-md" required="" autofocus=""> 
                   </td>

                   <td class="col-md-2"> 
                       <input id="position1" name="position[]" type="text" value=""  placeholder="Position" class="form-control input-md" required=""> 
                     </td>

                   <td class="col-md-2"> 
                     <select id="association_referee1" name="association_referee[]" class="form-control input-md" required="">

                       <option value="">Select</option>
                       <option  value="Thesis Supervisor">Thesis Supervisor</option>
                       <option  value="Postdoc Supervisor">Postdoc Supervisor</option>
                       <option  value="Research Collaborator">Research Collaborator</option>
                       <option  value="Other">Other</option>
                     </select>
                     </td>

                 
                    <td class="col-md-2"> 
                     <input id="org1" name="org[]" type="text" value=""  placeholder="Institution/Organization" class="form-control input-md" required=""> 
                   </td>
                   <td class="col-md-2"> 
                     <input id="emailR1" name="emailR[]" type="email" value=""  placeholder="E-mail" class="form-control input-md" required=""> 
                   </td>
                   <td class="col-md-2"> 
                     <input id="phone1" name="phone[]" type="text" value=""  placeholder="Contact No." class="form-control input-md" maxlength="20" required=""> 
                   </td>

                   
                 </tr>
                 
               

                 <tr height="60px">
                   <td class="col-md-2">  
                       <input id="ref_name2" name="ref_name[]" type="text" value="" placeholder="Name" class="form-control input-md" required="" autofocus=""> 
                   </td>

                   <td class="col-md-2"> 
                       <input id="position2" name="position[]" type="text" value=""  placeholder="Position" class="form-control input-md" required=""> 
                     </td>

                   <td class="col-md-2"> 
                     <select id="association_referee2" name="association_referee[]" class="form-control input-md" required="">

                       <option value="">Select</option>
                       <option  value="Thesis Supervisor">Thesis Supervisor</option>
                       <option  value="Postdoc Supervisor">Postdoc Supervisor</option>
                       <option  value="Research Collaborator">Research Collaborator</option>
                       <option  value="Other">Other</option>
                     </select>
                     </td>

                 
                    <td class="col-md-2"> 
                     <input id="org2" name="org[]" type="text" value=""  placeholder="Institution/Organization" class="form-control input-md" required=""> 
                   </td>
                   <td class="col-md-2"> 
                     <input id="emailR2" name="emailR[]" type="email" value=""  placeholder="E-mail" class="form-control input-md" required=""> 
                   </td>
                   <td class="col-md-2"> 
                     <input id="phone2" name="phone[]" type="text" value=""  placeholder="Contact No." class="form-control input-md" maxlength="20" required=""> 
                   </td>

                   
                 </tr>
                 
               

                 <tr height="60px">
                   <td class="col-md-2">  
                       <input id="ref_name3" name="ref_name[]" type="text" value="" placeholder="Name" class="form-control input-md" required="" autofocus=""> 
                   </td>

                   <td class="col-md-2"> 
                       <input id="position3" name="position[]" type="text" value=""  placeholder="Position" class="form-control input-md" required=""> 
                     </td>

                   <td class="col-md-2"> 
                     <select id="association_referee3" name="association_referee[]" class="form-control input-md" required="">

                       <option value="">Select</option>
                       <option  value="Thesis Supervisor">Thesis Supervisor</option>
                       <option  value="Postdoc Supervisor">Postdoc Supervisor</option>
                       <option  value="Research Collaborator">Research Collaborator</option>
                       <option  value="Other">Other</option>
                     </select>
                     </td>

                 
                    <td class="col-md-2"> 
                     <input id="org3" name="org[]" type="text" value=""  placeholder="Institution/Organization" class="form-control input-md" required=""> 
                   </td>
                   <td class="col-md-2"> 
                     <input id="emailR3" name="emailR[]" type="email" value=""  placeholder="E-mail" class="form-control input-md" required=""> 
                   </td>
                   <td class="col-md-2"> 
                     <input id="phone3" name="phone[]" type="text" value=""  placeholder="Contact No." class="form-control input-md" maxlength="20" required=""> 
                   </td>

                   
                 </tr>
                                  
              
               </tbody>
             </table>

         </div>
       </div>
       </div>
       </div>

<!-- Payment file upload           -->



<!-- Referees Details -->


<input type="hidden" name="ci_csrf_token" value="" />
    
 
<hr> 
<div class="form-group">
<div class="col-md-10">
  <!-- <a href="https://ofa.iiti.ac.in/facrec_che_2023_july_02/acde" class="btn btn-primary pull-left">BACK</a> -->
  <a href="https://ofa.iiti.ac.in/facrec_che_2023_july_02/rel_info" class="btn btn-primary pull-left"><i class="glyphicon glyphicon-fast-backward"></i></a>
  
  <!-- <button type="submit" name="submit" value="Submit" class="btn btn-success">SAVE</button> -->


</div>

<div class="col-md-2">
  <button id="submit" type="submit" name="submit" value="Submit" class="btn btn-success pull-right" style="margin-left: 75%;">SAVE</button>
    <strong class=" pull-right" style="color: green;"><a href='Page9.php' class="btn btn-sm btn-success">NEXT</a></strong>

  <!-- <button id="submit" type="submit" name="submit" value="Submit" class="btn btn-success pull-right">Final Submission</button> -->

</div>


</div>

</form>
</div> 
</div>
<script type="text/javascript">
function confirm_box()
{
  if(confirm("Dear Candidate, \n\nAre you sure that you are ready to submit the application? Press OK to submit the application. Press CANCEL to edit. \nOnce you press OK you cannot make any changes.\n\nThank you."))
  {
    return true;
  }
  else
  {
    return false;
  }
}
function submit_frm()
{
  alert();
  document.getElementById("upload_frm").submit();
}
</script>



<script type="text/javascript">
  $(document).ready(function () 
  {
   
    var list1 = document.getElementById('applicant_cate');
     
    list1.options[0] = new Option('Select/Category', '');
    list1.options[1] = new Option('Other Applicants', 'Other Applicants');
    list1.options[2] = new Option('OBC-NC, PwD, EWS and Female Applicants', 'OBC-NC, PwD, EWS and Female Applicants');
    list1.options[3] = new Option('SC, ST and Faculty Applicants from IIT Indore', 'SC, ST and Faculty Applicants from IIT Indore');
   

    $("#applicant_cate option").each(function()
    {

           if($(this).val()==selectoption){
        $(this).attr('selected', 'selected');
      }
      // Add $(this).val() to your list
    });

    getFoodItem();
      $("#payment_amount option").each(function()
    {

           if($(this).val()==selectsubthemeoption){
        $(this).attr('selected', 'selected');
      }
      // Add $(this).val() to your list
    });
  });

  
  function getFoodItem()
  {
 
    var list1 = document.getElementById('applicant_cate');
    var list2 = document.getElementById("payment_amount");
    var list1SelectedValue = list1.options[list1.selectedIndex].value;


    if (list1SelectedValue=='Other Applicants')
    {
         
        // list2.options.length=0;
        // list2.options[0] = new Option('Select Amount', '');
        list2.options[0] = new Option('INR 1000', 'INR 1000');
        
         
    }
    else if (list1SelectedValue=='OBC-NC, PwD, EWS and Female Applicants')
    {
         
        // list2.options.length=0;
        // list2.options[0] = new Option('Select Amount', '');
        list2.options[0] = new Option('INR 500', 'INR 500');
       
         
    }

    else if (list1SelectedValue=='SC, ST and Faculty Applicants from IIT Indore')
    {
         
        // list2.options.length=0;
        // list2.options[0] = new Option('Select Amount', '');
        list2.options[0] = new Option('NIL', 'NIL');
       
         
    }


    
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