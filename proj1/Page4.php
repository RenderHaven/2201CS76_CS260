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
    // Summary of Publications
    $summary_journal_inter = $_POST['summary_journal_inter'];
    $summary_journal = $_POST['summary_journal'];
    $summary_conf_inter = $_POST['summary_conf_inter'];
    $summary_conf_national = $_POST['summary_conf_national'];
    $patent_publish = $_POST['patent_publish'];
    $summary_book = $_POST['summary_book'];
    $summary_book_chapter = $_POST['summary_book_chapter'];
    $google_link = $_POST['google_link'];

    // Prepare and execute SQL statement
    $sql = "INSERT INTO publication (email, summary_journal_inter, summary_journal, summary_conf_inter, summary_conf_national, patent_publish, summary_book, summary_book_chapter, google_link)
            VALUES ('$email', '$summary_journal_inter', '$summary_journal', '$summary_conf_inter', '$summary_conf_national', '$patent_publish', '$summary_book', '$summary_book_chapter', '$google_link')";
    $mysqli->query($sql);   
    // Handle Book(s) data
    if (isset($_POST['bauthor'])) {
        // Retrieve data from form
        $author = $_POST['bauthor'];
        $title = $_POST['btitle'];
        $year = $_POST['byear'];
        $isbn = $_POST['bisbn'];

        // Loop through the data and insert into database
        for ($i = 0; $i < count($author); $i++) {
            $author_val = $author[$i];
            $title_val = $title[$i];
            $year_val = $year[$i];
            $isbn_val = $isbn[$i];

            // Prepare and execute SQL statement
            $sql = "INSERT INTO book (email, author, title, year, isbn) VALUES ('$email', '$author_val', '$title_val', '$year_val', '$isbn_val')";
            $mysqli->query($sql);
        }
    }

    // Handle Book Chapter(s) data
    if (isset($_POST['bc_author'])) {
        // Retrieve data from form
        $bc_author = $_POST['bc_author'];
        $bc_title = $_POST['bc_title'];
        $bc_year = $_POST['bc_year'];
        $bc_isbn = $_POST['bc_isbn'];

        // Loop through the data and insert into database
        for ($i = 0; $i < count($bc_author); $i++) {
            $bc_author_val = $bc_author[$i];
            $bc_title_val = $bc_title[$i];
            $bc_year_val = $bc_year[$i];
            $bc_isbn_val = $bc_isbn[$i];

            // Prepare and execute SQL statement
            $sql = "INSERT INTO book_chapter (email, bc_author, bc_title, bc_year, bc_isbn) VALUES ('$email', '$bc_author_val', '$bc_title_val', '$bc_year_val', '$bc_isbn_val')";
            $mysqli->query($sql);
        }
    }

    // Handle Patent(s) data
    if (isset($_POST['pauthor'])) {
        // Retrieve data from form
        $pauthor = $_POST['pauthor'];
        $ptitle = $_POST['ptitle'];
        $pcountry = $_POST['p_country'];
        $pnumber = $_POST['p_number'];
        $pyear_filed = $_POST['pyear_filed'];
        $pyear_published = $_POST['pyear_published'];
        $pyear_issued = $_POST['pyear_issued'];

        // Loop through the data and insert into database
        for ($i = 0; $i < count($pauthor); $i++) {
            $pauthor_val = $pauthor[$i];
            $ptitle_val = $ptitle[$i];
            $pcountry_val = $pcountry[$i];
            $pnumber_val = $pnumber[$i];
            $pyear_filed_val = $pyear_filed[$i];
            $pyear_published_val = $pyear_published[$i];
            $pyear_issued_val = $pyear_issued[$i];

            // Prepare and execute SQL statement
            $sql = "INSERT INTO patent (email, pauthor, ptitle, pcountry, pnumber, pyear_filed, pyear_published, pyear_issued) 
                    VALUES ('$email', '$pauthor_val', '$ptitle_val', '$pcountry_val', '$pnumber_val', '$pyear_filed_val', '$pyear_published_val', '$pyear_issued_val')";
            $mysqli->query($sql);
        }
      }

      // Handle Journal/Conference data
    if (isset($_POST['author'])) {
        // Retrieve data from form
        $author = $_POST['author'];
        $title = $_POST['title'];
        $journal = $_POST['journal'];
        $year = $_POST['year'];
        $impact = $_POST['impact'];
        $doi = $_POST['doi'];
        $status = $_POST['status'];

        // Loop through the data and insert into database
        for ($i = 0; $i < count($author); $i++) {
            $author_val = $author[$i];
            $title_val = $title[$i];
            $journal_val = $journal[$i];
            $year_val = $year[$i];
            $impact_val = $impact[$i];
            $doi_val = $doi[$i];
            $status_val = $status[$i];

            // Prepare and execute SQL statement
            $sql = "INSERT INTO jour (email, author, title, journal, year, impact, doi, status) 
                    VALUES ('$email', '$author_val', '$title_val', '$journal_val', '$year_val', '$impact_val', '$doi_val', '$status_val')";
            $mysqli->query($sql);
        }
    }

    if ($mysqli->query($sql) === TRUE) {
        echo "Publication data inserted successfully";
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
$pdf->Cell(30, 10, 'Summary of Publication', 0, 1, 'C');
$pdf->Ln(10);
$query = "SELECT * FROM publication WHERE email = '$email'";
$result = $mysqli->query($query);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {

$pdf->Ln();
                 $pdf->SetFont('Arial', '', 12);
                $pdf->Cell(0, 10, 'Number of International Journal Papers' . $row['summary_journal_inter'], 0, 1);
                $pdf->Cell(0, 10, 'Number of National Journal Papers: ' . $row['summary_journal'], 0, 1);
                $pdf->Cell(0, 10, 'Number of International Conference papers: ' . $row['summary_conf_inter'], 0, 1);
                $pdf->Cell(0, 10, 'Number of National Conference Papers: ' . $row['summary_conf_national'], 0, 1);
                $pdf->Cell(0, 10, 'Number of Patent(s): ' . $row['patent_publish'], 0, 1);
                $pdf->Cell(0, 10, 'Number of Book(s): ' . $row['summary_book'], 0, 1);
                $pdf->Cell(0, 10, 'Number of Book Chapter(s): ' . $row['summary_book_chapter'], 0, 1);
            }
          }
 $pdf->Ln();
 $pdf->SetFont('Arial', 'B', 15);
