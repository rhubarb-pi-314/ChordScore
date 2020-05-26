<?php
	include"database.php";
	session_start();
	if(!(isset($_SESSION["account_id"]) && ($_SESSION["account_type"]) == 'student'))
	{
		echo"<script>window.open('index.php?mes=Access Denied..','_self');</script>";
		
	}		
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Assignments</title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
	</head>
	<body>
		<?php include"navbar.php";?><br>
			<img src="img/1.jpg" style="margin-left:90px;" class="sha">
			
			<div id="section">
			
				<?php include"class_name.php";?><br>
				<div class="content">
					<button id="back_btn" type="submit" class="btn" name="back" onclick="window.history.back()">&larr; Back</button>		
						<div style="margin-left:auto;margin-right: auto;width: 70%;height: auto; margin-top: -35px;">
							<h3 style="margin-top:30px; font-size: 25px; margin-left: 10px;"> </h3><br>
							<?php
								if(isset($_GET["mes"]))
								{
									echo"<div class='error'>{$_GET["mes"]}</div>";	
								}
							
							?>

								<?php

									$class="SELECT Assignment_Definition.title, Assignment.grade, Class_Assignment.open_datetime, Class_Assignment.due_datetime, Assignment_Definition.instructions 
										     FROM Assignment_Definition
									         JOIN Assignment ON Assignment_Definition.assignment_def_id = Assignment.assignment_def_id
									         JOIN Class_Assignment ON Assignment.assignment_id = Class_Assignment.assignment_id
									         WHERE Assignment.assignment_id = '{$_GET["id"]}'

									         ";


									$res=$db->query($class);
									if($res->num_rows>0)
									{
										$r=$res->fetch_assoc();
										echo"<h3>{$r["title"]}<h3><br>
											<p style=color:grey>{$r["open_datetime"]} - {$r["due_datetime"]}<br><br>
												  {$r["instructions"]}<br><br></p>
												  
											";

									}

									if($res->num_rows<=0){
										echo " Assignment is blank. Please contact your instructor.";
									}
								?>
						</div>

						<?php
							if (isset($_POST["submit"])){
							     /*#retrieve file title
							        $title = $_POST["title"];
							     
							    #file name with a random number so that similar dont get replaced
							     $pname = rand(1000,10000)."-".$_FILES["file"]["name"];
							 
							    #temporary file name to store file
							    $tname = $_FILES["file"]["tmp_name"];
							   
							     #upload directory path
								$uploads_dir = 'images';
							    #TO move the uploaded file to specific location
							    move_uploaded_file($tname, $uploads_dir.'/'.$pname);
							 
							    #sql query to insert into database
							    $sql = "INSERT into Assignment(title,image) VALUES('$title','$pname')";
							 
							    if(mysqli_query($conn,$sql)){
							 
							    	echo "<div class='success'>Upload Success..</div>";
							    }
							    else{
							        echo "<div class='error'>Upload failed..</div>";
							    }
							}*/


							echo"<script>window.open('grade.php?mes=Assignment Graded Successfully...','_self');</script>";
						}

						?>

						<div style="margin-left:auto;margin-right: auto;width: 70%;height: auto; margin-top: 30px;">
							<h2>Submit Assignment</h2>
							<form method="post" enctype="multipart/form-data">
						    
							    <label>After uploading, you must click Submit to complete the submission.</label><br>
							    <input type="file" name="file" id="ass_file"><br><br>
							    <label>Comment</label><br>
							    <textarea style="width: 65%" rows="4" cols="50"></textarea><br>
							    <input style="width: 15%; margin-bottom: 35px;" type="submit" name="submit" class="btn">
						 
							</form>
						</div>
						
				
					<br><br>
						<p style="border-top: #5ca08e 3px solid;float:left;width:100%;"></p>
					<br><br>
				</div>
				
			</div>
	
		<?php include"footer.php";?>
	<script src="js/jquery.js"></script>
	<script>
			$(document).ready(function(){
				$(".error").fadeTo(1500, 200).slideUp(1500, function(){
						$(".error").slideUp(1000);
				});
				
				$(".success").fadeTo(1000, 100).slideUp(1000, function(){
						$(".success").slideUp(1000);
				});
			});

			jQuery(window).load(function() {
			    jQuery("body").addClass('all-loaded');
			});

			
	</script>
	</body>
</html>