$pdf->Cell(80);
$pdf->Cell(30, 10, 'List of Research Publications', 0, 1, 'C');
$pdf->Ln(10);
$query1 = "SELECT * FROM jour WHERE email = '$email'";
$result1 = $mysqli->query($query1);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, '(A) Journals', 0, 1);
$pdf->Ln();
        if ($result1->num_rows > 0) {
            while ($row = $result1->fetch_assoc()) {

                 $pdf->SetFont('Arial', '', 12);
                 $pdf->Cell(0, 10, 'Author: ' . $row['author'], 0, 1);
                $pdf->Cell(0, 10, 'Title: ' . $row['title'], 0, 1);
                $pdf->Cell(0, 10, 'Name of Journal: ' . $row['journal'], 0, 1);
                $pdf->Cell(0, 10, 'Year: ' . $row['year'], 0, 1);
                $pdf->Cell(0, 10, 'Impact: ' . $row['impact'], 0, 1);
                $pdf->Cell(0, 10, 'DOI: ' . $row['doi'], 0, 1);
                $pdf->Cell(0, 10, 'Status: ' . $row['status'], 0, 1);
            }
          }
$pdf->Ln();
$query2 = "SELECT * FROM book WHERE email = '$email'";
$result2 = $mysqli->query($query2);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, '(B) Book', 0, 1);
$pdf->Ln();
        if ($result2->num_rows > 0) {
            while ($row = $result2->fetch_assoc()) {

                 $pdf->SetFont('Arial', '', 12);
                 $pdf->Cell(0, 10, 'Author: ' . $row['author'], 0, 1);
                $pdf->Cell(0, 10, 'Title: ' . $row['title'], 0, 1);
                $pdf->Cell(0, 10, 'Year of Publication: ' . $row['year'], 0, 1);
                $pdf->Cell(0, 10, 'ISBN: ' . $row['isbn'], 0, 1);
            }
          }   

$pdf->Ln();
$query3 = "SELECT * FROM book_chapter WHERE email = '$email'";
$result3 = $mysqli->query($query3);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, '(C) Book Chapter(s)', 0, 1);
$pdf->Ln();
        if ($result3->num_rows > 0) {
            while ($row = $result3->fetch_assoc()) {

                 $pdf->SetFont('Arial', '', 12);
                 $pdf->Cell(0, 10, 'Author: ' . $row['bc_author'], 0, 1);
                $pdf->Cell(0, 10, 'Title: ' . $row['bc_title'], 0, 1);
                $pdf->Cell(0, 10, 'Year of Publication: ' . $row['bc_year'], 0, 1);
                $pdf->Cell(0, 10, 'ISBN: ' . $row['bc_isbn'], 0, 1);
            }
          } 

$pdf->Ln();
$query4 = "SELECT * FROM patent WHERE email = '$email'";
$result4 = $mysqli->query($query4);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, '(D) Patent(s)', 0, 1);
$pdf->Ln();
        if ($result4->num_rows > 0) {
            while ($row = $result4->fetch_assoc()) {

                 $pdf->SetFont('Arial', '', 12);
                 $pdf->Cell(0, 10, 'Inventor: ' . $row['pauthor'], 0, 1);
                $pdf->Cell(0, 10, 'Title of Patent: ' . $row['ptitle'], 0, 1);
                $pdf->Cell(0, 10, 'Country of Patent: ' . $row['pcountry'], 0, 1);
                $pdf->Cell(0, 10, 'Patent Number: ' . $row['pnumber'], 0, 1);
                $pdf->Cell(0, 10, 'Date of filing: ' . $row['pyear_filed'], 0, 1);
                $pdf->Cell(0, 10, 'Date of published: ' . $row['pyear_published'], 0, 1);
                $pdf->Cell(0, 10, 'Status: ' . $row['pyear_issued'], 0, 1);
            }
          }           
          // Output PDF to file
                $file_path = 'pdf_output/output4.pdf';
                $pdf->Output($file_path, 'F');
                 $pdf->Ln();
// Close connection
$mysqli->close();
?>


<html>
<head>
	<title>Publication Details</title>
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
                $('#dob').datepicker({
                    format: 'dd/mm/yyyy',
                    autoclose: true
                });
            });
</script>

<script type="text/javascript">
var tr="";
var counter_jour=1;
// var counter_confer=1;
var counter_book=1;
var counter_book_chapter=1;
var counter_patent=1;
  $(document).ready(function(){

    $("#add_more_jour").click(function(){
        create_tr();
        create_serial('jour');
        create_input('author[]', 'Author', 'author'+counter_jour,'jour', counter_jour, 'jour');
        create_input('title[]', 'Title', 'title'+counter_jour,'jour', counter_jour, 'jour');
        create_input('journal[]', 'Journal', 'journal'+counter_jour,'jour', counter_jour, 'jour');
        create_input('year[]', 'Year, Vol., Page', 'year'+counter_jour,'jour', counter_jour, 'jour');
        create_input('impact[]', 'Impact Factor','impact'+counter_jour, 'jour', counter_jour, 'jour');
        create_input('doi[]', 'DOI','doi'+counter_jour, 'jour', counter_jour, 'jour');
        create_input('status[]', 'Status', 'status'+counter_jour,'jour', counter_jour,'jour',true, true);
        counter_jour++;
        return false;
    });

    // $("#add_more_confer").click(function(){
    //     create_tr();
    //     create_serial('confer');
    //     create_input('cname[]', 'Conference','cname'+counter_confer, 'confer',counter_confer, 'confer');
    //     create_input('ctitle[]', 'Title', 'ctitle'+counter_confer,'confer',counter_confer, 'confer');
    //     create_input('cyear[]', 'Year', 'cyear'+counter_confer,'confer',counter_confer, 'confer',true);
    //     counter_confer++;
    //     return false;
    // });

    $("#add_more_book").click(function(){
        create_tr();
        create_serial('book');
        create_input('bauthor[]', 'Book','bauthor'+counter_book, 'book',counter_book, 'book');
        create_input('btitle[]', 'Title of the Book', 'btitle'+counter_book,'book',counter_book, 'book');
        create_input('byear[]', 'Year', 'byear'+counter_book,'book',counter_book, 'book');
        create_input('bisbn[]', 'ISBN', 'bisbn'+counter_book,'book',counter_book, 'book',true);
        // create_input('bstatus[]', 'Status', 'bstatus'+counter_book,'book', counter_book,'book',true, true);
        // create_input('dol[]', 'Date of Leaving', 'dol'+counter_exp,'exp',counter_exp, 'exp');
        // create_input('duration2[]', 'Duration','duration2'+counter_exp, 'exp',counter_exp,'exp', true);
        // //create_input('perce[]', 'Percentage', 'perce'+counter_exp,'exp', true);
        counter_book++;
        return false;
    });


    $("#add_more_book_chapter").click(function(){
        create_tr();
        create_serial('book_chapter');
        create_input('bc_author[]', 'Book Chapter','bc_author'+counter_book_chapter, 'book_chapter',counter_book_chapter, 'book_chapter');
        create_input('bc_title[]', 'Title', 'bc_title'+counter_book_chapter,'book_chapter',counter_book_chapter, 'book_chapter');
        create_input('bc_year[]', 'Year', 'bc_year'+counter_book_chapter,'book_chapter',counter_book_chapter, 'book_chapter');
        create_input('bc_isbn[]', 'ISBN', 'bc_isbn'+counter_book_chapter,'book_chapter',counter_book_chapter, 'book_chapter',true);
        counter_book_chapter++;
        return false;
    });


    $("#add_more_patent").click(function(){
        create_tr();
         create_serial('patent');
        create_input('pauthor[]', 'Inventor(s)','pauthor'+counter_patent, 'patent',counter_patent, 'patent');
        // create_input('p_year[]', 'Year of the patent','p_year'+counter_patent, 'patent',counter_patent, 'patent');
        create_input('ptitle[]', 'Title of Patent', 'ptitle'+counter_patent,'patent',counter_patent, 'patent');
        create_input('p_country[]', 'Country of patent','p_country'+counter_patent, 'patent',counter_patent, 'patent');
        create_input('p_number[]', 'Patent Number','p_number'+counter_patent, 'patent',counter_patent, 'patent');
        create_input('pyear_filed[]', 'DD/MM/YYYY','pyear_filed'+counter_patent, 'patent',counter_patent, 'patent');
        create_input('pyear_published[]', 'DD/MM/YYYY','pyear_published'+counter_patent, 'patent',counter_patent, 'patent');
        create_input('pyear_issued[]', 'DD/MM/YYYY','pyear_issued'+counter_patent, 'patent',counter_patent, 'patent',true);
        // create_input('pyear[]', 'Year', 'pyear'+counter_patent,'patent',counter_patent, 'patent',true);
        // create_input('pstatus[]', 'Status', 'pstatus'+counter_patent,'patent', patent,'patent',true, true);
        // create_input('dol[]', 'Date of Leaving', 'dol'+counter_exp,'exp',counter_exp, 'exp');
        // create_input('duration2[]', 'Duration','duration2'+counter_exp, 'exp',counter_exp,'exp', true);
        // //create_input('perce[]', 'Percentage', 'perce'+counter_exp,'exp', true);
        counter_patent++;
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
  function create_input(t_name, place_value, id, tbody_id, counter, remove_name, btn=false, select=false)
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
      sel.innerHTML+="<option value='published'>Published</option>";
      sel.innerHTML+="<option value='accepted'>Accepted</option>";
      // sel.innerHTML+="<option value='in_preparation'>In-Preparation</option>";
      var td=document.createElement("td");
      td.appendChild(sel);
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
        
<!-- all bootstrap buttons classes -->
<!-- 
  class="btn btn-sm, btn-lg, "
  color - btn-success, btn-primary, btn-default, btn-danger, btn-info, btn-warning
-->



<a href='https://ofa.iiti.ac.in/facrec_che_2023_july_02/layout'></a>

<div class="container">
  
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-8 well">
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

             

    
            <!-- Form Name -->
            
              
            <!-- Text input-->
           
            <h4 style="text-align:center; font-weight: bold; color: #6739bb;">5. Summary of Publications *</h4>
            <div class="row">
              <div class="col-md-12">
              <div class="panel panel-success">
              <div class="panel-body">

                <span class="col-md-5 control-label" for="summary_journal_inter">Number of International Journal Papers</span>  
                <div class="col-md-1">
                <input id="summary_journal_inter" value="" name="summary_journal_inter" type="text" placeholder="" class="form-control input-md" required="" maxlength="3">
                </div>

                <span class="col-md-5 control-label" for="summary_journal">Number of National Journal Papers</span>  
                <div class="col-md-1">
                <input id="summary_journal" value="" name="summary_journal" type="text" placeholder="" class="form-control input-md" required="" maxlength="3">
                </div>

                <span class="col-md-5 control-label" for="summary_conf_inter">Number of International Conference Papers</span>  
                <div class="col-md-1">
                <input id="summary_conf_inter" value="" name="summary_conf_inter" type="text" placeholder="" class="form-control input-md" required="" maxlength="3">
                </div>

                <span class="col-md-5 control-label" for="summary_conf_national">Number of National Conference Papers</span>  
                <div class="col-md-1">
                <input id="summary_conf_national" value="" name="summary_conf_national" type="text" placeholder="" class="form-control input-md" required="" maxlength="3">
                </div>

                <span class="col-md-5 control-label" for="patent_publish">Number of Patent(s) [Filed, Published, Granted] </span>  
                <div class="col-md-1">
                <input id="patent_publish" value="" name="patent_publish" type="text" placeholder="" class="form-control input-md" required="" maxlength="3">
                </div>

                <span class="col-md-5 control-label" for="summary_book">Number of Book(s) </span>  
                <div class="col-md-1">
                <input id="summary_book" value="" name="summary_book" type="text" placeholder="" class="form-control input-md" required="" maxlength="3">
                </div>

                <span class="col-md-5 control-label" for="summary_book_chapter">Number of Book Chapter(s)</span>  
                <div class="col-md-1">
                <input id="summary_book_chapter" value="" name="summary_book_chapter" type="text" placeholder="" class="form-control input-md" required="" maxlength="3">
                </div>

              

              

               

                

              </div>
              </div>
              </div>
              </div>   

           
           <h4 style="text-align:center; font-weight: bold; color: #6739bb;">6. List of 10 Best Publications (Journal/Conference)</h4>

           <div class="container-fluid table-responsive">
              <div class="row">
                

               <div class="panel panel-success">
                <div class="panel-heading">List of 10 Best Publications (Journal/Conference) &nbsp;&nbsp;&nbsp;
                  <button class="btn btn-sm btn-danger" id="add_more_jour">Add Details</button>
                </div>
                <table class="table table-bordered">
                    <tbody id="jour">
                    
                    <tr height="30px">
                      <th class="col-md-1"> S. No.</th>
                      <th class="col-md-2"> Author(s) </th>
                      <th class="col-md-1"> Title</th>
                      <th class="col-md-2"> Name of Journal/Conference </th>
                      <th class="col-md-1"> Year, Vol., Page</th>
                      <th class="col-md-1"> Impact Factor </th>
                      <th class="col-md-1"> DOI</th>
                      <th class="col-md-2"> Status</th>
                    </tr>


                                      </tbody>
                </table>

               </div>
              
            </div>

              
            </div> 
 
               

             <div class="container-fluid table-responsive">

              <h4 style="text-align:center; font-weight: bold; color: #6739bb;">7. List of  Patent(s), Book(s), Book Chapter(s)</h4>
             <div class="row">

           <div class="panel panel-success">
            <div class="panel-heading">(A) Patent(s)&nbsp;&nbsp;&nbsp;<button class="btn btn-sm btn-danger" id="add_more_patent">Add Details</button>  </div>
            <table class="table table-bordered">
                <tbody id="patent">
                
                <tr height="30px">
                  <th class="col-md-1"> S. No.</th>
                  <th class="col-md-1"> Inventor(s) </th>
                  <!-- <th class="col-md-2"> Year of Patent </th> -->
                  <th class="col-md-2"> Title of Patent </th>
                  <th class="col-md-1"> Country of Patent </th>
                  <th class="col-md-1"> Patent Number</th>
                  <th class="col-md-1"> Date of Filing</th>
                  <th class="col-md-1"> Date of Published</th>
                  <th class="col-md-1"> Status Filed/Published/Granted</th>
                  <!-- <th class="col-md-1"> Date of Filed/Published (If not granted, mention "Awaited")</th> -->
                </tr>


                              </tbody>
            </table>
          </div>
             
           </div>

            
           </div>

            <!-- Book Text -->

             <div class="container-fluid table-responsive">
              <div class="row">

             <div class="panel panel-success">
             <div class="panel-heading">(B) Book(s) &nbsp;&nbsp;&nbsp;<button class="btn btn-sm btn-danger" id="add_more_book">Add Details</button></div>

             <table class="table table-bordered">
                 <tbody id="book">
                 
                 <tr height="30px">
                   <th class="col-md-1"> S. No.</th>
                   <th class="col-md-2"> Author(s)</th>
                   <th class="col-md-2"> Title of the Book </th>
                   <th class="col-md-2"> Year of Publication </th>
                   <th class="col-md-2"> ISBN</th>
                   <!-- <th class="col-md-2"> Status</th> -->
                 </tr>


                                </tbody>
             </table>
            </div>
              
            
            </div>

             
            </div>


            <br />
            <br />

            <!-- Book chapter Text -->

             <div class="container-fluid table-responsive">
              <div class="row">

             <div class="panel panel-success">
             <div class="panel-heading">(C) Book Chapter(s)&nbsp;&nbsp;&nbsp;<button class="btn btn-sm btn-danger" id="add_more_book_chapter">Add Details</button></div>

             <table class="table table-bordered">
                 <tbody id="book_chapter">
                 
                 <tr height="30px">
                   <th class="col-md-1"> S. No.</th>
                   <th class="col-md-2"> Author(s)</th>
                   <th class="col-md-2"> Title of the Book Chapter(s) </th>
                   <th class="col-md-2"> Year of Publication </th>
                   <th class="col-md-2"> ISBN</th>
                   <!-- <th class="col-md-2"> Status</th> -->
                 </tr>


                                </tbody>
             </table>
            </div>
              
            
            </div>

             
            </div>


            <br />
            <br />
            

 

            <h4 style="text-align:center; font-weight: bold; color: #6739bb;">8. Google Scholar Link *</h4>
            <div class="row">
            <div class="col-md-12">
            <div class="panel panel-success">
            <div class="panel-heading">URL</div>
            <div class="panel-body">
              <span class="col-md-2 control-label" for="google_link">Google Scholar Link </span>  
              <div class="col-md-10">
              <input id="google_link" value="" name="google_link" type="text" placeholder="Google Scholar Link" class="form-control input-md" required="">
              </div>

              

            </div>
            </div>
            </div>
            </div>


            <!-- Button -->
<div class="form-group">

  <div class="col-md-1">
    <a href="https://ofa.iiti.ac.in/facrec_che_2023_july_02/employment_details" class="btn btn-primary pull-left"><i class="glyphicon glyphicon-fast-backward"></i></a>
  </div>

<div class="col-md-11">
  <button id="submit" type="submit" name="submit" value="Submit" class="btn btn-success pull-right" style="margin-left: 75%;">SAVE</button>
    <strong class=" pull-right" style="color: green;"><a href='Page5.php' class="btn btn-sm btn-success">NEXT</a></strong>
  
</div>

             
            </div>
           

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